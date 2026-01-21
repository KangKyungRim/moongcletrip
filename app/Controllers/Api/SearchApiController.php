<?php

namespace App\Controllers\Api;

use App\Models\User;
use App\Models\Partner;
use App\Models\Stay;
use App\Models\Room;
use App\Models\Rateplan;
use App\Models\Tag;
use App\Models\MoongcleTag;
use App\Models\StayMoongcleOffer;

use Carbon\Carbon;
use Database;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

class SearchApiController
{
    public static function realtimeSearch()
    {
        global $_EXCLUDED_PARTNER_IDX;

        $allowedDomains = [
            'https://www.devmoongcletrip.com',
            'https://devmoongcletrip.com',
            'https://staging.moongcletrip.com',
            'https://www.moongcletrip.com',
        ];

        // 요청의 Origin이나 Referer 헤더 확인
        $origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';

        // 도메인 부분만 추출 (프로토콜 제외)
        $originHost = parse_url($origin, PHP_URL_SCHEME) . '://' . parse_url($origin, PHP_URL_HOST);

        // 허용된 도메인이 아닌 경우 접근 거부
        if (!in_array($originHost, $allowedDomains)) {
            return ResponseHelper::jsonResponse([
                'message' => '허용되지 않은 도메인에서의 접근입니다.'
            ], 403);
        }

        // 사용자가 입력한 검색어를 $_GET 변수로 가져옵니다.
        $searchTerm = isset($_GET['query']) ? trim($_GET['query']) : '';

        // 검색어가 비어있을 경우 빈 배열을 반환합니다.
        if (empty($searchTerm)) {
            return ResponseHelper::jsonResponse([
                'message' => '검색어가 비어있습니다.',
                'searchResult' => [],
            ], 200);
        }

        $cleanedSearchTerm = str_replace(' ', '', $searchTerm);

        // DB 쿼리를 사용하여 partner_city 컬럼에서 검색어를 포함한 도시명을 찾습니다.
        $cityResults = Partner::select('partner_address1')
            ->whereRaw("REPLACE(partner_address1, ' ', '') LIKE ?", ['%' . $cleanedSearchTerm . '%'])
            ->whereNotIn('partner_idx', $_EXCLUDED_PARTNER_IDX)
            ->distinct()
            ->limit(10)
            ->pluck('partner_address1');

        $regionResults = Partner::select('partner_region')
            ->whereRaw("REPLACE(partner_region, ' ', '') LIKE ?", ['%' . $cleanedSearchTerm . '%'])
            ->whereNotIn('partner_idx', $_EXCLUDED_PARTNER_IDX)
            ->distinct()
            ->limit(10)
            ->pluck('partner_region');

        $stayResults = Partner::select('partner_name')
            ->whereRaw("REPLACE(partner_name, ' ', '') LIKE ?", ['%' . $cleanedSearchTerm . '%'])
            ->where('partner_category', 'stay')
            ->where('partner_status', 'enabled')
            ->whereNotIn('partner_idx', $_EXCLUDED_PARTNER_IDX)
            ->distinct()
            ->limit(10)
            ->pluck('partner_name');

        $tagResults = MoongcleTag::select('tag_name')
            ->whereRaw("REPLACE(tag_name, ' ', '') LIKE ?", ['%' . $cleanedSearchTerm . '%'])
            ->distinct()
            ->limit(10)
            ->pluck('tag_name');

        // 결과를 JSON 형식으로 반환합니다.
        return ResponseHelper::jsonResponse([
            'message' => '실시간 검색 결과입니다.',
            'searchResult' => [
                'region' => $regionResults,
                'city' => $cityResults,
                'country' => [],
                'landmark' => [],
                'hub' => [],
                'stay' => $stayResults,
                'activity' => [],
                'tour' => [],
                'tag' => [],
            ],
        ], 200);
    }

