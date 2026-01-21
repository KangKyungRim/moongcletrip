<?php

namespace App\Controllers\View;

use App\Models\Partner;
use App\Models\Stay;
use App\Models\Room;
use App\Models\CancelRule;
use App\Models\StayMoongcleOffer;
use App\Models\MoongcleOffer;
use App\Models\PartnerFavorite;
use App\Models\PartnerFaq;

use App\Helpers\MiddleHelper;
use App\Models\MoongcleTag;
use Database;
use RedisManager;
use Carbon\Carbon;

class MoongcleofferDetailViewController
{
    /**
     * 뭉클딜 상세
     */
    public static function product($partnerIdx)
    {
        $partner = null;
        $moongclePoint = null;
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
                SELECT mp.moongcleoffer_price_date,
                    MIN(mp.moongcleoffer_price_sale) AS lowest_price
                FROM moongcleoffer_prices mp
                JOIN room_inventories ri ON mp.room_idx = ri.room_idx AND mp.rateplan_idx = ri.rateplan_idx AND mp.moongcleoffer_price_date = ri.inventory_date
                JOIN rooms r ON mp.room_idx = r.room_idx
                JOIN room_rateplan rr ON mp.rateplan_idx = rr.rateplan_idx AND r.room_idx = rr.room_idx
                JOIN moongcleoffers m ON mp.moongcleoffer_idx = m.moongcleoffer_idx
                WHERE mp.moongcleoffer_price_sale > 0
                    AND ri.inventory_quantity > 0
                    AND mp.is_closed = 0
                    AND mp.moongcleoffer_price_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND r.partner_idx = :partnerIdx
                    AND r.room_max_person >= :totalGuests
                    AND m.moongcleoffer_status = 'enabled'
                GROUP BY mp.moongcleoffer_price_date
                ORDER BY lowest_price ASC
                LIMIT 1;
            ";

            $lowestPriceDay = Database::getInstance()->getConnection()->select($sql, $bindings);

            if (!empty($lowestPriceDay[0]->moongcleoffer_price_date)) {
                $startDate = $lowestPriceDay[0]->moongcleoffer_price_date;
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

        $stayMoongcleoffers = [];
        $now = Carbon::now();

        $stayMoongcleoffersQuery = StayMoongcleOffer::where('partner_idx', $partnerIdx)
            ->where(function ($query) use ($now) {
                $query->whereNull('sale_start_date')
                    ->orWhere('sale_start_date', '<', $now);
            })
            ->where('stay_moongcleoffer_status', 'enabled')
            ->get();

        if (!$stayMoongcleoffersQuery->isEmpty()) {
            foreach ($stayMoongcleoffersQuery as $stayMoongcleoffer) {
                if ($stayMoongcleoffer->stay_moongcleoffer_status != 'enabled') {
                    continue;
                }

                $stayMoongcleoffer->tags_list = MoongcleTag::whereIn('tag_machine_name', $stayMoongcleoffer->tags ?? [])->get();
                $stayMoongcleoffer->curated_tags_list = MoongcleTag::whereIn('tag_machine_name', $stayMoongcleoffer->curated_tags ?? [])->get();

                $sql = "SELECT
                            mo.*,
                            r.room_name,
                            r.room_standard_person,
                            r.room_max_person,
                            r.room_bed_type,
                            r.room_size,
                            (
                                SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
                                FROM moongcletrip.images img
                                WHERE img.image_entity_id = mo.room_idx AND img.image_entity_type = 'room'
                            ) AS image_paths,
                            (
                                SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
                                FROM moongcletrip.tag_connections t
                                WHERE t.item_idx = mo.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
                            ) AS views,
                            rt.rateplan_name,
                            rt.rateplan_type,
                            rt.rateplan_sales_from,
                            rt.rateplan_sales_to,
                            IF(COUNT(DISTINCT mp.moongcleoffer_price_date) = 1, MAX(mp.moongcleoffer_price_sale), SUM(mp.moongcleoffer_price_sale)) AS total_sale_price,
                            IF(COUNT(DISTINCT mp.moongcleoffer_price_date) = 1, MAX(mp.moongcleoffer_price_basic), SUM(mp.moongcleoffer_price_basic)) AS total_basic_price,
                            rt.rateplan_stay_min,
                            rt.rateplan_stay_max,
                            MIN(i.inventory_quantity) AS min_inventory_quantity
                        FROM moongcletrip.moongcleoffers mo
                        LEFT JOIN moongcletrip.rooms r ON mo.room_idx = r.room_idx
                        LEFT JOIN moongcletrip.room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
                        LEFT JOIN moongcletrip.rateplans rt ON mo.rateplan_idx = rt.rateplan_idx
                        LEFT JOIN moongcletrip.moongcleoffer_prices mp ON mo.room_idx = mp.room_idx AND mo.rateplan_idx = mp.rateplan_idx AND mo.moongcleoffer_idx = mp.moongcleoffer_idx 
                        LEFT JOIN moongcletrip.room_prices rp ON r.room_idx = rp.room_idx AND rp.rateplan_idx = rr.rateplan_idx AND rp.room_price_date = mp.moongcleoffer_price_date
                        LEFT JOIN moongcletrip.room_inventories i ON mo.room_idx = i.room_idx AND 0 = i.rateplan_idx AND mp.moongcleoffer_price_date = i.inventory_date
                        WHERE
                            mo.stay_moongcleoffer_idx = :stayMoongcleofferIdx
                            AND mo.moongcleoffer_status = 'enabled'
                            AND r.room_status = 'enabled'
                            AND rr.room_rateplan_status = 'enabled'
                            AND i.inventory_quantity > 0
                            AND rp.is_closed = 0
                            AND mp.moongcleoffer_price_sale > 0
                            AND mp.is_closed = 0
                            AND mp.moongcleoffer_price_date >= :startDate1
                            AND mp.moongcleoffer_price_date < :endDate1
                            AND r.room_max_person >= :totalGuests
                            AND (
                                rt.rateplan_cutoff_days IS NULL 
                                OR DATE_ADD(CURDATE(), INTERVAL rt.rateplan_cutoff_days DAY) <= :startDate3
                            )
                            AND (rt.rateplan_sales_from IS NULL OR rt.rateplan_sales_from <= CURDATE())
                            AND (rt.rateplan_sales_to IS NULL OR rt.rateplan_sales_to >= CURDATE())
                        GROUP BY 
                            mo.base_product_idx
                        HAVING 
                            COUNT(DISTINCT mp.moongcleoffer_price_date) = DATEDIFF(:endDate2, :startDate2)
                        ORDER BY 
                            r.room_order ASC,
                            r.room_idx ASC,
                            mp.moongcleoffer_price_sale ASC
                    ";

                $bindings = [
                    'stayMoongcleofferIdx' => $stayMoongcleoffer->stay_moongcleoffer_idx,
                    'startDate1' => $_GET['startDate'],
                    'endDate1' => $_GET['endDate'],
                    'startDate2' => $_GET['startDate'],
                    'startDate3' => $_GET['startDate'],
                    'endDate2' => $_GET['endDate'],
                    'totalGuests' => $totalGuests
                ];

                $moongcleoffersQuery = Database::getInstance()->getConnection()->select($sql, $bindings);

                $stayMoongcleoffer->moongcleoffers = $moongcleoffersQuery;

                $stayMoongcleoffers[] = $stayMoongcleoffer;
            }
        } else {
            if ($partnerTmp->partner_thirdparty == 'onda') {
                $sql = "SELECT
                            mo.*,
                            r.room_name,
                            r.room_standard_person,
                            r.room_max_person,
                            r.room_bed_type,
                            r.room_size,
                            (
                                SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
                                FROM moongcletrip.images img
                                WHERE img.image_entity_id = mo.room_idx AND img.image_entity_type = 'room'
                            ) AS image_paths,
                            (
                                SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
                                FROM moongcletrip.tag_connections t
                                WHERE t.item_idx = mo.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
                            ) AS views,
                            rt.rateplan_name,
                            rt.rateplan_type,
                            rt.rateplan_sales_from,
                            rt.rateplan_sales_to,
                            IF(COUNT(DISTINCT mp.moongcleoffer_price_date) = 1, MAX(mp.moongcleoffer_price_sale), SUM(mp.moongcleoffer_price_sale)) AS total_sale_price,
                            IF(COUNT(DISTINCT mp.moongcleoffer_price_date) = 1, MAX(mp.moongcleoffer_price_basic), SUM(mp.moongcleoffer_price_basic)) AS total_basic_price,
                            rt.rateplan_stay_min,
                            rt.rateplan_stay_max,
                            MIN(i.inventory_quantity) AS min_inventory_quantity,
                            (SELECT 
                                JSON_ARRAYAGG(bi.benefit_name)
                            FROM moongcletrip.benefit_item bi 
                            WHERE bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan') AS benefits,
                            (SELECT 
                                JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
                            FROM curated_tags ct 
                            WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags
                        FROM moongcletrip.moongcleoffers mo
                        LEFT JOIN moongcletrip.rooms r ON mo.room_idx = r.room_idx
                        LEFT JOIN moongcletrip.room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
                        LEFT JOIN moongcletrip.rateplans rt ON mo.rateplan_idx = rt.rateplan_idx
                        LEFT JOIN moongcletrip.moongcleoffer_prices mp ON mo.room_idx = mp.room_idx AND mo.rateplan_idx = mp.rateplan_idx AND mo.moongcleoffer_idx = mp.moongcleoffer_idx 
                        LEFT JOIN moongcletrip.room_inventories i ON mo.room_idx = i.room_idx AND mo.rateplan_idx = i.rateplan_idx AND mp.moongcleoffer_price_date = i.inventory_date
                        WHERE
                            mo.partner_idx = :partnerIdx
                            AND mo.moongcleoffer_status = 'enabled'
                            AND r.room_status = 'enabled'
                            AND rr.room_rateplan_status = 'enabled'
                            AND i.inventory_quantity > 0
                            AND mp.moongcleoffer_price_sale > 0
                            AND mp.is_closed = 0
                            AND mp.moongcleoffer_price_date >= :startDate1
                            AND mp.moongcleoffer_price_date < :endDate1
                            AND r.room_max_person >= :totalGuests
                            AND (
                                rt.rateplan_cutoff_days IS NULL 
                                OR DATE_ADD(CURDATE(), INTERVAL rt.rateplan_cutoff_days DAY) <= :startDate3
                            )
                            AND (rt.rateplan_sales_from IS NULL OR rt.rateplan_sales_from <= CURDATE())
                            AND (rt.rateplan_sales_to IS NULL OR rt.rateplan_sales_to >= CURDATE())
                        GROUP BY 
                            mo.base_product_idx
                        HAVING 
                            COUNT(DISTINCT mp.moongcleoffer_price_date) = DATEDIFF(:endDate2, :startDate2)
                        ORDER BY 
                            r.room_order ASC,
                            r.room_idx ASC,
                            mp.moongcleoffer_price_sale ASC
                    ";

                $bindings = [
                    'partnerIdx' => $partnerIdx,
                    'startDate1' => $_GET['startDate'],
                    'endDate1' => $_GET['endDate'],
                    'startDate2' => $_GET['startDate'],
                    'startDate3' => $_GET['startDate'],
                    'endDate2' => $_GET['endDate'],
                    'totalGuests' => $totalGuests
                ];

                $moongcleoffersQuery = Database::getInstance()->getConnection()->select($sql, $bindings);

                $stayMoongcleoffers = [];
                $moongcleofferRateplan = [];

                foreach ($moongcleoffersQuery as $moongcleoffer) {
                    if (!array_key_exists($moongcleoffer->rateplan_name, $moongcleofferRateplan)) {
                        $moongcleofferRateplan[$moongcleoffer->rateplan_name] = [];
                    }

                    $moongcleofferRateplan[$moongcleoffer->rateplan_name][] = $moongcleoffer;
                }

                $key = 0;
                foreach ($moongcleofferRateplan as $k => $moongcleoffers) {
                    $stayMoongcleoffers[$key] = new \stdClass();
                    $stayMoongcleoffers[$key]->moongcleoffers = $moongcleoffers;

                    $stayMoongcleoffers[$key]->stay_moongcleoffer_idx = $key;
                    $stayMoongcleoffers[$key]->rateplan_idx = $key;
                    $stayMoongcleoffers[$key]->stay_moongcleoffer_title = $k;
                    $stayMoongcleoffers[$key]->sale_start_date = null;
                    $stayMoongcleoffers[$key]->sale_end_date = null;
                    $stayMoongcleoffers[$key]->stay_start_date = null;
                    $stayMoongcleoffers[$key]->stay_end_date = null;
                    $stayMoongcleoffers[$key]->blackout_dates = [];
                    $stayMoongcleoffers[$key]->rooms = null;
                    $stayMoongcleoffers[$key]->tags = null;
                    $stayMoongcleoffers[$key]->curated_tags = null;
                    if (!empty($moongcleoffers[0]->minimum_discount)) {
                        $stayMoongcleoffers[$key]->discount = $moongcleoffers[0]->minimum_discount;
                    }

                    $stayMoongcleoffers[$key]->benefits = json_decode($moongcleoffers[0]->benefits);
                    $stayMoongcleoffers[$key]->curated_tags = json_decode($moongcleoffers[0]->curated_tags);
                    // $stayMoongcleoffers[$key]->benefits_list = json_decode($stayMoongcleoffers[$key]->benefits);
                    $stayMoongcleoffers[$key]->curated_tags_list = json_decode($stayMoongcleoffers[$key]->curated_tags);

                    $key++;
                }
            }
        }

        $sql = "SELECT 
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

        $allRooms = Database::getInstance()->getConnection()->select($sql, $bindings);

        $allRoomsData = [];
        $closedRoomsIdxArray = [];
        foreach ($allRooms as $room) {
            $allRoomsData[$room->room_idx] = $room;
            $allRoomsIdxArray[$room->room_idx] = 'exist';
        }

        foreach ($stayMoongcleoffers as $stayMoongcleoffer) {
            foreach ($stayMoongcleoffer->moongcleoffers as $moongcleoffer) {
                if (!empty($closedRoomsIdxArray[$moongcleoffer->room_idx])) {
                    unset($closedRoomsIdxArray[$moongcleoffer->room_idx]);
                }
            }
        }

        $closedRooms = [];
        foreach ($closedRoomsIdxArray as $closedRoomsIdx => $value) {
            $closedRooms[] = $allRoomsData[$closedRoomsIdx];
        }

        $cancelRules = CancelRule::where('partner_idx', $partnerIdx)
            ->orderBy('cancel_rules_percent', 'DESC')
            ->get();

        $startDateTime = new \DateTime($_GET['startDate']);
        $endDateTime = new \DateTime($_GET['endDate']);

        $interval = $startDateTime->diff($endDateTime);

        $sql = "
            SELECT
                mtc.*
            FROM partners p
            LEFT JOIN stays s ON s.stay_idx = p.partner_detail_idx
            LEFT JOIN moongcle_tag_connections mtc ON mtc.item_idx = s.stay_idx AND mtc.item_type = 'stay'
            WHERE p.partner_idx = :partnerIdx
        ";

        $bindings = [
            'partnerIdx' => $partnerIdx
        ];

        $moongcleStayTags = Database::getInstance()->getConnection()->select($sql, $bindings);

        $sql = "
            SELECT
                mtc.*
            FROM rooms r
            LEFT JOIN moongcle_tag_connections mtc ON mtc.item_idx = r.room_idx AND mtc.item_type = 'room'
            WHERE r.partner_idx = :partnerIdx
        ";

        $bindings = [
            'partnerIdx' => $partnerIdx
        ];

        $moongcleRoomTags = Database::getInstance()->getConnection()->select($sql, $bindings);

        $moongcleofferFavorites = null;
        if ($user && !$isGuest) {
            $moongcleofferFavorites = PartnerFavorite::where('user_idx', $user->user_idx)
                ->where('target', 'moongcleoffer')
                ->where('partner_idx', $partnerIdx)
                ->first();
        }

        $redis = RedisManager::getInstance();

        // Redis 키 설정
        $redisKey = "moongcleoffer:open_calendar:$partnerIdx";
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
                        WHEN SUM(CASE WHEN ri.inventory_quantity > 0 AND mp.moongcleoffer_price_sale > 0 THEN 1 ELSE 0 END) > 0 THEN 'open'
                        ELSE 'closed'
                    END AS status,
                    MIN(CASE WHEN ri.inventory_quantity > 0 AND mp.moongcleoffer_price_sale > 0 THEN mp.moongcleoffer_price_sale ELSE NULL END) AS lowest_price
                FROM moongcletrip.room_rateplan rr
                LEFT JOIN moongcletrip.rooms r ON r.room_idx = rr.room_idx 
                LEFT JOIN moongcletrip.room_inventories ri 
                    ON rr.room_rateplan_idx = ri.room_rateplan_idx
                LEFT JOIN moongcletrip.moongcleoffer_prices mp 
                    ON rr.room_rateplan_idx = mp.room_rateplan_idx 
                    AND ri.rateplan_idx = mp.rateplan_idx 
                    AND ri.inventory_date = mp.moongcleoffer_price_date
                WHERE r.partner_idx = :partnerIdx
                    AND ri.inventory_date >= CURDATE()
                    AND r.room_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND mp.is_closed = 0
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
                mo.rateplan_idx,
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
            'stayMoongcleoffers' => $stayMoongcleoffers,
            'allRooms' => $allRoomsData,
            'closedRooms' => $closedRooms,
            'moongclePoint' => !empty($moongclePoint) ? $moongclePoint[0] : null,
            'mainTagList' => $mainTagList,
            'cancelRules' => $cancelRules,
            'intervalDays' => $interval->days,
            'moongcleStayTags' => $moongcleStayTags,
            'moongcleRoomTags' => $moongcleRoomTags,
            'totalGuests' => $totalGuests,
            'moongcleofferFavorites' => $moongcleofferFavorites,
            'openCalendar' => $openCalendar,
            'curatedTags' => $curatedTags,
            'curatedTagsEncode' => $curatedTagsEncode,
            'reviews' => $reviews,
            'reviewRating' => $reviewRating,
            'reviewCount' => $reviewCount,
            'partnerFaq' => $partnerFaq,
            'facilities' => $facilities,
            'services' => $services,
        );

        self::render('moongcleoffer-product', ['data' => $data]);
    }

    // public static function main($moongcleofferIdx)
    // {
    //     $partner = null;
    //     $moongclePoint = null;
    //     $rooms = [];
    //     $closedRooms = null;
    //     $mainTagList = [];

    //     $moongcleoffer = MoongcleOffer::find($moongcleofferIdx);
    //     $partnerIdx = $moongcleoffer->partner_idx;

    //     $deviceType = getDeviceType();

    //     $user = MiddleHelper::checkLoginCookie();
    //     $isGuest = true;

    //     if ($user) {
    //         if ($user->user_is_guest == false) {
    //             $isGuest = false;
    //         }
    //     }

    //     $queryString = $_SERVER['QUERY_STRING'];

    //     header("Location: /moongcleoffer/product/" . $partnerIdx . ($queryString ? '?' . $queryString : ''));
    //     exit;

    //     if (empty($_GET['startDate']) || empty($_GET['endDate'])) {
    //         $bindings = [];
    //         $totalGuests = 0;

    //         if (!empty($_GET['adult'])) {
    //             $totalGuests += $_GET['adult'];
    //         }
    //         if (!empty($_GET['child'])) {
    //             $totalGuests += $_GET['child'];
    //         }
    //         if (!empty($_GET['infant'])) {
    //             $totalGuests += $_GET['infant'];
    //         }

    //         $bindings['partnerIdx'] = $partnerIdx;
    //         $bindings['totalGuests'] = $totalGuests;
    //         $bindings['moongcleofferIdx'] = $moongcleofferIdx;

    //         // $sql = "
    //         //     SELECT rp.room_price_date,
    //         //         MIN(rp.room_price_sale) AS lowest_price
    //         //     FROM room_prices rp
    //         //     JOIN room_inventories ri ON rp.room_idx = ri.room_idx AND rp.rateplan_idx = ri.rateplan_idx AND rp.room_price_date = ri.inventory_date
    //         //     JOIN rooms r ON rp.room_idx = r.room_idx
    //         //     JOIN room_rateplan rr ON rp.rateplan_idx = rr.rateplan_idx AND r.room_idx = rr.room_idx
    //         //     WHERE rp.room_price_sale > 0
    //         //         AND ri.inventory_quantity > 0
    //         //         AND rp.room_price_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
    //         //         AND r.room_status = 'enabled'
    //         //         AND rr.room_rateplan_status = 'enabled'
    //         //         AND r.partner_idx = :partnerIdx
    //         //         AND r.room_max_person >= :totalGuests
    //         //     GROUP BY rp.room_price_date
    //         //     ORDER BY lowest_price ASC
    //         //     LIMIT 1;
    //         // ";

    //         $sql = "
    //             SELECT mp.moongcleoffer_price_date,
    //                 MIN(mp.moongcleoffer_price_sale) AS lowest_price
    //             FROM moongcletrip.moongcleoffer_prices mp
    //             JOIN moongcletrip.moongcleoffers mo ON mo.moongcleoffer_idx = mp.moongcleoffer_idx 
    //             JOIN moongcletrip.room_prices rp ON rp.room_rateplan_idx = mp.room_rateplan_idx AND rp.room_price_date = mp.moongcleoffer_price_date
    //             JOIN moongcletrip.room_inventories ri ON rp.room_idx = ri.room_idx AND rp.rateplan_idx = ri.rateplan_idx AND rp.room_price_date = ri.inventory_date
    //             JOIN moongcletrip.rooms r ON rp.room_idx = r.room_idx
    //             JOIN moongcletrip.room_rateplan rr ON rp.rateplan_idx = rr.rateplan_idx AND r.room_idx = rr.room_idx
    //             WHERE mp.moongcleoffer_price_sale > 0
    //                 AND mo.moongcleoffer_idx = :moongcleofferIdx
    //                 AND ri.inventory_quantity > 0
    //                 AND mp.moongcleoffer_price_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
    //                 AND r.room_status = 'enabled'
    //                 AND rr.room_rateplan_status = 'enabled'
    //                 AND r.partner_idx = :partnerIdx
    //                 AND r.room_max_person >= :totalGuests
    //             GROUP BY rp.room_price_date
    //             ORDER BY lowest_price ASC
    //             LIMIT 1;
    //         ";

    //         $lowestPriceDay = Database::getInstance()->getConnection()->select($sql, $bindings);

    //         if (!empty($lowestPriceDay[0]->moongcleoffer_price_date)) {
    //             $startDate = $lowestPriceDay[0]->moongcleoffer_price_date;
    //         } else {
    //             $startDate = date('Y-m-d');
    //         }

    //         $date = new \DateTime($startDate);

    //         // 하루 더하기
    //         $date->modify('+1 day');

    //         // 결과 출력
    //         $endDate = $date->format('Y-m-d');

    //         $currentUrl = $_SERVER['REQUEST_URI'];
    //         $currentDomain = explode('?', $_SERVER['REQUEST_URI']);

    //         $queryParams = $_GET;
    //         $queryParams['startDate'] = $startDate;
    //         $queryParams['endDate'] = $endDate;

    //         if ($totalGuests == 0) {
    //             $queryParams['adult'] = 2;
    //             $queryParams['child'] = 0;
    //             $queryParams['infant'] = 0;
    //         }

    //         $newQueryString = http_build_query($queryParams);
    //         $newUrl = $currentDomain[0] . '?' . $newQueryString;

    //         // parentGotoNewUrl($newUrl);
    //         header("Location: $newUrl");
    //         exit;
    //     }

    //     MiddleHelper::checkUserAction();

    //     $totalGuests = 0;

    //     if (!empty($_GET['adult'])) {
    //         $totalGuests += $_GET['adult'];
    //     }
    //     if (!empty($_GET['child'])) {
    //         $totalGuests += $_GET['child'];
    //     }
    //     if (!empty($_GET['infant'])) {
    //         $totalGuests += $_GET['infant'];
    //     }

    //     $bindings = [];
    //     $bindings['partnerIdx'] = $partnerIdx;

    //     $sql = "
    //         SELECT
    //             p.*,
    //             s.*,
    //             (
	// 				SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
	// 				FROM moongcletrip.images img
	// 				WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
	// 			) AS image_paths,
    //             (
	// 				SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
	// 				FROM moongcletrip.curated_images img
	// 				WHERE img.image_entity_id = p.partner_detail_idx AND img.image_entity_type = 'stay'
	// 			) AS curated_image_paths,
    //             rs.average_rating,
    //             rs.review_count,
    //             GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
    //             GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types
    //         FROM partners p
    //         LEFT JOIN stays s ON p.partner_detail_idx = s.stay_idx
    //         LEFT JOIN review_statistics rs ON p.partner_detail_idx = rs.entity_idx AND rs.entity_type = 'stay'
    //         LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
    //         LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
    //         WHERE p.partner_category = 'stay'
    //             AND p.partner_idx = :partnerIdx
    //     ";

    //     $partner = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     if (!empty($partner[0]) && $partner[0]->partner_status != 'enabled') {
    //         header('Location: /404');
    //     }

    //     $sql = "
    //         SELECT
    //             tc.*
    //         FROM partners p
    //         LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay'
    //         WHERE p.partner_idx = :partnerIdx
    //     ";

    //     $mainTags = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     foreach ($mainTags as $tags) {
    //         if (!isset($mainTagList[$tags->connection_type])) {
    //             $mainTagList[$tags->connection_type] = [];
    //         }

    //         $mainTagList[$tags->connection_type][] = $tags;
    //     }

    //     $sql = "
    //         SELECT 
    //             r.*,
    //             (
	// 				SELECT GROUP_CONCAT(image_big_path ORDER BY image_order SEPARATOR ':-:')
	// 				FROM moongcletrip.images img
	// 				WHERE img.image_entity_id = r.room_idx AND img.image_entity_type = 'room'
	// 			) AS image_paths,
    //             (
    //                 SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
    //                 FROM moongcletrip.tag_connections t
    //                 WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
    //             ) AS views
    //         FROM rooms r
    //         WHERE 
    //             r.partner_idx = :partnerIdx
    //         ORDER BY r.room_order ASC, r.room_idx ASC;
    //     ";

    //     $bindings = [
    //         'partnerIdx' => $partnerIdx
    //     ];

    //     // 쿼리 실행
    //     $allRooms = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $allRoomsData = [];
    //     foreach ($allRooms as $room) {
    //         $allRoomsData[$room->room_idx] = $room;
    //     }

    //     $cancelRules = CancelRule::where('partner_idx', $partnerIdx)
    //         ->orderBy('cancel_rules_order', 'DESC')
    //         ->get();

    //     $startDateTime = new \DateTime($_GET['startDate']);
    //     $endDateTime = new \DateTime($_GET['endDate']);

    //     $interval = $startDateTime->diff($endDateTime);

    //     // $sql = "
    //     //     SELECT
    //     //         mo.*,
    //     //         r.*,
    //     //         (SELECT 
    //     //             JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //     //         FROM moongcletrip.benefit_item bi 
    //     //         WHERE bi.item_idx = r.room_idx AND bi.item_type = 'room') AS room_benefits,
    //     //         (SELECT 
    //     //             JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //     //         FROM moongcletrip.benefit_item bi 
    //     //         WHERE bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan') AS rateplan_benefits,
    //     //         (SELECT 
    //     //             JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //     //         FROM moongcletrip.benefit_item bi 
    //     //         WHERE bi.item_idx = mo.moongcleoffer_idx AND bi.item_type = 'moongcleoffer') AS moongcleoffer_benefits,
    //     //         (
    //     //         SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
    //     //         FROM tag_connections t
    //     //         WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
    //     //         ) AS views,
    //     //         (SELECT 
    //     //             JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
    //     //         FROM curated_tags ct 
    //     //         WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags,
    //     //         (SELECT 
    //     //             JSON_ARRAYAGG(JSON_OBJECT('image_path', img.image_big_path))
    //     //         FROM images img 
    //     //         WHERE img.image_entity_id = r.room_idx AND img.image_entity_type = 'room'
    //     //         ORDER BY img.image_order ASC) AS room_images,
    //     //         MIN(i.inventory_quantity) AS min_inventory_quantity,
    //     //         rt.rateplan_name,
    //     //         rt.rateplan_type,
    //     //         rt.rateplan_idx,
    //     //         rt.rateplan_sales_from,
    //     //         rt.rateplan_sales_to,
    //     //         SUM(mp.moongcleoffer_price_sale) AS total_sale_price,
    //     //         SUM(mp.moongcleoffer_price_basic) AS total_basic_price,
    //     //         rt.rateplan_stay_min,
    //     //         rt.rateplan_stay_max,
    //     //         rp.room_price_promotion_type
    //     //     FROM
    //     //         moongcleoffers mo
    //     //     LEFT JOIN partners p ON p.partner_idx = mo.partner_idx 
    //     //     LEFT JOIN rooms r ON p.partner_idx = r.partner_idx
    //     //     LEFT JOIN room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
    //     //     LEFT JOIN rateplans rt ON rt.rateplan_idx = rr.rateplan_idx
    //     //     LEFT JOIN moongcleoffer_prices mp ON mo.moongcleoffer_idx = mp.moongcleoffer_idx
    //     //     LEFT JOIN room_prices rp ON rp.room_price_idx = mp.base_idx
    //     //     LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx AND i.inventory_date = mp.moongcleoffer_price_date
    //     //     WHERE mo.moongcleoffer_idx = :moongcleofferIdx
    //     //         AND mo.moongcleoffer_status = 'enabled'
    //     //         AND r.room_status = 'enabled'
    //     //         AND rr.room_rateplan_status = 'enabled'
    //     //         AND i.inventory_quantity > 0
    //     //         AND mp.moongcleoffer_price_sale > 0
    //     //         AND mp.moongcleoffer_price_date >= :startDate
    //     //         AND mp.moongcleoffer_price_date < :endDate
    //     //         AND r.room_max_person >= :totalGuests
    //     //     HAVING COUNT(DISTINCT mp.moongcleoffer_price_date) = DATEDIFF(:endDate2, :startDate2)
    //     // ";

    //     // $bindings = [
    //     //     'moongcleofferIdx' => $moongcleofferIdx,
    //     //     'startDate' => $_GET['startDate'],
    //     //     'endDate' => $_GET['endDate'],
    //     //     'totalGuests' => $totalGuests,
    //     //     'endDate2' => $_GET['endDate'],
    //     //     'startDate2' => $_GET['startDate'],
    //     // ];

    //     // $moongcleoffer = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $sql = "
    //         SELECT
    //             mo.*,
    //             r.*,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //             FROM moongcletrip.benefit_item bi 
    //             WHERE bi.item_idx = r.room_idx AND bi.item_type = 'room') AS room_benefits,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //             FROM moongcletrip.benefit_item bi 
    //             WHERE bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan') AS rateplan_benefits,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //             FROM moongcletrip.benefit_item bi 
    //             WHERE bi.item_idx = mo.moongcleoffer_idx AND bi.item_type = 'moongcleoffer') AS moongcleoffer_benefits,
    //             (
    //             SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
    //             FROM tag_connections t
    //             WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
    //             ) AS views,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
    //             FROM curated_tags ct 
    //             WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('image_path', img.image_big_path))
    //             FROM images img 
    //             WHERE img.image_entity_id = r.room_idx AND img.image_entity_type = 'room'
    //             ORDER BY img.image_order ASC) AS room_images,
    //             rt.rateplan_name,
    //             rt.rateplan_type,
    //             rt.rateplan_idx
    //         FROM
    //             moongcleoffers mo
    //         LEFT JOIN partners p ON p.partner_idx = mo.partner_idx 
    //         LEFT JOIN room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
    //         LEFT JOIN rooms r ON rr.room_idx = r.room_idx
    //         LEFT JOIN rateplans rt ON rt.rateplan_idx = rr.rateplan_idx
    //         WHERE mo.moongcleoffer_idx = :moongcleofferIdx
    //             AND mo.moongcleoffer_status = 'enabled'
    //             AND r.room_status = 'enabled'
    //             AND rr.room_rateplan_status = 'enabled'
    //     ";

    //     $bindings = [
    //         'moongcleofferIdx' => $moongcleofferIdx,
    //     ];

    //     $moongcleofferInfo = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     if ($partner[0]->partner_thirdparty == 'onda') {
    //         $sql = "
    //             SELECT
    //                 mo.*,
    //                 r.*,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                 FROM moongcletrip.benefit_item bi 
    //                 WHERE bi.item_idx = r.room_idx AND bi.item_type = 'room') AS room_benefits,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                 FROM moongcletrip.benefit_item bi 
    //                 WHERE bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan') AS rateplan_benefits,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                 FROM moongcletrip.benefit_item bi 
    //                 WHERE bi.item_idx = mo.moongcleoffer_idx AND bi.item_type = 'moongcleoffer') AS moongcleoffer_benefits,
    //                 (
    //                 SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
    //                 FROM tag_connections t
    //                 WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
    //                 ) AS views,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
    //                 FROM curated_tags ct 
    //                 WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('image_path', img.image_big_path))
    //                 FROM images img 
    //                 WHERE img.image_entity_id = r.room_idx AND img.image_entity_type = 'room'
    //                 ORDER BY img.image_order ASC) AS room_images,
    //                 MIN(i.inventory_quantity) AS min_inventory_quantity,
    //                 rt.rateplan_name,
    //                 rt.rateplan_type,
    //                 rt.rateplan_idx,
    //                 rt.rateplan_sales_from,
    //                 rt.rateplan_sales_to,
    //                 SUM(mp.moongcleoffer_price_sale) AS total_sale_price,
    //                 SUM(mp.moongcleoffer_price_basic) AS total_basic_price,
    //                 rt.rateplan_stay_min,
    //                 rt.rateplan_stay_max,
    //                 rp.room_price_promotion_type
    //             FROM
    //                 moongcleoffers mo
    //             LEFT JOIN partners p ON p.partner_idx = mo.partner_idx 
    //             LEFT JOIN rooms r ON p.partner_idx = r.partner_idx
    //             LEFT JOIN room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
    //             LEFT JOIN rateplans rt ON rt.rateplan_idx = rr.rateplan_idx
    //             LEFT JOIN moongcleoffer_prices mp ON mo.moongcleoffer_idx = mp.moongcleoffer_idx
    //             LEFT JOIN room_prices rp ON rp.room_price_idx = mp.base_idx
    //             LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND i.rateplan_idx = rr.rateplan_idx AND i.inventory_date = mp.moongcleoffer_price_date
    //             WHERE
    //                 mo.moongcleoffer_category = 'roomRateplan'
    //                 AND p.partner_idx = :partnerIdx
    //                 AND mo.moongcleoffer_status = 'enabled'
    //                 AND r.room_status = 'enabled'
    //                 AND rr.room_rateplan_status = 'enabled'
    //                 AND i.inventory_quantity > 0
    //                 AND mp.moongcleoffer_price_sale > 0
    //                 AND mp.moongcleoffer_price_date >= :startDate
    //                 AND mp.moongcleoffer_price_date < :endDate
    //                 AND mp.is_closed = 0
    //                 AND r.room_max_person >= :totalGuests
    //             GROUP BY mo.moongcleoffer_idx
    //             HAVING COUNT(DISTINCT mp.moongcleoffer_price_date) = DATEDIFF(:endDate2, :startDate2)
    //         ";
    //     } else {
    //         $sql = "
    //             SELECT
    //                 mo.*,
    //                 r.*,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                 FROM moongcletrip.benefit_item bi 
    //                 WHERE bi.item_idx = r.room_idx AND bi.item_type = 'room') AS room_benefits,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                 FROM moongcletrip.benefit_item bi 
    //                 WHERE bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan') AS rateplan_benefits,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                 FROM moongcletrip.benefit_item bi 
    //                 WHERE bi.item_idx = mo.moongcleoffer_idx AND bi.item_type = 'moongcleoffer') AS moongcleoffer_benefits,
    //                 (
    //                 SELECT GROUP_CONCAT(DISTINCT t.tag_name ORDER BY t.tag_name ASC SEPARATOR ':-:')
    //                 FROM tag_connections t
    //                 WHERE t.item_idx = r.room_idx AND t.item_type = 'room' AND t.connection_type = 'view'
    //                 ) AS views,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
    //                 FROM curated_tags ct 
    //                 WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags,
    //                 (SELECT 
    //                     JSON_ARRAYAGG(JSON_OBJECT('image_path', img.image_big_path))
    //                 FROM images img 
    //                 WHERE img.image_entity_id = r.room_idx AND img.image_entity_type = 'room'
    //                 ORDER BY img.image_order ASC) AS room_images,
    //                 MIN(i.inventory_quantity) AS min_inventory_quantity,
    //                 rt.rateplan_name,
    //                 rt.rateplan_type,
    //                 rt.rateplan_idx,
    //                 rt.rateplan_sales_from,
    //                 rt.rateplan_sales_to,
    //                 SUM(mp.moongcleoffer_price_sale) AS total_sale_price,
    //                 SUM(mp.moongcleoffer_price_basic) AS total_basic_price,
    //                 rt.rateplan_stay_min,
    //                 rt.rateplan_stay_max,
    //                 rp.room_price_promotion_type
    //             FROM
    //                 moongcleoffers mo
    //             LEFT JOIN partners p ON p.partner_idx = mo.partner_idx 
    //             LEFT JOIN rooms r ON p.partner_idx = r.partner_idx
    //             LEFT JOIN room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
    //             LEFT JOIN rateplans rt ON rt.rateplan_idx = rr.rateplan_idx
    //             LEFT JOIN moongcleoffer_prices mp ON mo.moongcleoffer_idx = mp.moongcleoffer_idx
    //             LEFT JOIN room_prices rp ON r.room_idx = rp.room_idx AND rp.rateplan_idx = rr.rateplan_idx AND rp.room_price_date = mp.moongcleoffer_price_date
    //             LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND i.rateplan_idx = 0 AND i.inventory_date = mp.moongcleoffer_price_date
    //             WHERE
    //                 mo.moongcleoffer_category = 'roomRateplan'
    //                 AND p.partner_idx = :partnerIdx
    //                 AND mo.moongcleoffer_status = 'enabled'
    //                 AND r.room_status = 'enabled'
    //                 AND rr.room_rateplan_status = 'enabled'
    //                 AND i.inventory_quantity > 0
    //                 AND rp.is_closed = 0
    //                 AND mp.moongcleoffer_price_sale > 0
    //                 AND mp.moongcleoffer_price_date >= :startDate
    //                 AND mp.moongcleoffer_price_date < :endDate
    //                 AND mp.is_closed = 0
    //                 AND r.room_max_person >= :totalGuests
    //             GROUP BY mo.moongcleoffer_idx
    //             HAVING COUNT(DISTINCT mp.moongcleoffer_price_date) = DATEDIFF(:endDate2, :startDate2)
    //         ";
    //     }

    //     $bindings = [
    //         'partnerIdx' => $partnerIdx,
    //         'startDate' => $_GET['startDate'],
    //         'endDate' => $_GET['endDate'],
    //         'totalGuests' => $totalGuests,
    //         'endDate2' => $_GET['endDate'],
    //         'startDate2' => $_GET['startDate'],
    //     ];

    //     $roomsQuery = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $otherMoongcleoffers = [];

    //     foreach ($roomsQuery as $room) {
    //         if (!isset($otherMoongcleoffers[$room->room_idx])) {
    //             $otherMoongcleoffers[$room->room_idx] = [
    //                 'room_idx' => $room->room_idx,
    //                 'room_name' => $room->room_name,
    //                 'room_standard_person' => $room->room_standard_person,
    //                 'room_max_person' => $room->room_max_person,
    //                 'room_bed_type' => $room->room_bed_type,
    //                 'min_inventory_quantity' => $room->min_inventory_quantity,
    //                 'room_images' => $room->room_images,
    //                 'views' => $room->views,
    //                 'moongcleoffers' => []
    //             ];
    //         }

    //         // if ($moongcleofferIdx == $room->moongcleoffer_idx) {
    //         //     continue;
    //         // }

    //         if (!empty($room->rateplan_sales_from) && !empty($room->rateplan_sales_to)) {
    //             if (strtotime($room->rateplan_sales_from) > strtotime('now') || strtotime($room->rateplan_sales_to) < strtotime('now')) {
    //                 continue;
    //             }
    //         }

    //         $otherMoongcleoffers[$room->room_idx]['moongcleoffers'][] = [
    //             'moongcleoffer_idx' => $room->moongcleoffer_idx,
    //             'rateplan_idx' => $room->rateplan_idx,
    //             'rateplan_name' => $room->rateplan_name,
    //             'rateplan_type' => $room->rateplan_type,
    //             'total_basic_price' => $room->total_basic_price,
    //             'total_sale_price' => $room->total_sale_price,
    //             'rateplan_stay_min' => $room->rateplan_stay_min,
    //             'rateplan_stay_max' => $room->rateplan_stay_max,
    //             'room_benefits' => $room->room_benefits,
    //             'rateplan_benefits' => $room->rateplan_benefits,
    //             'moongcleoffer_benefits' => $room->moongcleoffer_benefits,
    //         ];
    //     }

    //     $closedRooms = array_diff_key($allRoomsData, $otherMoongcleoffers);

    //     foreach ($otherMoongcleoffers as $key => $otherMoongcleoffer) {
    //         if (empty($otherMoongcleoffer['moongcleoffers'])) {
    //             unset($otherMoongcleoffers[$key]);
    //         }
    //     }

    //     $sql = "
    //         SELECT
    //             mtc.*
    //         FROM partners p
    //         LEFT JOIN stays s ON s.stay_idx = p.partner_detail_idx
    //         LEFT JOIN moongcle_tag_connections mtc ON mtc.item_idx = s.stay_idx AND mtc.item_type = 'stay'
    //         WHERE p.partner_idx = :partnerIdx
    //     ";

    //     $bindings = [
    //         'partnerIdx' => $partnerIdx
    //     ];

    //     $moongcleStayTags = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $sql = "
    //         SELECT
    //             mtc.*
    //         FROM rooms r
    //         LEFT JOIN moongcle_tag_connections mtc ON mtc.item_idx = r.room_idx AND mtc.item_type = 'room'
    //         WHERE r.partner_idx = :partnerIdx
    //     ";

    //     $bindings = [
    //         'partnerIdx' => $partnerIdx
    //     ];

    //     $moongcleRoomTags = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $moongcleofferFavorites = [];
    //     if ($user && !$isGuest) {
    //         $moongcleofferFavorites = PartnerFavorite::where('user_idx', $user->user_idx)
    //             ->where('target', 'moongcleoffer')
    //             ->pluck('moongcleoffer_idx')
    //             ->toArray();
    //     }

    //     $redis = RedisManager::getInstance();

    //     // Redis 키 설정
    //     $redisKey = "moongcleoffer:open_calendar:$partnerIdx";
    //     $cacheTTL = 3600; // 한 시간 (초 단위)

    //     // Redis에서 데이터 조회
    //     $cachedData = $redis->get($redisKey);

    //     if ($cachedData) {
    //         $openCalendar = json_decode($cachedData);
    //     } else {
    //         $sql = "
    //             SELECT 
    //                 ri.inventory_date,
    //                 CASE 
    //                     WHEN SUM(CASE WHEN ri.inventory_quantity > 0 AND mp.moongcleoffer_price_sale > 0 THEN 1 ELSE 0 END) > 0 THEN 'open'
    //                     ELSE 'closed'
    //                 END AS status,
    //                 MIN(CASE WHEN ri.inventory_quantity > 0 AND mp.moongcleoffer_price_sale > 0 THEN mp.moongcleoffer_price_sale ELSE NULL END) AS lowest_price
    //             FROM moongcletrip.rooms r
    //             LEFT JOIN moongcletrip.room_inventories ri 
    //                 ON r.room_idx = ri.room_idx
    //             LEFT JOIN moongcletrip.room_prices rp 
    //                 ON r.room_idx = rp.room_idx 
    //                 AND ri.rateplan_idx = rp.rateplan_idx 
    //                 AND ri.inventory_date = rp.room_price_date
    //             LEFT JOIN moongcletrip.moongcleoffer_prices mp 
    //                 ON mp.base_idx = rp.room_price_idx
    //             WHERE r.partner_idx = :partnerIdx
    //                 AND ri.inventory_date >= CURDATE()
    //                 AND r.room_status = 'enabled'
    //             GROUP BY ri.inventory_date
    //             ORDER BY ri.inventory_date;
    //         ";

    //         $bindings = [
    //             'partnerIdx' => $partnerIdx
    //         ];

    //         $openCalendar = Database::getInstance()->getConnection()->select($sql, $bindings);

    //         $redis->setex($redisKey, $cacheTTL, json_encode($openCalendar));
    //     }

    //     $sql = "
    //         SELECT
    //             mp.*,
    //             (
    //                 SELECT CONCAT('[', GROUP_CONCAT(
    //                     JSON_OBJECT(
    //                         'image_origin_name', img.image_origin_name,
    //                         'image_path', img.image_big_path,
    //                         'image_origin_size', img.image_origin_size
    //                     ) ORDER BY img.image_order ASC SEPARATOR ','), ']')
    //                 FROM moongcletrip.moongcle_point_images img
    //                 WHERE img.moongcle_point_idx = mp.moongcle_point_idx
    //             ) AS images
    //         FROM moongcle_points mp
    //         WHERE mp.partner_idx = :partnerIdx
    //     ";

    //     $bindings = [
    //         'partnerIdx' => $partnerIdx
    //     ];

    //     $moongclePoint = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $sql = "
    //         SELECT 
    //             r.*,
    //             u.user_nickname,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('origin_path', ri.review_image_origin_path, 'path', ri.review_image_normal_path, 'extension', ri.review_image_extension, 'order', ri.review_image_order))
    //             FROM moongcletrip.review_images ri 
    //             WHERE ri.review_idx = r.review_idx) AS image_list,
    //             (SELECT 
    //                 JSON_ARRAYAGG(JSON_OBJECT('tag_name', rt.tag_name, 'tag_machine_name', rt.tag_machine_name, 'order', rt.tag_order))
    //             FROM moongcletrip.review_tags rt 
    //             WHERE rt.review_idx = r.review_idx) AS tag_list
    //         FROM moongcletrip.reviews r 
    //         LEFT JOIN moongcletrip.users u ON u.user_idx = r.user_idx
    //         WHERE r.partner_idx = :partnerIdx
    //         GROUP BY r.review_idx
    //         ORDER BY r.created_at DESC;
    //     ";

    //     $bindings = [
    //         'partnerIdx' => $partnerIdx
    //     ];

    //     $reviews = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $reviewRating = 0;
    //     $reviewCount = 0;

    //     if (!empty($reviews[0])) {
    //         foreach ($reviews as $review) {
    //             $reviewRating += $review->rating;
    //             $reviewCount++;
    //         }
    //     }

    //     if ($reviewCount !== 0) {
    //         $reviewRating = round($reviewRating / $reviewCount, 2);
    //     }

    //     $partnerFaq = PartnerFaq::where('partner_idx', $partnerIdx)
    //         ->orderBy('faq_order', 'ASC')
    //         ->get();

    //     $sql = "
    //         SELECT
    //             fd.*,
    //             (
    //                 SELECT CONCAT('[', GROUP_CONCAT(
    //                     JSON_OBJECT(
    //                         'image_origin_name', img.image_origin_name,
    //                         'image_origin_path', img.image_origin_path,
    //                         'image_origin_size', img.image_origin_size
    //                     ) ORDER BY img.image_order ASC SEPARATOR ','), ']')
    //                 FROM moongcletrip.facility_images img
    //                 WHERE img.facility_detail_idx = fd.facility_detail_idx
    //             ) AS images
    //         FROM facility_detail fd
    //         WHERE fd.partner_idx = :partnerIdx
    //         ORDER BY fd.created_at ASC;
    //     ";

    //     $bindings = [
    //         'partnerIdx' => $partnerIdx
    //     ];

    //     $facilities = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $sql = "
    //         SELECT
    //             sd.*
    //         FROM service_detail sd
    //         WHERE sd.partner_idx = :partnerIdx
    //         ORDER BY sd.created_at ASC;
    //     ";

    //     $bindings = [
    //         'partnerIdx' => $partnerIdx
    //     ];

    //     $services = Database::getInstance()->getConnection()->select($sql, $bindings);

    //     $data = array(
    //         'deviceType' => $deviceType,
    //         'user' => $user,
    //         'isGuest' => $isGuest,
    //         'partner' => $partner[0],
    //         'rooms' => $rooms,
    //         'allRooms' => $allRoomsData,
    //         'closedRooms' => $closedRooms,
    //         'moongclePoint' => !empty($moongclePoint) ? $moongclePoint[0] : null,
    //         'mainTagList' => $mainTagList,
    //         'cancelRules' => $cancelRules,
    //         'intervalDays' => $interval->days,
    //         'moongcleoffer' => !empty($moongcleoffer[0]) ? $moongcleoffer[0] : null,
    //         'moongcleofferInfo' => !empty($moongcleofferInfo[0]) ? $moongcleofferInfo[0] : null,
    //         'otherMoongcleoffers' => $otherMoongcleoffers,
    //         'moongcleStayTags' => $moongcleStayTags,
    //         'moongcleRoomTags' => $moongcleRoomTags,
    //         'totalGuests' => $totalGuests,
    //         'moongcleofferFavorites' => $moongcleofferFavorites,
    //         'openCalendar' => $openCalendar,
    //         'reviews' => $reviews,
    //         'reviewRating' => $reviewRating,
    //         'reviewCount' => $reviewCount,
    //         'partnerFaq' => $partnerFaq,
    //         'facilities' => $facilities,
    //         'services' => $services,
    //     );

    //     self::render('moongcleoffer-detail', ['data' => $data]);
    // }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
