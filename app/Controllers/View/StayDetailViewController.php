<?php

namespace App\Controllers\View;

use App\Models\Partner;
use App\Models\Stay;
use App\Models\CancelRule;
use App\Models\PartnerFavorite;
use App\Models\Review;
use App\Models\PartnerFaq;

use App\Helpers\MiddleHelper;

use Database;
use RedisManager;

class StayDetailViewController
{
    public static function main($partnerIdx)
    {
        $partner = null;
        $moongclePoint = null;
        $rooms = [];
        $closedRooms = null;
        $mainTagList = [];

        $deviceType = getDeviceType();

        $partnerTmp = Partner::find($partnerIdx);

        if ($partnerTmp->partner_status != 'enabled') {
            header('Location: /404');
        }

        $user = MiddleHelper::checkLoginCookie();

        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        if (!empty($_GET['startDate'])) {
            $startDate = $_GET['startDate'];
            $currentDate = strtotime(date('Y-m-d'));
            $startDateTimestamp = strtotime($startDate);

            if ($startDateTimestamp < $currentDate) {
                $_GET['startDate'] = '';
            }
        }

        if (!empty($_GET['endDate'])) {
            $endDate = $_GET['endDate'];
            $currentDate = strtotime(date('Y-m-d'));
            $endDateTimestamp = strtotime($endDate);

            if ($endDateTimestamp < $currentDate) {
                $_GET['endDate'] = '';
            }
        }

        if ((empty($_GET['startDate']) || empty($_GET['endDate'])) && !isBot()) {
            $bindings = [];
            $totalGuests = 0;

            if (!empty($_GET['adult'])) {
                $totalGuests += $_GET['adult'];
            }
            if (!empty($_GET['child'])) {
                $totalGuests += $_GET['child'];
            }
            if (!empty($_GET['infant'])) {
                $totalGuests += $_GET['infant'];
            }

            $bindings['partnerIdx'] = $partnerIdx;
            $bindings['totalGuests'] = $totalGuests;

            $sql = "
                SELECT rp.room_price_date,
                    MIN(rp.room_price_sale) AS lowest_price
                FROM room_prices rp
                JOIN room_inventories ri ON rp.room_idx = ri.room_idx AND rp.rateplan_idx = ri.rateplan_idx AND rp.room_price_date = ri.inventory_date
                JOIN rooms r ON rp.room_idx = r.room_idx
                JOIN room_rateplan rr ON rp.rateplan_idx = rr.rateplan_idx AND r.room_idx = rr.room_idx
                WHERE rp.room_price_sale > 0
                    AND ri.inventory_quantity > 0
                    AND rp.is_closed = 0
                    AND rp.room_price_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND r.partner_idx = :partnerIdx
                    AND r.room_max_person >= :totalGuests
                GROUP BY rp.room_price_date
                ORDER BY lowest_price ASC
                LIMIT 1;
            ";

            $lowestPriceDay = Database::getInstance()->getConnection()->select($sql, $bindings);

            if (!empty($lowestPriceDay[0]->room_price_date)) {
                $startDate = $lowestPriceDay[0]->room_price_date;
                $date = new \DateTime($startDate);
            } else {
                $date = new \DateTime();
                $startDate = $date->format('Y-m-d');
            }

            // 하루 더하기
            $date->modify('+1 day');

            // 결과 출력
            $endDate = $date->format('Y-m-d');

            $currentUrl = $_SERVER['REQUEST_URI'];
            $currentDomain = explode('?', $_SERVER['REQUEST_URI']);

            $queryParams = $_GET;
            $queryParams['startDate'] = $startDate;
            $queryParams['endDate'] = $endDate;

            if ($totalGuests == 0) {
                $queryParams['adult'] = 2;
                $queryParams['child'] = 0;
                $queryParams['infant'] = 0;
            }

            $newQueryString = http_build_query($queryParams);
            $newUrl = $currentDomain[0] . '?' . $newQueryString;

            // parentGotoNewUrl($newUrl);
            header("Location: $newUrl");
            exit;
        } else {
            $totalGuests = 0;

            if (!empty($_GET['adult'])) {
                $totalGuests += $_GET['adult'];
            }
            if (!empty($_GET['child'])) {
                $totalGuests += $_GET['child'];
            }
            if (!empty($_GET['infant'])) {
                $totalGuests += $_GET['infant'];
            }

            if ($totalGuests == 0  && !isBot()) {
                $currentDomain = explode('?', $_SERVER['REQUEST_URI']);

                $queryParams = $_GET;

                if ($totalGuests == 0) {
                    $queryParams['adult'] = 2;
                    $queryParams['child'] = 0;
                    $queryParams['infant'] = 0;
                }

                $newQueryString = http_build_query($queryParams);
                $newUrl = $currentDomain[0] . '?' . $newQueryString;

                header("Location: $newUrl");
                exit;
            }
        }

        MiddleHelper::checkUserAction();

        $totalGuests = 0;

        if (!empty($_GET['adult'])) {
            $totalGuests += $_GET['adult'];
        }
        if (!empty($_GET['child'])) {
            $totalGuests += $_GET['child'];
        }
        if (!empty($_GET['infant'])) {
            $totalGuests += $_GET['infant'];
        }

        $bindings = [];
        $bindings['partnerIdx'] = $partnerIdx;

        $sql = "
            SELECT
                p.*,
                s.*,
                (
					SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
					FROM moongcletrip.images img
					WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
				) AS image_paths,
                (
					SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
					FROM moongcletrip.curated_images img
					WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay'
				) AS curated_image_paths,
                rs.average_rating,
                rs.review_count,
                GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
                GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types
            FROM partners p
            LEFT JOIN stays s ON p.partner_detail_idx = s.stay_idx
            LEFT JOIN review_statistics rs ON p.partner_detail_idx = rs.entity_idx AND rs.entity_type = 'stay'
            LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
            LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
            WHERE p.partner_category = 'stay'
                AND p.partner_idx = :partnerIdx
        ";

        $partner = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT
                tc.*
            FROM partners p
            LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay'
            WHERE p.partner_idx = :partnerIdx
        ";

        $mainTags = Database::getInstance()->getConnection()->select($sql, $bindings);

        foreach ($mainTags as $tags) {
            if (!isset($mainTagList[$tags->connection_type])) {
                $mainTagList[$tags->connection_type] = [];
            }

            $mainTagList[$tags->connection_type][] = $tags;
        }

        if ($partner[0]->partner_thirdparty == 'onda') {
            $sql = "
                SELECT 
                    p.partner_idx,
                    p.partner_name,
                    r.room_idx,
                    r.room_name,
                    rr.rateplan_idx,
                    rr.room_rateplan_idx,
                    rt.rateplan_name,
                    rt.rateplan_type,
                    rt.rateplan_sales_from,
                    rt.rateplan_sales_to,
                    SUM(rp.room_price_sale) AS total_sale_price,
                    SUM(rp.room_price_basic) AS total_basic_price,
                    rt.rateplan_stay_min,
                    rt.rateplan_stay_max,
                    rp.room_price_promotion_type,
                    r.room_standard_person,
                    r.room_max_person,
                    r.room_bed_type,
                    r.room_size,
                    MIN(i.inventory_quantity) AS min_inventory_quantity,
                    (
                        SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
                        FROM moongcletrip.images img
                        WHERE img.image_entity_id = r.room_idx AND img.image_entity_type = 'room'
                    ) AS image_paths,
                    (
                        SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
                        FROM moongcletrip.tag_connections t
                        WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
                    ) AS views
                FROM partners p
                    LEFT JOIN rooms r ON p.partner_idx = r.partner_idx
                    LEFT JOIN room_rateplan rr ON r.room_idx = rr.room_idx
                    LEFT JOIN rateplans rt ON rr.rateplan_idx = rt.rateplan_idx
                    LEFT JOIN room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx
                    LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND i.rateplan_idx = rr.rateplan_idx AND rp.room_price_date = i.inventory_date
                WHERE 
                    p.partner_category = 'stay'
                    AND p.partner_idx = :partnerIdx
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND i.inventory_quantity > 0
                    AND rp.room_price_sale > 0
                    AND rp.is_closed = 0
                    AND rp.room_price_date >= :startDate1
                    AND rp.room_price_date < :endDate1
                    AND r.room_max_person >= :totalGuests
                    AND (
                        rt.rateplan_cutoff_days IS NULL 
                        OR DATE_ADD(CURDATE(), INTERVAL rt.rateplan_cutoff_days DAY) <= :startDate3
                    )
                GROUP BY 
                    rr.room_rateplan_idx
                HAVING 
                    COUNT(DISTINCT rp.room_price_date) = DATEDIFF(:endDate2, :startDate2)
                ORDER BY 
                    r.room_order ASC,
                    r.room_idx ASC,
                    rp.room_price_sale ASC;
            ";
        } else {
            $sql = "
                SELECT 
                    p.partner_idx,
                    p.partner_name,
                    r.room_idx,
                    r.room_name,
                    rr.rateplan_idx,
                    rr.room_rateplan_idx,
                    rt.rateplan_name,
                    rt.rateplan_type,
                    rt.rateplan_sales_from,
                    rt.rateplan_sales_to,
                    SUM(rp.room_price_sale) AS total_sale_price,
                    SUM(rp.room_price_basic) AS total_basic_price,
                    rt.rateplan_stay_min,
                    rt.rateplan_stay_max,
                    rp.room_price_promotion_type,
                    r.room_standard_person,
                    r.room_max_person,
                    r.room_bed_type,
                    r.room_size,
                    MIN(i.inventory_quantity) AS min_inventory_quantity,
                    (
                        SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
                        FROM moongcletrip.images img
                        WHERE img.image_entity_id = r.room_idx AND img.image_entity_type = 'room'
                    ) AS image_paths,
                    (
                        SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
                        FROM moongcletrip.tag_connections t
                        WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
                    ) AS views
                FROM partners p
                    LEFT JOIN rooms r ON p.partner_idx = r.partner_idx
                    LEFT JOIN room_rateplan rr ON r.room_idx = rr.room_idx
                    LEFT JOIN rateplans rt ON rr.rateplan_idx = rt.rateplan_idx
                    LEFT JOIN room_prices rp ON r.room_idx = rp.room_idx AND rr.rateplan_idx = rp.rateplan_idx
                    LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND i.rateplan_idx = 0 AND rp.room_price_date = i.inventory_date
                WHERE 
                    p.partner_category = 'stay'
                    AND p.partner_idx = :partnerIdx
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND i.inventory_quantity > 0
                    AND rp.room_price_sale > 0
                    AND rp.is_closed = 0
                    AND rp.room_price_date >= :startDate1
                    AND rp.room_price_date < :endDate1
                    AND r.room_max_person >= :totalGuests
                    AND (
                        rt.rateplan_cutoff_days IS NULL 
                        OR DATE_ADD(CURDATE(), INTERVAL rt.rateplan_cutoff_days DAY) <= :startDate3
                    )
                GROUP BY 
                    rr.room_rateplan_idx
                HAVING 
                    COUNT(DISTINCT rp.room_price_date) = DATEDIFF(:endDate2, :startDate2)
                ORDER BY 
                    r.room_order ASC,
                    r.room_idx ASC,
                    rp.room_price_sale ASC;
            ";
        }

        // 바인딩할 파라미터 설정
        $bindings = [
            'partnerIdx' => $partnerIdx,
            'startDate1' => $_GET['startDate'],
            'endDate1' => $_GET['endDate'],
            'startDate2' => $_GET['startDate'],
            'startDate3' => $_GET['startDate'],
            'endDate2' => $_GET['endDate'],
            'totalGuests' => $totalGuests
        ];

        // 쿼리 실행
        $roomsQuery = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT 
                r.*,
                (
					SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
					FROM moongcletrip.images img
					WHERE img.image_entity_id = r.room_idx AND img.image_entity_type = 'room'
				) AS image_paths,
                (
                    SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
                    FROM moongcletrip.tag_connections t
                    WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
                ) AS views
            FROM rooms r
            WHERE r.partner_idx = :partnerIdx
                AND r.room_status = 'enabled'
            ORDER BY r.room_order ASC, r.room_idx ASC;
        ";

        $bindings = [
            'partnerIdx' => $partnerIdx
        ];

        // 쿼리 실행
        $allRooms = Database::getInstance()->getConnection()->select($sql, $bindings);

        $allRoomsData = [];
        foreach ($allRooms as $room) {
            $allRoomsData[$room->room_idx] = $room;
        }

        foreach ($roomsQuery as $room) {
            if (!isset($rooms[$room->room_idx])) {
                $rooms[$room->room_idx] = [
                    'room_idx' => $room->room_idx,
                    'room_name' => $room->room_name,
                    'room_standard_person' => $room->room_standard_person,
                    'room_max_person' => $room->room_max_person,
                    'room_bed_type' => $room->room_bed_type,
                    'room_size' => $room->room_size,
                    'min_inventory_quantity' => $room->min_inventory_quantity,
                    'image_paths' => $room->image_paths,
                    'views' => $room->views,
                    'rateplans' => []
                ];
            }

            if (!empty($room->rateplan_sales_from) && !empty($room->rateplan_sales_to)) {
                if (strtotime($room->rateplan_sales_from) > strtotime('now') || strtotime($room->rateplan_sales_to) < strtotime('now')) {
                    continue;
                }
            }

            $rooms[$room->room_idx]['rateplans'][] = [
                'rateplan_idx' => $room->rateplan_idx,
                'rateplan_name' => $room->rateplan_name,
                'room_rateplan_idx' => $room->room_rateplan_idx,
                'total_basic_price' => $room->total_basic_price,
                'total_sale_price' => $room->total_sale_price,
                'rateplan_stay_min' => $room->rateplan_stay_min,
                'rateplan_stay_max' => $room->rateplan_stay_max,
                'room_price_promotion_type' => $room->room_price_promotion_type,
                'rateplan_type' => $room->rateplan_type,
            ];
        }

        $closedRooms = array_diff_key($allRoomsData, $rooms);

        $cancelRules = CancelRule::where('partner_idx', $partnerIdx)
            ->orderBy('cancel_rules_percent', 'DESC')
            ->get();

        $startDateTime = new \DateTime($_GET['startDate']);
        $endDateTime = new \DateTime($_GET['endDate']);

        $interval = $startDateTime->diff($endDateTime);

        $favorite = null;
        if ($user && !$isGuest) {
            $favorite = PartnerFavorite::where('user_idx', $user->user_idx)
                ->where('partner_idx', $partner[0]->partner_idx)
                ->where('target', 'partner')
                ->first();
        }

        $redis = RedisManager::getInstance();

        // Redis 키 설정
        $redisKey = "stay:open_calendar:$partnerIdx";
        $cacheTTL = 3600; // 한 시간 (초 단위)

        // Redis에서 데이터 조회
        $cachedData = $redis->get($redisKey);

        if ($cachedData) {
            $openCalendar = json_decode($cachedData);
        } else {
            $sql = "
                SELECT 
                    ri.inventory_date,
                    CASE 
                        WHEN SUM(CASE WHEN ri.inventory_quantity > 0 AND rp.room_price_sale > 0 THEN 1 ELSE 0 END) > 0 THEN 'open'
                        ELSE 'closed'
                    END AS status,
                    MIN(CASE WHEN ri.inventory_quantity > 0 AND rp.room_price_sale > 0 THEN rp.room_price_sale ELSE NULL END) AS lowest_price
                FROM moongcletrip.room_rateplan rr
                LEFT JOIN moongcletrip.rooms r ON r.room_idx = rr.room_idx 
                LEFT JOIN moongcletrip.room_inventories ri 
                    ON rr.room_rateplan_idx = ri.room_rateplan_idx
                LEFT JOIN moongcletrip.room_prices rp 
                    ON rr.room_rateplan_idx = rp.room_rateplan_idx 
                    AND ri.rateplan_idx = rp.rateplan_idx 
                    AND ri.inventory_date = rp.room_price_date
                WHERE r.partner_idx = :partnerIdx
                    AND ri.inventory_date >= CURDATE()
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND rp.is_closed = 0
                GROUP BY ri.inventory_date
                ORDER BY ri.inventory_date;
            ";

            $bindings = [
                'partnerIdx' => $partnerIdx
            ];

            $openCalendar = Database::getInstance()->getConnection()->select($sql, $bindings);

            $redis->setex($redisKey, $cacheTTL, json_encode($openCalendar));
        }

        $sql = "
            SELECT 
                (SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT(
                            'tag_name', ct.tag_name,
                            'tag_machine_name', ct.tag_machine_name
                        ))
                FROM moongcletrip.curated_tags ct 
                WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer'
                ) AS curated_tags
            FROM moongcletrip.moongcleoffers mo
            WHERE mo.partner_idx = :partnerIdx;
        ";