    public static function searchLoadMore()
    {
        global $_EXCLUDED_PARTNER_STRING;

        $input = json_decode(file_get_contents("php://input"), true);

        $page = isset($input['page']) ? intval($input['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $searchTerm = $input['text'];
        $startDate = $input['startDate'];
        $endDate = $input['endDate'];
        $totalGuests = (empty($input['adult'])? 0 : $input['adult']) 
                    + (empty($input['child'])? 0 : $input['child'])
                    + (empty($input['infant'])? 0 : $input['infant']);

        $startPrice = $input['startPrice'];
        $endPrice = $input['endPrice'];
        $starRating = $input['starRating'];
        $tagNames = empty($input['tagName']) ? [] : $input['tagName'];;//$input['tagName'];

        $orderType = empty($input['orderType']) ? '' : $input['orderType'];
        $order = empty($input['order']) ? '' : $input['order'];

        if ($orderType == '') {
            $orderType = 'p.search_index';
        } else if ($orderType == 'price') {
            $orderType = 'price_data.lowest_price';
        } else {
            $orderType = 'p.search_index';
        }

        if ($order == '') {
            $order = 'DESC';
        }

        // 날짜 범위 설정
        $start = $startDate ? $startDate : null;
        $end = null;
        if (!empty($endDate)) {
            $endDateObj = new \DateTime($endDate);
            $endDateObj->modify('-1 day');
            $end = $endDateObj->format('Y-m-d');
        }

        $bindings = [];

        if(empty($startDate) && empty($endDate)) {
            $priceSql = "
                WITH price_data AS (
                    SELECT
                        r.partner_idx,
                        -- MAX(i.inventory_quantity) AS inventory_quantity,
                        MAX(rp.room_price_sale) AS max_price,
                        MIN(NULLIF(rp.room_price_sale, 0)) AS lowest_price,
                        SUBSTRING_INDEX(
                            GROUP_CONCAT(rp.room_price_basic ORDER BY rp.room_price_sale ASC), ',', 1
                        ) AS basic_price
                    FROM moongcletrip.rooms r
                    LEFT JOIN moongcletrip.room_rateplan rr ON r.room_idx = rr.room_idx
                    -- LEFT JOIN moongcletrip.room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                    -- LEFT JOIN moongcletrip.room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx AND i.inventory_date = rp.room_price_date
                    -- #room_inventories와 room_prices는 room_rateplan_idx를 기준으로 JOIN해야 더 정확
                    -- LEFT JOIN moongcletrip.room_inventories i ON rr.room_rateplan_idx = i.room_rateplan_idx
                    LEFT JOIN moongcletrip.room_prices rp ON rr.room_rateplan_idx = rp.room_rateplan_idx
                    WHERE rp.room_price_sale > 0
                        AND r.room_status = 'enabled'
                        AND rr.room_rateplan_status = 'enabled'
                        AND rp.is_closed = 0
            ";
        } else {
            $priceSql = "
                WITH price_data AS (
                    SELECT
                        r.partner_idx,
                        -- MAX(i.inventory_quantity) AS inventory_quantity,
                        MAX(rp.room_price_sale) AS max_price,
                        MIN(NULLIF(rp.room_price_sale, 0)) AS lowest_price,
                        SUBSTRING_INDEX(
                            GROUP_CONCAT(rp.room_price_basic ORDER BY rp.room_price_sale ASC), ',', 1
                        ) AS basic_price
                    FROM moongcletrip.rooms r
                    LEFT JOIN moongcletrip.room_rateplan rr ON r.room_idx = rr.room_idx
                    -- LEFT JOIN moongcletrip.room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                    -- LEFT JOIN moongcletrip.room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx AND i.inventory_date = rp.room_price_date
                    -- LEFT JOIN moongcletrip.room_inventories i ON rr.room_rateplan_idx = i.room_rateplan_idx
                    LEFT JOIN moongcletrip.room_prices rp ON rr.room_rateplan_idx = rp.room_rateplan_idx
                    WHERE rp.room_price_sale >= 0
                        AND r.room_status = 'enabled'
                        AND rr.room_rateplan_status = 'enabled'
                        AND rp.is_closed = 0
            ";
        }

        if (!empty($startPrice)) {
            $priceSql .= " AND rp.room_price_sale >= :startPrice";
            $bindings['startPrice'] = $startPrice;
        } else {
            $priceSql .= " AND rp.room_price_sale > 0";
        }

        if (!empty($endPrice)) {
            $priceSql .= " AND rp.room_price_sale <= :endPrice";
            $bindings['endPrice'] = $endPrice;
        }

        if (!empty($totalGuests)) {
            $priceSql .= " AND r.room_max_person >= :totalGuests";
            $bindings['totalGuests'] = $totalGuests;
        }

        if (!empty($start) && !empty($end)) {
            //$priceSql .= " AND i.inventory_date BETWEEN :startDate AND :endDate";
            $priceSql .= " AND rp.room_price_date BETWEEN :pstartDate3 AND :pendDate3 
                    AND (
                        -- 이 객실(rr.room_rateplan_idx)이 연속 숙박 가능한지 확인
                        SELECT COUNT(DISTINCT inner_i.inventory_date)
                        FROM moongcletrip.room_inventories inner_i
                        WHERE inner_i.room_rateplan_idx = rr.room_rateplan_idx
                        AND inner_i.inventory_date BETWEEN :pstartDate AND :pendDate
                        AND inner_i.inventory_quantity > 0
                    ) = DATEDIFF(:pendDate1, :pstartDate1) + 1
                    AND EXISTS (
                        -- '예약 가능한 방의 최저가'가 존재하는지 확인
                        SELECT 1 
                        FROM moongcletrip.room_prices r_price
                        WHERE 
                            r_price.room_rateplan_idx = rr.room_rateplan_idx
                            AND r_price.is_closed = 0 AND r_price.room_price_sale > 0
                            AND r_price.room_price_sale IS NOT NULL 
                            AND r_price.room_price_date BETWEEN :pstartDate2 AND :pendDate2
                    )";
            $priceSql .= "
                GROUP BY r.partner_idx
                -- HAVING COUNT(DISTINCT i.inventory_date) = DATEDIFF(:pendDate3, :pstartDate3) + 1
            ),
            ";

            $bindings['pstartDate3'] = $start;
            $bindings['pendDate3'] = $end;
            $bindings['pstartDate'] = $start;
            $bindings['pendDate'] = $end;
            $bindings['pstartDate1'] = $start;
            $bindings['pendDate1'] = $end;
            $bindings['pstartDate2'] = $start;
            $bindings['pendDate2'] = $end;

        } else {
            //$priceSql .= " AND i.inventory_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";
            $priceSql .=" AND rp.room_price_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    AND (
                       -- 이 객실(rr.room_rateplan_idx)에 숙박 가능한지 확인
                        SELECT COUNT(DISTINCT inner_i.inventory_date)
                        FROM moongcletrip.room_inventories inner_i 
                        wHERE inner_i.room_rateplan_idx = rr.room_rateplan_idx
                        AND inner_i.inventory_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                        AND inner_i.inventory_quantity > 0
                    )
                    AND EXISTS (
                        -- '예약 가능한 방의 최저가'가 존재하는지 확인
                        SELECT 1 
                        FROM moongcletrip.room_prices r_price
                        WHERE 
                            r_price.room_rateplan_idx = rr.room_rateplan_idx
                            AND r_price.is_closed = 0 AND r_price.room_price_sale > 0
                            AND r_price.room_price_sale IS NOT NULL 
                            AND r_price.room_price_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    )
            ";
            $priceSql .= "
                    GROUP BY r.partner_idx
                ),
            ";
        }

        $tagSql = "
            tag_data AS (
                SELECT
                    tc.item_idx AS partner_detail_idx,
                    GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS types,
                    GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS tags
                FROM moongcletrip.tag_connections tc
                LEFT JOIN moongcletrip.tag_connections tc1 ON tc.item_idx = tc1.item_idx
                    AND tc1.item_type = 'stay'
                    AND tc1.connection_type = 'stay_type_detail'
                WHERE tc.item_type = 'stay' AND tc.connection_type = 'stay_type'
                GROUP BY tc.item_idx
            ),
        ";

        $imageSql = "
            image_data AS (
                SELECT
                    img.image_entity_id AS partner_idx,
                    GROUP_CONCAT(image_normal_path ORDER BY image_order SEPARATOR ':-:') AS image_paths
                FROM moongcletrip.images img
                WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
                GROUP BY img.image_entity_id
            ),
            curated_image_data AS (
                SELECT
                    img.image_entity_id AS partner_idx,
                    GROUP_CONCAT(image_normal_path ORDER BY image_order SEPARATOR ':-:') AS curated_image_paths
                FROM moongcletrip.curated_images img
                WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
                GROUP BY img.image_entity_id
            )
        ";

        $mainSql = "
            SELECT
                p.partner_idx,
                p.partner_name,
                p.partner_address1,
                p.search_index,
                p.image_curated,
                p.partner_search_badge,
                price_data.lowest_price,
                price_data.basic_price,
                image_data.image_paths,
                curated_image_data.curated_image_paths,
                rs.average_rating,
                rs.review_count,
                tag_data.tags,
                tag_data.types
            FROM moongcletrip.partners p
            INNER JOIN price_data ON p.partner_idx = price_data.partner_idx
            LEFT JOIN tag_data ON p.partner_detail_idx = tag_data.partner_detail_idx
            LEFT JOIN image_data ON p.partner_detail_idx = image_data.partner_idx
            LEFT JOIN curated_image_data ON p.partner_detail_idx = curated_image_data.partner_idx
            LEFT JOIN moongcletrip.review_statistics rs ON p.partner_detail_idx = rs.entity_idx AND rs.entity_type = 'stay'
            WHERE p.partner_category = 'stay'
                AND p.partner_status = 'enabled'
                AND p.partner_idx NOT IN $_EXCLUDED_PARTNER_STRING
                AND price_data.lowest_price IS NOT NULL
        ";

        if (!empty($starRating)) {
            $mainSql .= " AND (EXISTS (
                    SELECT 1 
                    FROM moongcle_tags t_search
                    LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                    WHERE tc_search.item_idx = p.partner_detail_idx AND tc_search.item_type = 'stay'
                    AND t_search.tag_machine_name = :starRating1
                ) OR EXISTS (
                    SELECT 1 
                    FROM moongcle_tags t_search
                    LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                    WHERE tc_search.item_idx IN (
                        SELECT r.room_idx 
                        FROM moongcletrip.rooms r 
                        WHERE r.partner_idx = p.partner_idx
                    ) AND tc_search.item_type = 'room'
                    AND t_search.tag_machine_name = :starRating2
                ))
            ";
            $bindings['starRating1'] = $starRating;
            $bindings['starRating2'] = $starRating;
        }

