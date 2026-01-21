<?php

namespace App\Controllers\View;

use App\Models\Room;
use App\Models\RoomInventory;
use App\Models\Partner;
use App\Models\MoongcleOffer;
use App\Models\PartnerFavorite;
use App\Models\Notification;
use App\Models\StayMoongcleOffer;

use App\Helpers\MiddleHelper;

use App\Services\TagService;
use App\Services\SearchService;

use Carbon\Carbon;
use Database;
use stdClass;

// use RedisManager;

class SearchViewController
{
    public static function searchHome()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;
        $unreadMoocledealCount = 0;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }

            $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('is_read', false)
                ->where('notification_type', 'moongcledeal')
                ->distinct()
                ->count(['base_idx', 'target_idx']);
        }

        $data = array(
            'deviceType' => $deviceType,
            'unreadMoocledealCount' => $unreadMoocledealCount
        );

        self::render('search-home', ['data' => $data]);
    }

    public static function main()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;
        $unreadMoocledealCount = 0;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }

            $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('is_read', false)
                ->where('notification_type', 'moongcledeal')
                ->distinct()
                ->count(['base_idx', 'target_idx']);
        }

        $popularTerms = SearchService::getPopularTerms();
        $risingCities = SearchService::getRisingCities();

        $data = array(
            'deviceType' => $deviceType,
            'unreadMoocledealCount' => $unreadMoocledealCount,
            'popularTerms' => $popularTerms,
            'risingCities' => $risingCities,
        );

        self::render('search-start', ['data' => $data]);
    }

    public static function searchResult()
    {
        MiddleHelper::checkUserAction();
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;
        $unreadMoocledealCount = 0;

        global $_EXCLUDED_PARTNER_STRING;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }

            $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('is_read', false)
                ->where('notification_type', 'moongcledeal')
                ->distinct()
                ->count(['base_idx', 'target_idx']);
        }

        $stays = null;
        $activities = null;
        $tour = null;

        $deviceType = getDeviceType();

        // $redis = RedisManager::getInstance();

        $searchTerm = $_GET['text'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $startPrice = $_GET['startPrice'];
        $endPrice = $_GET['endPrice'];
        $starRating = $_GET['starRating'];
        $tagNames = empty($_GET['tagName']) ? [] : explode(',', urldecode($_GET['tagName']));
        $totalGuests = $_GET['adult'] + $_GET['child'] + $_GET['infant'];
        $orderType = empty($_GET['orderType']) ? '' : $_GET['orderType'];
        $order = empty($_GET['order']) ? '' : $_GET['order'];
        $limit = 10;

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
        $interval = 1;

        $bindings = [];

        $priceSql = "
            WITH price_data AS (
                SELECT
                    r.partner_idx,
                    MIN(rp.room_price_sale) AS lowest_price,
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
                JOIN moongcletrip.room_inventories i ON i.room_rateplan_idx = rr.room_rateplan_idx AND i.inventory_date    = rp.room_price_date AND i.inventory_quantity > 0
                WHERE r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    -- AND i.inventory_quantity > 0
                    AND rp.is_closed = 0
        ";

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
                    GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:') AS image_paths
                FROM moongcletrip.images img
                WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
                GROUP BY img.image_entity_id
            ),
            curated_image_data AS (
                SELECT
                    img.image_entity_id AS partner_idx,
                    GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:') AS curated_image_paths
                FROM moongcletrip.curated_images img
                WHERE img.image_entity_type = 'stay'
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
            if ($_GET['categoryType'] === 'region') {
                $mainSql .= " AND REPLACE(p.partner_region, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';

            } else if ($_GET['categoryType'] === 'city') {
                $mainSql .= " AND REPLACE(p.partner_address1, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';

            } else if ($_GET['categoryType'] === 'stay') {
                $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';

            } else if ($_GET['categoryType'] === 'text') {
                $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            }
        }
        $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
            ORDER BY $orderType $order
            LIMIT $limit;
        ";

        $stays = Database::getInstance()->getConnection()->select($sql, $bindings);

        $stayMoongcleExist = [];

        foreach ($stays as $key => $value) {
            $count = StayMoongcleOffer::where('partner_idx', $value->partner_idx)
                ->where('stay_moongcleoffer_status', 'enabled')
                ->count();

            $stayMoongcleExist[$key] = new \stdClass();
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

        if (!empty($start) && !empty($end)) {
            $startDateTmp = new \DateTime($start);
            $endDateTmp = new \DateTime($end);

            // 날짜 차이 계산
            $interval = $startDateTmp->diff($endDateTmp);
            $interval = $interval->days;
        }

        $partnerFavorites = [];
        if ($user && !$isGuest) {
            $partnerFavorites = PartnerFavorite::where('user_idx', $user->user_idx)
                ->where('target', 'partner')
                ->pluck('partner_idx')
                ->toArray();
        }

        $tagService = new TagService();
        $tags = $tagService->getSearchTags();

        $data = array(
            'user' => $user,
            'isGuest' => $isGuest,
            'partnerFavorites' => $partnerFavorites,
            'deviceType' => $deviceType,
            'stays' => $stays,
            'activities' => $activities,
            'tour' => $tour,
            'interval' => $interval,
            'tags' => $tags,
            'unreadMoocledealCount' => $unreadMoocledealCount,
            'stayMoongcleExist' => $stayMoongcleExist
        );

        self::render('search-result', ['data' => $data]);
    }

    public static function searchMap()
    {
        MiddleHelper::checkUserAction();
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;
        $unreadMoocledealCount = 0;

        global $_EXCLUDED_PARTNER_STRING;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }

            $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('is_read', false)
                ->where('notification_type', 'moongcledeal')
                ->distinct()
                ->count(['base_idx', 'target_idx']);
        }

        $stays = null;
        $activities = null;
        $tour = null;

        $deviceType = getDeviceType();

        // $redis = RedisManager::getInstance();

        $searchTerm = $_GET['text'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $startPrice = $_GET['startPrice'];
        $endPrice = $_GET['endPrice'];
        $starRating = $_GET['starRating'];
        $tagNames = empty($_GET['tagName']) ? [] : explode(',', urldecode($_GET['tagName']));
        $totalGuests = $_GET['adult'] + $_GET['child'] + $_GET['infant'];
        $orderType = empty($_GET['orderType']) ? '' : $_GET['orderType'];
        $order = empty($_GET['order']) ? '' : $_GET['order'];
        $limit = 30;

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
        $interval = 1;

        $bindings = [];

        $priceSql = "
            WITH price_data AS (
                SELECT
                    r.partner_idx,
                    MIN(rp.room_price_sale) AS lowest_price,
                    SUBSTRING_INDEX(
                        GROUP_CONCAT(rp.room_price_basic ORDER BY rp.room_price_sale ASC), ',', 1
                    ) AS basic_price
                FROM moongcletrip.rooms r
                LEFT JOIN moongcletrip.room_rateplan rr ON r.room_idx = rr.room_idx
                LEFT JOIN moongcletrip.room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                LEFT JOIN moongcletrip.room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx AND i.inventory_date = rp.room_price_date
                WHERE i.inventory_quantity > 0
                AND r.room_status = 'enabled'
                AND rr.room_rateplan_status = 'enabled'
                AND rp.is_closed = 0
        ";

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
                    GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:') AS image_paths
                FROM moongcletrip.images img
                WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
                GROUP BY img.image_entity_id
            ),
            curated_image_data AS (
                SELECT
                    img.image_entity_id AS partner_idx,
                    GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:') AS curated_image_paths
                FROM moongcletrip.curated_images img
                WHERE img.image_entity_type = 'stay'
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

        if ($_GET['categoryType'] === 'region') {
            if (!empty($searchTerm)) {
                $mainSql .= " AND REPLACE(p.partner_region, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY $orderType $order
                LIMIT $limit;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        } else if ($_GET['categoryType'] === 'city') {
            if (!empty($searchTerm)) {
                $mainSql .= " AND REPLACE(p.partner_address1, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY $orderType $order
                LIMIT $limit;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        } else if ($_GET['categoryType'] === 'stay') {
            if (!empty($searchTerm)) {
                $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY $orderType $order
                LIMIT $limit;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        } else if ($_GET['categoryType'] === 'text') {
            if (!empty($searchTerm)) {
                $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
                $bindings['searchTerm'] = '%' . $searchTerm . '%';
            }

            $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
                ORDER BY $orderType $order
                LIMIT $limit;
            ";

            $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
        }

        if (!empty($start) && !empty($end)) {
            $startDateTmp = new \DateTime($start);
            $endDateTmp = new \DateTime($end);

            // 날짜 차이 계산
            $interval = $startDateTmp->diff($endDateTmp);
            $interval = $interval->days;
        }

        $partnerFavorites = [];
        if ($user && !$isGuest) {
            $partnerFavorites = PartnerFavorite::where('user_idx', $user->user_idx)
                ->where('target', 'partner')
                ->pluck('partner_idx')
                ->toArray();
        }

        $tagService = new TagService();
        $tags = $tagService->getSearchTags();

        $data = array(
            'user' => $user,
            'isGuest' => $isGuest,
            'partnerFavorites' => $partnerFavorites,
            'deviceType' => $deviceType,
            'stays' => $stays,
            'activities' => $activities,
            'tour' => $tour,
            'interval' => $interval,
            'tags' => $tags,
            'unreadMoocledealCount' => $unreadMoocledealCount
        );

        self::render('search-map', ['data' => $data]);
    }

    // public static function test()
    // {
    //     $deviceType = getDeviceType();

    //     $user = MiddleHelper::checkLoginCookie();
    //     $isGuest = true;
    //     $unreadMoocledealCount = 0;

    //     if ($user) {
    //         if ($user->user_is_guest == false) {
    //             $isGuest = false;
    //         }

    //         $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
    //             ->where('is_read', false)
    //             ->where('notification_type', 'moongcledeal')
    //             ->distinct()
    //             ->count(['base_idx', 'target_idx']);
    //     }

    //     $data = array(
    //         'deviceType' => $deviceType,
    //         'unreadMoocledealCount' => $unreadMoocledealCount
    //     );

    //     self::render('search', ['data' => $data]);
    // }

    // public static function searchResultTest()
    // {
    //     MiddleHelper::checkUserAction();
    //     $user = MiddleHelper::checkLoginCookie();
    //     $isGuest = true;
    //     $unreadMoocledealCount = 0;

    //     global $_EXCLUDED_PARTNER_STRING;

    //     if ($user) {
    //         if ($user->user_is_guest == false) {
    //             $isGuest = false;
    //         }

    //         $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
    //             ->where('is_read', false)
    //             ->where('notification_type', 'moongcledeal')
    //             ->distinct()
    //             ->count(['base_idx', 'target_idx']);
    //     }

    //     $moongcleoffers = null;
    //     $stays = null;
    //     $activities = null;
    //     $tour = null;

    //     $deviceType = getDeviceType();

    //     // $redis = RedisManager::getInstance();

    //     $searchTerm = $_GET['text'];
    //     $startDate = $_GET['startDate'];
    //     $endDate = $_GET['endDate'];
    //     $totalGuests = $_GET['adult'] + $_GET['child'] + $_GET['infant'];
    //     $limit = 10;

    //     // 날짜 범위 설정
    //     $start = $startDate ? $startDate : null;
    //     $end = null;
    //     if (!empty($endDate)) {
    //         $endDateObj = new \DateTime($endDate);
    //         $endDateObj->modify('-1 day');
    //         $end = $endDateObj->format('Y-m-d');
    //     }
    //     $interval = 1;

    //     $bindings = [];

    //     $priceSql = "
    //         WITH price_data AS (
    //             SELECT
    //                 r.partner_idx,
    //                 MIN(rp.room_price_sale) AS lowest_price,
    //                 SUBSTRING_INDEX(
    //                     GROUP_CONCAT(rp.room_price_basic ORDER BY rp.room_price_sale ASC), ',', 1
    //                 ) AS basic_price
    //             FROM moongcletrip.rooms r
    //             LEFT JOIN moongcletrip.room_rateplan rr ON r.room_idx = rr.room_idx
    //             LEFT JOIN moongcletrip.room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
    //             LEFT JOIN moongcletrip.room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx AND i.inventory_date = rp.room_price_date
    //             WHERE i.inventory_quantity > 0
    //             AND rp.room_price_sale > 0
    //             AND r.room_status = 'enabled'
    //             AND rr.room_rateplan_status = 'enabled'
    //             AND rp.is_closed = 0
    //     ";

    //     if (!empty($totalGuests)) {
    //         $priceSql .= " AND r.room_max_person >= :totalGuests";
    //         $bindings['totalGuests'] = $totalGuests;
    //     }

    //     if (!empty($start) && !empty($end)) {
    //         $priceSql .= " AND i.inventory_date BETWEEN :startDate AND :endDate";
    //         $priceSql .= "
    //                 GROUP BY r.partner_idx
    //                 HAVING COUNT(DISTINCT rp.room_price_date) = DATEDIFF(:endDate1, :startDate1) + 1
    //             ),
    //         ";

    //         $bindings['startDate'] = $start;
    //         $bindings['endDate'] = $end;
    //         $bindings['startDate1'] = $start;
    //         $bindings['endDate1'] = $end;
    //     } else {
    //         $priceSql .= " AND i.inventory_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";
    //         $priceSql .= "
    //                 GROUP BY r.partner_idx
    //             ),
    //         ";
    //     }

    //     $tagSql = "
    //         tag_data AS (
    //             SELECT
    //                 tc.item_idx AS partner_detail_idx,
    //                 GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS types,
    //                 GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS tags
    //             FROM moongcletrip.tag_connections tc
    //             LEFT JOIN moongcletrip.tag_connections tc1 ON tc.item_idx = tc1.item_idx
    //                 AND tc1.item_type = 'stay'
    //                 AND tc1.connection_type = 'stay_type_detail'
    //             WHERE tc.item_type = 'stay' AND tc.connection_type = 'stay_type'
    //             GROUP BY tc.item_idx
    //         ),
    //     ";

    //     $imageSql = "
    //         image_data AS (
    //             SELECT
    //                 img.image_entity_id AS partner_idx,
    //                 GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:') AS image_paths
    //             FROM moongcletrip.images img
    //             WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
    //             GROUP BY img.image_entity_id
    //         ),
    //         curated_image_data AS (
    //             SELECT
    //                 img.image_entity_id AS partner_idx,
    //                 GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:') AS curated_image_paths
    //             FROM moongcletrip.curated_images img
    //             WHERE img.image_entity_type = 'stay'
    //             GROUP BY img.image_entity_id
    //         )
    //     ";

    //     $mainSql = "
    //         SELECT
    //             p.partner_idx,
    //             p.partner_name,
    //             p.partner_address1,
    //             p.search_index,
    //             p.image_curated,
    //             price_data.lowest_price,
    //             price_data.basic_price,
    //             image_data.image_paths,
    //             curated_image_data.curated_image_paths,
    //             rs.average_rating,
    //             rs.review_count,
    //             tag_data.tags,
    //             tag_data.types
    //         FROM moongcletrip.partners p
    //         LEFT JOIN price_data ON p.partner_idx = price_data.partner_idx
    //         LEFT JOIN tag_data ON p.partner_detail_idx = tag_data.partner_detail_idx
    //         LEFT JOIN image_data ON p.partner_detail_idx = image_data.partner_idx
    //         LEFT JOIN curated_image_data ON p.partner_detail_idx = curated_image_data.partner_idx
    //         LEFT JOIN moongcletrip.review_statistics rs ON p.partner_detail_idx = rs.entity_idx AND rs.entity_type = 'stay'
    //         WHERE p.partner_category = 'stay'
    //         AND p.partner_status = 'enabled'
    //         AND p.partner_idx NOT IN $_EXCLUDED_PARTNER_STRING
    //         AND price_data.lowest_price IS NOT NULL
    //     ";

    //     if ($_GET['categoryType'] === 'region') {
    //         if (!empty($searchTerm)) {
    //             $mainSql .= " AND REPLACE(p.partner_region, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
    //             $bindings['searchTerm'] = '%' . $searchTerm . '%';
    //         }

    //         $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
    //             ORDER BY p.search_index DESC
    //             LIMIT $limit;
    //         ";

    //         $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
    //     } else if ($_GET['categoryType'] === 'city') {
    //         if (!empty($searchTerm)) {
    //             $mainSql .= " AND REPLACE(p.partner_address1, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
    //             $bindings['searchTerm'] = '%' . $searchTerm . '%';
    //         }

    //         $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
    //             ORDER BY p.search_index DESC
    //             LIMIT $limit;
    //         ";

    //         $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
    //     } else if ($_GET['categoryType'] === 'stay') {
    //         if (!empty($searchTerm)) {
    //             $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
    //             $bindings['searchTerm'] = '%' . $searchTerm . '%';
    //         }

    //         $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
    //             ORDER BY p.search_index DESC
    //             LIMIT $limit;
    //         ";

    //         $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
    //     } else if ($_GET['categoryType'] === 'tag') {
    //         if (!empty($searchTerm)) {
    //             $mainSql .= " AND (EXISTS (
    //                     SELECT 1 
    //                     FROM moongcle_tags t_search
    //                     LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
    //                     WHERE tc_search.item_idx = p.partner_detail_idx AND tc_search.item_type = 'stay'
    //                     AND t_search.tag_name = :searchTerm1
    //                 ) OR EXISTS (
    //                     SELECT 1 
    //                     FROM moongcle_tags t_search
    //                     LEFT JOIN moongcle_tag_connections tc_search ON tc_search.tag_idx = t_search.tag_idx
    //                     WHERE tc_search.item_idx IN (
    //                         SELECT r.room_idx 
    //                         FROM moongcletrip.rooms r 
    //                         WHERE r.partner_idx = p.partner_idx
    //                     ) AND tc_search.item_type = 'room'
    //                     AND t_search.tag_name = :searchTerm2
    //                 ))
    //             ";
    //             $bindings['searchTerm1'] = $searchTerm;
    //             $bindings['searchTerm2'] = $searchTerm;
    //         }

    //         $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
    //             ORDER BY p.search_index DESC
    //             LIMIT $limit;
    //         ";

    //         $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
    //     } else if ($_GET['categoryType'] === 'text') {
    //         if (!empty($searchTerm)) {
    //             $mainSql .= " AND REPLACE(p.partner_name, ' ', '') LIKE REPLACE(:searchTerm, ' ', '')";
    //             $bindings['searchTerm'] = '%' . $searchTerm . '%';
    //         }

    //         $sql = $priceSql . $tagSql . $imageSql . $mainSql . "
    //             ORDER BY p.search_index DESC
    //             LIMIT $limit;
    //         ";

    //         $stays = Database::getInstance()->getConnection()->select($sql, $bindings);
    //     }

    //     if (!empty($start) && !empty($end)) {
    //         $startDateTmp = new \DateTime($start);
    //         $endDateTmp = new \DateTime($end);

    //         // 날짜 차이 계산
    //         $interval = $startDateTmp->diff($endDateTmp);
    //         $interval = $interval->days;
    //     }

    //     $sql = "
    //         WITH ranked_offers AS (
    //             SELECT
    //                 mo.*,
    //                 ROW_NUMBER() OVER (PARTITION BY mo.partner_idx ORDER BY mo.moongcleoffer_attractive DESC) AS rank
    //             FROM moongcletrip.moongcleoffers mo
    //             WHERE mo.moongcleoffer_status = 'enabled'
    //             AND mo.moongcleoffer_category = 'roomRateplan'
    //             AND mo.moongcleoffer_attractive > 90
    //         ),
    //         price_data AS (
    //             SELECT 
    //                 moongcleoffer_idx,
    //                 MIN(mp.moongcleoffer_price_sale) AS lowest_price,
    //                 SUBSTRING_INDEX(
    //                     GROUP_CONCAT(mp.moongcleoffer_price_basic ORDER BY mp.moongcleoffer_price_sale ASC), ',', 1
    //                 ) AS basic_price,
    //                 r.room_status,
    //                 i.inventory_quantity,
    //                 rr.room_rateplan_status
    //             FROM moongcletrip.moongcleoffer_prices mp
    //             JOIN moongcletrip.room_prices rp ON mp.base_idx = rp.room_price_idx
    //             JOIN moongcletrip.room_rateplan rr ON rp.room_rateplan_idx = rr.room_rateplan_idx
    //             JOIN moongcletrip.rooms r ON rr.room_idx = r.room_idx
    //             JOIN moongcletrip.room_inventories i ON rr.rateplan_idx = i.rateplan_idx AND mp.moongcleoffer_price_date = i.inventory_date
    //             WHERE mp.moongcleoffer_price_sale > 0
    //             AND mp.moongcleoffer_price_date >= CURDATE()
    //             AND r.room_status = 'enabled'
    //             AND i.inventory_quantity > 0
    //             GROUP BY mp.moongcleoffer_idx
    //         ),
    //         tag_data AS (
    //             SELECT 
    //                 tc.item_idx AS partner_detail_idx,
    //                 GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS types,
    //                 GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS tags
    //             FROM moongcletrip.tag_connections tc
    //             LEFT JOIN moongcletrip.tag_connections tc1 ON tc.item_idx = tc1.item_idx
    //                 AND tc1.item_type = 'stay'
    //                 AND tc1.connection_type = 'stay_type_detail'
    //             WHERE tc.item_type = 'stay' AND tc.connection_type = 'stay_type'
    //             GROUP BY tc.item_idx
    //         ),
    //         image_data AS (
    //             SELECT 
    //                 img.image_entity_id AS stay_id,
    //                 img.image_normal_path
    //             FROM moongcletrip.images img
    //             WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
    //             AND img.image_order = (
    //                 SELECT MIN(img_sub.image_order)
    //                 FROM moongcletrip.images img_sub
    //                 WHERE img_sub.image_entity_id = img.image_entity_id
    //                     AND img_sub.image_entity_type = 'stay' AND img.image_type = 'basic'
    //             )
    //         )
    //         SELECT
    //             r_offers.*,
    //             p.partner_idx,
    //             p.partner_name,
    //             p.partner_address1,
    //             pd.lowest_price,
    //             pd.basic_price,
    //             td.tags,
    //             td.types,
    //             id.image_normal_path,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
    //             FROM moongcletrip.curated_tags ct 
    //             WHERE ct.item_idx = r_offers.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //             FROM moongcletrip.benefit_item bi
    //             WHERE bi.item_idx = r_offers.base_product_idx AND bi.item_type = 'room'
    //             ) AS room_benefits,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //             FROM moongcletrip.benefit_item bi
    //             WHERE bi.item_idx = r_offers.base_product_idx AND bi.item_type = 'rateplan'
    //             ) AS rateplan_benefits,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //             FROM moongcletrip.benefit_item bi
    //             WHERE bi.item_idx = r_offers.moongcleoffer_idx AND bi.item_type = 'moongcleoffer'
    //             ) AS moongcleoffer_benefits
    //         FROM ranked_offers r_offers
    //         LEFT JOIN moongcletrip.partners p ON r_offers.partner_idx = p.partner_idx
    //         LEFT JOIN price_data pd ON r_offers.moongcleoffer_idx = pd.moongcleoffer_idx
    //         LEFT JOIN tag_data td ON p.partner_detail_idx = td.partner_detail_idx
    //         LEFT JOIN image_data id ON p.partner_detail_idx = id.stay_id
    //         WHERE r_offers.rank = 1
    //         AND pd.lowest_price IS NOT NULL
    //         AND pd.room_status = 'enabled'
    //         AND pd.inventory_quantity > 0
    //         AND pd.room_rateplan_status = 'enabled'
    //         AND p.partner_status = 'enabled'
    //         ORDER BY r_offers.moongcleoffer_attractive DESC
    //         LIMIT 12;
    //     ";

    //     $moongcleoffers = Database::getInstance()->getConnection()->select($sql);

    //     $partnerFavorites = [];
    //     if ($user && !$isGuest) {
    //         $partnerFavorites = PartnerFavorite::where('user_idx', $user->user_idx)
    //             ->where('target', 'partner')
    //             ->pluck('partner_idx')
    //             ->toArray();
    //     }

    //     $data = array(
    //         'user' => $user,
    //         'isGuest' => $isGuest,
    //         'partnerFavorites' => $partnerFavorites,
    //         'deviceType' => $deviceType,
    //         'moongcleoffers' => $moongcleoffers,
    //         'stays' => $stays,
    //         'activities' => $activities,
    //         'tour' => $tour,
    //         'interval' => $interval,
    //         'unreadMoocledealCount' => $unreadMoocledealCount
    //     );

    //     self::render('search-result-old', ['data' => $data]);
    // }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