        $bindings = [
            'partnerIdx' => $partnerIdx
        ];

        $curatedTagsQuery = Database::getInstance()->getConnection()->select($sql, $bindings);

        $curatedTags = null;
        $curatedTagsEncode = null;

        if (!empty($curatedTagsQuery[0]->curated_tags)) {
            $curatedTags = json_decode($curatedTagsQuery[0]->curated_tags, true);

            if (count($curatedTags) > 3) {
                $curatedTags = array_slice($curatedTags, 0, 3);
            }

            $curatedTagsEncode = base64_encode(json_encode($curatedTags));
        }

        $sql = "
            SELECT
                mp.*,
                (
                    SELECT CONCAT('[', GROUP_CONCAT(
                        JSON_OBJECT(
                            'image_origin_name', img.image_origin_name,
                            'image_path', img.image_big_path,
                            'image_origin_size', img.image_origin_size
                        ) ORDER BY img.image_order ASC SEPARATOR ','), ']')
                    FROM moongcletrip.moongcle_point_images img
                    WHERE img.moongcle_point_idx = mp.moongcle_point_idx
                ) AS images
            FROM moongcle_points mp
            WHERE mp.partner_idx = :partnerIdx
        ";

        $bindings = [
            'partnerIdx' => $partnerIdx
        ];