        if (count($tagNames) > 0) {
            foreach ($tagNames as $key => $tagName) {
                $mainSql .= " AND (EXISTS (
                        SELECT 1 
                        FROM moongcle_tags t_search
                        LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                        WHERE tc_search.item_idx = p.partner_detail_idx AND tc_search.item_type = 'stay'
                        AND t_search.tag_machine_name = :searchTag1$key
                    ) OR EXISTS (
                        SELECT 1 
                        FROM moongcle_tags t_search
                        LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                        WHERE tc_search.item_idx IN (
                            SELECT r.room_idx 
                            FROM moongcletrip.rooms r 
                            WHERE r.partner_idx = p.partner_idx
                        ) AND tc_search.item_type = 'room'
                        AND t_search.tag_machine_name = :searchTag2$key
                    ))
                ";
                $bindings['searchTag1' . $key] = $tagName;
                $bindings['searchTag2' . $key] = $tagName;
            }
        }

        if (!empty($searchTerm)) {
            if ($input['categoryType'] === 'region') {
                $mainSql .= " AND REPLACE(p.partner_region, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            } else if ($input['categoryType'] === 'city') {
                $mainSql .= " AND REPLACE(p.partner_address1, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            } else if ($input['categoryType'] === 'stay') {
                $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            } else if ($input['categoryType'] === 'tag') {
                $mainSql .= " AND (EXISTS (
                        SELECT 1 
                        FROM moongcle_tags t_search
                        LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                        WHERE tc_search.item_idx = p.partner_detail_idx AND tc_search.item_type = 'stay'
                        AND t_search.tag_name = :searchTerm1
                    ) OR EXISTS (
                        SELECT 1 
                        FROM moongcle_tags t_search
                        LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                        WHERE tc_search.item_idx IN (
                            SELECT r.room_idx 
                            FROM moongcletrip.rooms r 
                            WHERE r.partner_idx = p.partner_idx
                        ) AND tc_search.item_type = 'room'
                        AND t_search.tag_name = :searchTerm2
                    ))
                ";
                $bindings['searchTerm1'] = $searchTerm;
                $bindings['searchTerm2'] = $searchTerm;
            } else if ($input['categoryType'] === 'text') {
                $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            }
        }

        $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
            ORDER BY  $orderType $order
            LIMIT $limit OFFSET $offset;
        ";

        $stays = Database::getInstance()->getConnection()->select($sql, $bindings);

        $stayMoongcleExist = [];

        foreach ($stays as $key => $value) {
            $count = StayMoongcleOffer::where('partner_idx', $value->partner_idx)
                ->where('stay_moongcleoffer_status', 'enabled')
                ->count();

            $stayMoongcleExist[$key]->moongcleoffer_count = $count;

            if ($count > 0) {
                $stayMoongcleoffer = StayMoongcleOffer::where('partner_idx', $value->partner_idx)
                    ->where('stay_moongcleoffer_status', 'enabled')
                    ->orderBy('sale_end_date', 'ASC')
                    ->get();

                $stayMoongcleExist[$key]->sale_end_date = $stayMoongcleoffer[0]->sale_end_date;

                $benefits = [];

                foreach ($stayMoongcleoffer as $s) {
                    if (!empty($s->benefits)) {
                        foreach ($s->benefits as $b) {
                            if (!in_array($b, $benefits)) {
                                $benefits[] = $b;
                            }
                        }
                    }
                }

                $stayMoongcleExist[$key]->benefits = $benefits;
            } else {
                $stayMoongcleExist[$key]->sale_end_date = null;
            }
        }

        return ResponseHelper::jsonResponse([
            'data' => $stays,
            'stayMoongcleExist' => $stayMoongcleExist,
            'success' => true,
        ], 200);
    }

    public static function searchLoadMap()
    {
        global $_EXCLUDED_PARTNER_STRING;

        $input = json_decode(file_get_contents("php://input"), true);

        $page = isset($input['page']) ? intval($input['page']) : 1;
        $limit = 30;
        $offset = ($page - 1) * $limit;

        $searchTerm = $input['text'];
        $startDate = $input['startDate'];
        $endDate = $input['endDate'];
        $totalGuests = $input['adult'] + $input['child'] + $input['infant'];

        $startPrice = $input['startPrice'];
        $endPrice = $input['endPrice'];
        $starRating = $input['starRating'];
        $tagNames = $input['tagName'];

        $sLatitude = $input['sLat'];
        $sLongitude = $input['sLng'];
        $nLatitude = $input['nLat'];
        $nLongitude = $input['nLng'];

        // 날짜 범위 설정
        $start = $startDate ? $startDate : null;
        $end = null;
        if (!empty($endDate)) {
            $endDateObj = new \DateTime($endDate);
            $endDateObj->modify('-1 day');
            $end = $endDateObj->format('Y-m-d');
        }

        $bindings = [];

        if(empty($startDate) && empty($endDate)) {
            $priceSql = "
                WITH price_data AS (
                    SELECT
                        r.partner_idx,
                        MAX(i.inventory_quantity) AS inventory_quantity,
                        MAX(rp.room_price_sale) AS max_price,
                        MIN(NULLIF(rp.room_price_sale, 0)) AS lowest_price,
                        SUBSTRING_INDEX(
                            GROUP_CONCAT(rp.room_price_basic ORDER BY rp.room_price_sale ASC), ',', 1
                        ) AS basic_price
                    FROM moongcletrip.rooms r
                    LEFT JOIN moongcletrip.room_rateplan rr ON r.room_idx = rr.room_idx
                    LEFT JOIN moongcletrip.room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                    LEFT JOIN moongcletrip.room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx AND i.inventory_date = rp.room_price_date
                    WHERE i.inventory_quantity > 0
                    AND rp.room_price_sale > 0
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND rp.is_closed = 0
            ";
        } else {
            $priceSql = "
                WITH price_data AS (
                    SELECT
                        r.partner_idx,
                        MAX(i.inventory_quantity) AS inventory_quantity,
                        MAX(rp.room_price_sale) AS max_price,
                        MIN(NULLIF(rp.room_price_sale, 0)) AS lowest_price,
                        SUBSTRING_INDEX(
                            GROUP_CONCAT(rp.room_price_basic ORDER BY rp.room_price_sale ASC), ',', 1
                        ) AS basic_price
                    FROM moongcletrip.rooms r
                    LEFT JOIN moongcletrip.room_rateplan rr ON r.room_idx = rr.room_idx
                    LEFT JOIN moongcletrip.room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                    LEFT JOIN moongcletrip.room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx AND i.inventory_date = rp.room_price_date
                    WHERE i.inventory_quantity >= 0
                    AND rp.room_price_sale >= 0
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND rp.is_closed = 0
            ";
        }

        if (!empty($startPrice)) {
            $priceSql .= " AND rp.room_price_sale >= :startPrice";
            $bindings['startPrice'] = $startPrice;
        } else {
            $priceSql .= " AND rp.room_price_sale > 0";
        }

        if (!empty($endPrice)) {
            $priceSql .= " AND rp.room_price_sale <= :endPrice";
            $bindings['endPrice'] = $endPrice;
        }

        if (!empty($totalGuests)) {
            $priceSql .= " AND r.room_max_person >= :totalGuests";
            $bindings['totalGuests'] = $totalGuests;
        }

        if (!empty($start) && !empty($end)) {
            $priceSql .= " AND i.inventory_date BETWEEN :startDate AND :endDate";
            $priceSql .= "
                    GROUP BY r.partner_idx
                    HAVING COUNT(DISTINCT rp.room_price_date) = DATEDIFF(:endDate1, :startDate1) + 1
                ),
            ";

            $bindings['startDate'] = $start;
            $bindings['endDate'] = $end;
            $bindings['startDate1'] = $start;
            $bindings['endDate1'] = $end;
        } else {
            $priceSql .= " AND i.inventory_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";
            $priceSql .= "
                    GROUP BY r.partner_idx
                ),
            ";
        }

        $tagSql = "
            tag_data AS (
                SELECT
                    tc.item_idx AS partner_detail_idx,
                    GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS types,
                    GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS tags
                FROM moongcletrip.tag_connections tc
                LEFT JOIN moongcletrip.tag_connections tc1 ON tc.item_idx = tc1.item_idx
                    AND tc1.item_type = 'stay'
                    AND tc1.connection_type = 'stay_type_detail'
                WHERE tc.item_type = 'stay' AND tc.connection_type = 'stay_type'
                GROUP BY tc.item_idx
            ),
        ";

        $imageSql = "
            image_data AS (
                SELECT
                    img.image_entity_id AS partner_idx,
                    GROUP_CONCAT(image_normal_path ORDER BY image_order SEPARATOR ':-:') AS image_paths
                FROM moongcletrip.images img
                WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
                GROUP BY img.image_entity_id
            ),
            curated_image_data AS (
                SELECT
                    img.image_entity_id AS partner_idx,
                    GROUP_CONCAT(image_normal_path ORDER BY image_order SEPARATOR ':-:') AS curated_image_paths
                FROM moongcletrip.curated_images img
                WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
                GROUP BY img.image_entity_id
            )
        ";

        $mainSql = "
            SELECT
                p.partner_idx,
                p.partner_name,
                p.partner_address1,
                p.partner_latitude,
                p.partner_longitude,
                p.search_index,
                p.image_curated,
                price_data.inventory_quantity,
                price_data.max_price,
                price_data.lowest_price,
                price_data.basic_price,
                image_data.image_paths,
                curated_image_data.curated_image_paths,
                rs.average_rating,
                rs.review_count,
                tag_data.tags,
                tag_data.types
            FROM moongcletrip.partners p
            LEFT JOIN price_data ON p.partner_idx = price_data.partner_idx
            LEFT JOIN tag_data ON p.partner_detail_idx = tag_data.partner_detail_idx
            LEFT JOIN image_data ON p.partner_detail_idx = image_data.partner_idx
            LEFT JOIN curated_image_data ON p.partner_detail_idx = curated_image_data.partner_idx
            LEFT JOIN moongcletrip.review_statistics rs ON p.partner_detail_idx = rs.entity_idx AND rs.entity_type = 'stay'
            WHERE p.partner_category = 'stay'
                AND p.partner_status = 'enabled'
                AND p.partner_idx NOT IN $_EXCLUDED_PARTNER_STRING
                AND price_data.lowest_price IS NOT NULL
        ";

        if (!empty($sLatitude) && !empty($sLongitude) && !empty($nLatitude) && !empty($nLongitude)) {
            $mainSql .= " AND partner_latitude BETWEEN :sLatitude AND :nLatitude
                        AND partner_longitude BETWEEN :sLongitude AND :nLongitude
            ";

            $bindings['sLatitude'] = $sLatitude;
            $bindings['sLongitude'] = $sLongitude;
            $bindings['nLatitude'] = $nLatitude;
            $bindings['nLongitude'] = $nLongitude;
        }

        if (!empty($starRating)) {
            $mainSql .= " AND (EXISTS (
                    SELECT 1 
                    FROM moongcle_tags t_search
                    LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                    WHERE tc_search.item_idx = p.partner_detail_idx AND tc_search.item_type = 'stay'
                    AND t_search.tag_machine_name = :starRating1
                ) OR EXISTS (
                    SELECT 1 
                    FROM moongcle_tags t_search
                    LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                    WHERE tc_search.item_idx IN (
                        SELECT r.room_idx 
                        FROM moongcletrip.rooms r 
                        WHERE r.partner_idx = p.partner_idx
                    ) AND tc_search.item_type = 'room'
                    AND t_search.tag_machine_name = :starRating2
                ))
            ";
            $bindings['starRating1'] = $starRating;
            $bindings['starRating2'] = $starRating;
        }

        if (count($tagNames) > 0) {
            foreach ($tagNames as $key => $tagName) {
                $mainSql .= " AND (EXISTS (
                        SELECT 1 
                        FROM moongcle_tags t_search
                        LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                        WHERE tc_search.item_idx = p.partner_detail_idx AND tc_search.item_type = 'stay'
                        AND t_search.tag_machine_name = :searchTag1$key
                    ) OR EXISTS (
                        SELECT 1 
                        FROM moongcle_tags t_search
                        LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                        WHERE tc_search.item_idx IN (
                            SELECT r.room_idx 
                            FROM moongcletrip.rooms r 
                            WHERE r.partner_idx = p.partner_idx
                        ) AND tc_search.item_type = 'room'
                        AND t_search.tag_machine_name = :searchTag2$key
                    ))
                ";
                $bindings['searchTag1' . $key] = $tagName;
                $bindings['searchTag2' . $key] = $tagName;
            }
        }

        if ($input['categoryType'] === 'region') {
            // if (!empty($searchTerm)) {
            //     $mainSql .= " AND REPLACE(p.partner_region, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
            //     $bindings['searchTerm'] = '%' . $searchTerm . '%';
            // }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY p.search_index DESC
                LIMIT $limit OFFSET $offset;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        } else if ($input['categoryType'] === 'city') {
            // if (!empty($searchTerm)) {
            //     $mainSql .= " AND REPLACE(p.partner_address1, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
            //     $bindings['searchTerm'] = '%' . $searchTerm . '%';
            // }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY p.search_index DESC
                LIMIT $limit OFFSET $offset;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        } else if ($input['categoryType'] === 'stay') {
            if (!empty($searchTerm)) {
                $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY p.search_index DESC
                LIMIT $limit OFFSET $offset;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        } else if ($input['categoryType'] === 'tag') {
            if (!empty($searchTerm)) {
                $mainSql .= " AND (EXISTS (
                        SELECT 1 
                        FROM moongcle_tags t_search
                        LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                        WHERE tc_search.item_idx = p.partner_detail_idx AND tc_search.item_type = 'stay'
                        AND t_search.tag_name = :searchTerm1
                    ) OR EXISTS (
                        SELECT 1 
                        FROM moongcle_tags t_search
                        LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
                        WHERE tc_search.item_idx IN (
                            SELECT r.room_idx 
                            FROM moongcletrip.rooms r 
                            WHERE r.partner_idx = p.partner_idx
                        ) AND tc_search.item_type = 'room'
                        AND t_search.tag_name = :searchTerm2
                    ))
                ";
                $bindings['searchTerm1'] = $searchTerm;
                $bindings['searchTerm2'] = $searchTerm;
            }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY p.search_index DESC
                LIMIT $limit OFFSET $offset;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        } else if ($input['categoryType'] === 'text') {
            if (!empty($searchTerm)) {
                $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY p.search_index DESC
                LIMIT $limit OFFSET $offset;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        } else {
            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY p.search_index DESC
                LIMIT $limit OFFSET $offset;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        }

        return ResponseHelper::jsonResponse([
            'data' => $stays,
            'success' => true,
        ], 200);
    }
}