        $moongclePoint = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT 
                r.*,
                u.user_nickname,
                (SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('origin_path', ri.review_image_origin_path, 'path', ri.review_image_normal_path, 'extension', ri.review_image_extension, 'order', ri.review_image_order))
                FROM moongcletrip.review_images ri 
                WHERE ri.review_idx = r.review_idx) AS image_list,
                (SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('tag_name', rt.tag_name, 'tag_machine_name', rt.tag_machine_name, 'order', rt.tag_order))
                FROM moongcletrip.review_tags rt 
                WHERE rt.review_idx = r.review_idx) AS tag_list
            FROM moongcletrip.reviews r 
            LEFT JOIN moongcletrip.users u ON u.user_idx = r.user_idx
            WHERE r.partner_idx = :partnerIdx
            GROUP BY r.review_idx
            ORDER BY r.created_at DESC;
        ";

        $bindings = [
            'partnerIdx' => $partnerIdx
        ];

        $reviews = Database::getInstance()->getConnection()->select($sql, $bindings);

        $reviewRating = 0;
        $reviewCount = 0;

        if (!empty($reviews[0])) {
            foreach ($reviews as $review) {
                $reviewRating += $review->rating;
                $reviewCount++;
            }
        }

        if ($reviewCount !== 0) {
            $reviewRating = round($reviewRating / $reviewCount, 2);
        }

        $partnerFaq = PartnerFaq::where('partner_idx', $partnerIdx)
            ->orderBy('faq_order', 'ASC')
            ->get();

        $bindings['partnerIdx'] = $partnerIdx;

        $sql = "
                SELECT mp.moongcleoffer_price_date,
                    MIN(mp.moongcleoffer_price_sale) AS lowest_price
                FROM moongcletrip.partners p
                JOIN moongcletrip.moongcleoffers mo ON mo.partner_idx = p.partner_idx 
                JOIN moongcletrip.moongcleoffer_prices mp ON mo.moongcleoffer_idx = mp.moongcleoffer_idx
                JOIN moongcletrip.room_prices rp ON rp.room_rateplan_idx = mp.room_rateplan_idx AND rp.room_price_date = mp.moongcleoffer_price_date
                JOIN moongcletrip.room_inventories ri ON rp.room_idx = ri.room_idx AND rp.rateplan_idx = ri.rateplan_idx AND rp.room_price_date = ri.inventory_date
                JOIN moongcletrip.rooms r ON rp.room_idx = r.room_idx
                JOIN moongcletrip.room_rateplan rr ON rp.rateplan_idx = rr.rateplan_idx AND r.room_idx = rr.room_idx
                WHERE mp.moongcleoffer_price_sale > 0
                    AND ri.inventory_quantity > 0
                    AND mp.moongcleoffer_price_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND r.partner_idx = :partnerIdx
                GROUP BY rp.room_price_date
                ORDER BY lowest_price ASC
                LIMIT 1;
            ";

        $moongcleofferLowestPrice = null;
        $moongcleofferLP = Database::getInstance()->getConnection()->select($sql, $bindings);

        if (!empty($moongcleofferLP[0]->lowest_price)) {
            $moongcleofferLowestPrice = $moongcleofferLP[0]->lowest_price;
        }

        $sql = "
            SELECT
                fd.*,
                (
                    SELECT CONCAT('[', GROUP_CONCAT(
                        JSON_OBJECT(
                            'image_origin_name', img.image_origin_name,
                            'image_origin_path', img.image_origin_path,
                            'image_origin_size', img.image_origin_size
                        ) ORDER BY img.image_order ASC SEPARATOR ','), ']')
                    FROM moongcletrip.facility_images img
                    WHERE img.facility_detail_idx = fd.facility_detail_idx
                ) AS images
            FROM facility_detail fd
            WHERE fd.partner_idx = :partnerIdx
            ORDER BY fd.created_at ASC;
        ";

        $bindings = [
            'partnerIdx' => $partnerIdx
        ];

        $facilities = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT
                sd.*
            FROM service_detail sd
            WHERE sd.partner_idx = :partnerIdx
            ORDER BY sd.created_at ASC;
        ";

        $bindings = [
            'partnerIdx' => $partnerIdx
        ];

        $services = Database::getInstance()->getConnection()->select($sql, $bindings);

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'isGuest' => $isGuest,
            'partner' => $partner[0],
            'rooms' => $rooms,
            'allRooms' => $allRoomsData,
            'closedRooms' => $closedRooms,
            'moongclePoint' => !empty($moongclePoint) ? $moongclePoint[0] : null,
            'mainTagList' => $mainTagList,
            'cancelRules' => $cancelRules,
            'intervalDays' => $interval->days,
            'favorite' => $favorite,
            'openCalendar' => $openCalendar,
            'curatedTags' => $curatedTags,
            'curatedTagsEncode' => $curatedTagsEncode,
            'reviews' => $reviews,
            'reviewRating' => $reviewRating,
            'reviewCount' => $reviewCount,
            'partnerFaq' => $partnerFaq,
            'moongcleofferLowestPrice' => $moongcleofferLowestPrice,
            'facilities' => $facilities,
            'services' => $services,
        );

        self::render('stay-detail', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
