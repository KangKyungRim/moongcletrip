<?php

namespace App\Controllers\View;

use App\Models\MoongcleDeal;
use App\Models\MoongcleMatch;
use App\Models\Notification;
use App\Models\PartnerFavorite;

use App\Services\TagService;

use App\Helpers\MiddleHelper;

use Database;

class MoongcledealViewController
{
    /**
     * 뭉클딜
     */
    public static function main()
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;
        $unreadMoocledealCount = 0;
        $moongcledealIdx = $_GET['moongcledealIdx'];

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

        $deviceType = getDeviceType();

        $inProgressMoongcledeal = null;

        if ($user) {
            $inProgressMoongcledeal = MoongcleDeal::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->whereIn('status', ['in_progress', 'matching', 'matched'])
                ->where('moongcledeal_create_complete', true)
                ->orderBy('created_at', 'DESC')
                ->get();

            foreach ($inProgressMoongcledeal as &$deal) {
                $deal->deal_count = MoongcleMatch::where('moongcledeal_idx', $deal->moongcledeal_idx)
                    ->whereIn('notification_status', ['sent', 'token_x'])
                    ->count();

                $deal->unread_deal_count = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                    ->where('notification_type', 'moongcledeal')
                    ->where('base_idx', $deal->moongcledeal_idx)
                    ->where('is_read', false)
                    ->distinct('target_idx')
                    ->count('target_idx');
            }
        }

        if (!empty($inProgressMoongcledeal[0])) {
            if (empty($moongcledealIdx)) {
                header('Location: /moongcledeals?moongcledealIdx=' . $inProgressMoongcledeal[0]->moongcledeal_idx);
            }

            $moongcledeal = MoongcleDeal::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('moongcledeal_idx', $moongcledealIdx)
                ->first();

            // ▼▼▼ [추가] 사용자 선택 날짜 추출 로직 ▼▼▼
            $user_start_date = null;
            $user_end_date = null;
            if ($moongcledeal && !empty($moongcledeal->selected)) {
                $selectedData = $moongcledeal->selected;
                // JSON 구조에 따라 'dates' 키가 있는지 확인
                if (!empty($selectedData['days'][0]['dates'])) {
                    if ($selectedData['days'][0]['type'] == 'period') {
                        $dateParts = explode('~', $selectedData['days'][0]['dates']);
                        
                        $user_start_date = $dateParts[0]; // 예: '2025-08-30'
                        $user_end_date = $dateParts[1];   // 예: '2025-08-31'
                        
                    }
                }
            }
            // ▲▲▲ [추가] 여기까지 ▲▲▲

            $moongcleMatches = MoongcleMatch::where('moongcledeal_idx', $moongcledealIdx)
                ->whereIn('notification_status', ['sent', 'token_x'])
                ->orderBy('notification_time', 'DESC')
                ->get();

            //뭉클딜 매칭 여부 조회 (match_count, match_count_fcm)
            $moongcleDealNMatchStatusSql = "
                SELECT
                    md.status,
                    md.status_view,
                    (
                        SELECT COUNT(*)
                        FROM moongcle_match mm
                        WHERE mm.moongcledeal_idx = md.moongcledeal_idx
                    ) AS match_count, #뭉클딜 매칭, fcm 발송x 전체건
                    (
                        SELECT COUNT(*)
                        FROM moongcle_match mm
                        WHERE mm.moongcledeal_idx = md.moongcledeal_idx
                        	AND notification_status in ('sent','token_x')
                    ) AS match_count_fcm #뭉클딜 매칭, fcm 발송 완료
                FROM
                    moongcledeals md
                where md.moongcledeal_idx = $moongcledealIdx
                ";
            $moongcleDealNMatchStatus = Database::getInstance()->getConnection()->select($moongcleDealNMatchStatusSql);

            Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('notification_type', 'moongcledeal')
                ->where('base_idx', $moongcledealIdx)
                ->update(['is_read' => true]);

            //moongcleoffer_idx array
            $matchedIds = $moongcleMatches->pluck('product_idx')->toArray();

            $moongcleoffers = null;

            if (!empty($matchedIds)) {
                $bindingsPlaceholders = implode(',', array_fill(0, count($matchedIds), '?'));

                $sql = "
                SELECT
                    mo.*,
                    p.partner_idx,
                    p.partner_name,
                    p.partner_address1,
                    r.room_status,
                    (
                        SELECT mp.moongcleoffer_price_sale
                        FROM moongcletrip.moongcleoffer_prices mp
                        JOIN moongcletrip.room_inventories ri ON mp.room_idx = ri.room_idx AND mp.rateplan_idx = ri.rateplan_idx AND mp.moongcleoffer_price_date = ri.inventory_date
                        JOIN moongcletrip.rooms r ON mp.room_idx = r.room_idx
                        JOIN moongcletrip.room_rateplan rr ON mp.rateplan_idx = rr.rateplan_idx AND r.room_idx = rr.room_idx
                        WHERE mp.moongcleoffer_idx = mo.moongcleoffer_idx
                        AND mp.moongcleoffer_price_sale > 0
                        AND ri.inventory_quantity > 0
                        AND mp.is_closed = 0
                        AND r.room_status = 'enabled'
                        AND rr.room_rateplan_status = 'enabled'
                        AND mp.moongcleoffer_price_date BETWEEN
                            -- ▼▼▼ [수정] GREATEST를 추가해 과거 날짜는 오늘부터 시작하도록 처리 ▼▼▼
                            GREATEST(COALESCE(?, smo.stay_start_date, CURDATE()), CURDATE())
                            AND
                            -- 종료일은 기존 로직 유지
                            COALESCE(?, smo.stay_end_date, CURDATE() + INTERVAL 1 MONTH)
                        ORDER BY mp.moongcleoffer_price_sale ASC, mp.moongcleoffer_price_date ASC
                        LIMIT 1
                    ) AS lowest_price,
                    (
                        SELECT mp.moongcleoffer_price_basic
                        FROM moongcletrip.moongcleoffer_prices mp
                        JOIN moongcletrip.room_inventories ri ON mp.room_idx = ri.room_idx AND mp.rateplan_idx = ri.rateplan_idx AND mp.moongcleoffer_price_date = ri.inventory_date
                        JOIN moongcletrip.rooms r ON mp.room_idx = r.room_idx
                        JOIN moongcletrip.room_rateplan rr ON mp.rateplan_idx = rr.rateplan_idx AND r.room_idx = rr.room_idx
                        WHERE mp.moongcleoffer_idx = mo.moongcleoffer_idx
                        AND mp.moongcleoffer_price_sale > 0
                        AND ri.inventory_quantity > 0
                        AND mp.is_closed = 0
                        AND r.room_status = 'enabled'
                        AND rr.room_rateplan_status = 'enabled'
                        AND mp.moongcleoffer_price_date BETWEEN
                            -- ▼▼▼ [수정] basic_price에도 동일하게 적용 ▼▼▼
                            GREATEST(COALESCE(?, smo.stay_start_date, CURDATE()), CURDATE())
                            AND
                            COALESCE(?, smo.stay_end_date, CURDATE() + INTERVAL 1 MONTH)
                        ORDER BY mp.moongcleoffer_price_sale ASC, mp.moongcleoffer_price_date ASC
                        LIMIT 1
                    ) AS basic_price,
                    (
				        SELECT img.image_normal_path
				        FROM moongcletrip.images img
				        WHERE img.image_entity_id = p.partner_detail_idx
				        AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
				        ORDER BY img.image_order ASC
				        LIMIT 1
				    ) AS image_normal_path,
                    GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
                    GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types,
                    (SELECT 
                        JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
                    FROM curated_tags ct 
                    WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags,
                    (SELECT 
                        JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
                    FROM moongcletrip.benefit_item bi 
                    WHERE bi.item_idx = r.room_idx AND bi.item_type = 'room') AS room_benefits,
                    (SELECT 
                        JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
                    FROM moongcletrip.benefit_item bi 
                    WHERE bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan') AS rateplan_benefits,
                    (SELECT 
                        JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
                    FROM moongcletrip.benefit_item bi 
                    WHERE bi.item_idx = mo.moongcleoffer_idx AND bi.item_type = 'moongcleoffer') AS moongcleoffer_benefits
                FROM moongcleoffers mo
                LEFT JOIN stay_moongcleoffers smo ON smo.stay_moongcleoffer_idx = mo.stay_moongcleoffer_idx
                LEFT JOIN partners p ON p.partner_idx = mo.partner_idx
                LEFT JOIN room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
                LEFT JOIN rooms r ON rr.room_idx = r.room_idx 
                LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                LEFT JOIN moongcleoffer_prices mp ON mo.moongcleoffer_idx = mp.moongcleoffer_idx AND i.inventory_date = mp.moongcleoffer_price_date
                LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
                LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
                WHERE mo.moongcleoffer_status = 'enabled'
                    AND mo.moongcleoffer_category = 'roomRateplan'
                    AND p.partner_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND mo.moongcleoffer_idx IN ($bindingsPlaceholders)
                GROUP BY mo.moongcleoffer_idx
                ORDER BY FIELD(mo.moongcleoffer_idx, $bindingsPlaceholders);
                ";

                $bindings = [
                    $user_start_date, $user_end_date, // lowest_price 서브쿼리용
                    $user_start_date, $user_end_date, // basic_price 서브쿼리용
                ];
                $bindings = array_merge($bindings, $matchedIds, $matchedIds);
                $moongcleoffers = Database::getInstance()->getConnection()->select($sql, $bindings);
            }

            $moongcleofferFavorites = [];
            if ($user && !$isGuest) {
                $moongcleofferFavorites = PartnerFavorite::where('user_idx', $user->user_idx)
                    ->where('target', 'moongcleoffer')
                    ->pluck('moongcleoffer_idx')
                    ->toArray();
            }
        }

        $data = array(
            'user' => $user,
            'isGuest' => $isGuest,
            'deviceType' => $deviceType,
            'inProgressMoongcledeal' => $inProgressMoongcledeal,
            'moongcledeal' => $moongcledeal,
            'moongcleMatches' => $moongcleMatches,
            'moongcledealMatchInfo' => $moongcleDealNMatchStatus[0],
            'moongcleoffers' => $moongcleoffers,
            'moongcleofferFavorites' => $moongcleofferFavorites,
            'unreadMoocledealCount' => $unreadMoocledealCount
        );

        self::render('moongcledeal', ['data' => $data]);
    }

    public static function new()
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $deviceType = getDeviceType();

        $data = array(
            'user' => $user,
            'isGuest' => $isGuest,
            'deviceType' => $deviceType
        );

        self::render('moongcledeal-new', ['data' => $data]);
    }

    public static function test()
    {
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

        $deviceType = getDeviceType();

        $pendingMoongcledeal = null;
        $inProgressMoongcledeal = null;
        $stopMoongcledeal = null;

        if ($user) {
            $pendingMoongcledeal = MoongcleDeal::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('status', 'pending')
                ->where('moongcledeal_create_complete', false)
                ->first();

            $inProgressMoongcledeal = MoongcleDeal::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->whereIn('status', ['in_progress', 'matching', 'matched']) // whereIn 사용
                ->where('moongcledeal_create_complete', true)
                ->orderBy('created_at', 'DESC')
                ->get();

            $stopMoongcledeal = MoongcleDeal::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('status', 'stop')
                ->where('moongcledeal_create_complete', true)
                ->get();

            foreach ($inProgressMoongcledeal as &$deal) {
                $deal->deal_count = MoongcleMatch::where('moongcledeal_idx', $deal->moongcledeal_idx)
                    ->whereIn('notification_status', ['sent', 'token_x'])
                    ->count();

                $deal->unread_deal_count = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                    ->where('notification_type', 'moongcledeal')
                    ->where('base_idx', $deal->moongcledeal_idx)
                    ->where('is_read', false)
                    ->distinct('target_idx')
                    ->count('target_idx');
            }

            foreach ($stopMoongcledeal as &$deal) {
                $deal->deal_count = MoongcleMatch::where('moongcledeal_idx', $deal->moongcledeal_idx)
                    ->whereIn('notification_status', ['sent', 'token_x'])
                    ->count();

                $deal->unread_deal_count = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                    ->where('notification_type', 'moongcledeal')
                    ->where('base_idx', $deal->moongcledeal_idx)
                    ->where('is_read', false)
                    ->distinct('target_idx')
                    ->count('target_idx');
            }
        }

        $data = array(
            'user' => $user,
            'isGuest' => $isGuest,
            'deviceType' => $deviceType,
            'pendingMoongcledeal' => $pendingMoongcledeal,
            'inProgressMoongcledeal' => $inProgressMoongcledeal,
            'stopMoongcledeal' => $stopMoongcledeal,
            'unreadMoocledealCount' => $unreadMoocledealCount
        );

        self::render('moongcledeal-old', ['data' => $data]);
    }

    public static function moongcledealDetail($moongcledealIdx)
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }

            Notification::where('user_idx', $user->user_idx)
                ->where('notification_type', 'moongcledeal')
                ->where('base_idx', $moongcledealIdx)
                ->update(['is_read' => true]);
        }

        $deviceType = getDeviceType();

        $moongcleMatches = MoongcleMatch::where('moongcledeal_idx', $moongcledealIdx)
            ->whereIn('notification_status', ['sent', 'token_x'])
            ->orderBy('notification_time', 'DESC')
            ->get();

        $matchedIds = $moongcleMatches->pluck('product_idx')->toArray();

        $moongcleoffers = null;

        if (!empty($matchedIds)) {
            $bindingsPlaceholders = implode(',', array_fill(0, count($matchedIds), '?'));

            $sql = "
                SELECT
                    mo.*,
                    p.partner_idx,
                    p.partner_name,
                    p.partner_address1,
                    r.room_status,
                    (
                        SELECT mp.moongcleoffer_price_sale
                        FROM moongcletrip.moongcleoffer_prices mp
                        WHERE mp.moongcleoffer_idx = mo.moongcleoffer_idx
                        AND mp.moongcleoffer_price_sale > 0
                        ORDER BY mp.moongcleoffer_price_sale ASC
                        LIMIT 1
                    ) AS lowest_price,
                    (
                        SELECT mp.moongcleoffer_price_basic
                        FROM moongcletrip.moongcleoffer_prices mp
                        WHERE mp.moongcleoffer_idx = mo.moongcleoffer_idx
                        AND mp.moongcleoffer_price_sale = (
                            SELECT MIN(mp_inner.moongcleoffer_price_sale)
                            FROM moongcletrip.moongcleoffer_prices mp_inner
                            WHERE mp_inner.moongcleoffer_idx = mo.moongcleoffer_idx
                                AND mp_inner.moongcleoffer_price_sale > 0
                        )
                        LIMIT 1
                    ) AS basic_price,
                    (
				        SELECT img.image_normal_path
				        FROM moongcletrip.images img
				        WHERE img.image_entity_id = p.partner_detail_idx
				        AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
				        ORDER BY img.image_order ASC
				        LIMIT 1
				    ) AS image_normal_path,
                    GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
                    GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types,
                    (SELECT 
                        JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
                    FROM curated_tags ct 
                    WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags,
                    (SELECT 
                        JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
                    FROM moongcletrip.benefit_item bi 
                    WHERE bi.item_idx = r.room_idx AND bi.item_type = 'room') AS room_benefits,
                    (SELECT 
                        JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
                    FROM moongcletrip.benefit_item bi 
                    WHERE bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan') AS rateplan_benefits,
                    (SELECT 
                        JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
                    FROM moongcletrip.benefit_item bi 
                    WHERE bi.item_idx = mo.moongcleoffer_idx AND bi.item_type = 'moongcleoffer') AS moongcleoffer_benefits
                FROM moongcleoffers mo
                LEFT JOIN partners p ON p.partner_idx = mo.partner_idx
                LEFT JOIN room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
                LEFT JOIN rooms r ON rr.room_idx = r.room_idx 
                LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
                LEFT JOIN moongcleoffer_prices mp ON mo.moongcleoffer_idx = mp.moongcleoffer_idx AND i.inventory_date = mp.moongcleoffer_price_date
                LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
                LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
                WHERE mo.moongcleoffer_status = 'enabled'
                    AND mo.moongcleoffer_category = 'roomRateplan'
                    AND p.partner_status = 'enabled'
                    AND rr.room_rateplan_status = 'enabled'
                    AND mo.moongcleoffer_idx IN ($bindingsPlaceholders)
                GROUP BY mo.moongcleoffer_idx
                ORDER BY FIELD(mo.moongcleoffer_idx, $bindingsPlaceholders);
            ";

            $bindings = array_merge($matchedIds, $matchedIds);
            $moongcleoffers = Database::getInstance()->getConnection()->select($sql, $bindings);
        }

        $moongcledeal = MoongcleDeal::where('user_idx', $user->user_idx)
            ->where('moongcledeal_idx', $moongcledealIdx)
            ->first();

        if (empty($moongcledeal)) {
            header('Location: /moongcledeals');
        }

        $moongcledeal->deal_count = MoongcleMatch::where('moongcledeal_idx', $moongcledeal->moongcledeal_idx)
            ->whereIn('notification_status', ['sent', 'token_x'])
            ->count();

        $moongcleofferFavorites = [];
        if ($user && !$isGuest) {
            $moongcleofferFavorites = PartnerFavorite::where('user_idx', $user->user_idx)
                ->where('target', 'moongcleoffer')
                ->pluck('moongcleoffer_idx')
                ->toArray();
        }

        $data = array(
            'user' => $user,
            'isGuest' => $isGuest,
            'deviceType' => $deviceType,
            'moongcledeal' => $moongcledeal,
            'moongcleMatches' => $moongcleMatches,
            'moongcleoffers' => $moongcleoffers,
            'moongcleofferFavorites' => $moongcleofferFavorites
        );

        self::render('moongcledeal-detail', ['data' => $data]);
    }

    public static function create01()
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        } else {
            header('Location: /moongcledeals');
        }

        $deviceType = getDeviceType();

        $tagService = new TagService();

        $data = array(
            'deviceType' => $deviceType,
            'featuredTags' => $tagService->getFeaturedTags()
        );

        self::render('create-moongcledeal01', ['data' => $data]);
    }

    public static function create02()
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }
        // } else {
        //     header('Location: /moongcledeals');
        // }

        $deviceType = getDeviceType();
        $selectedTags = [];
        $moongcledeal = [];
        $existMoongcledeal = false;

        if (!empty($_GET['selected'])) {
            $selectedTags = json_decode(base64_decode($_GET['selected']), true);
        }
        if (!empty($_GET['moongcledeal_idx'])) {
            $existMoongcledeal = true;
            $moongcledeal = MoongcleDeal::find($_GET['moongcledeal_idx']);
        }

        $tagService = new TagService();
        $tags = $tagService->getMoongcledealTags();

        $data = array(
            'deviceType' => $deviceType,
            'selectedTags' => $selectedTags,
            'companionTags' => $tags['companionTags'],
            'petDetailTags' => $tags['petDetailTags'],
            'cityTags' => $tags['cityTags'],
            'overseasTags' => $tags['overseasTags'],
            'travelTasteTags' => $tags['travelTasteTags'],
            'eventTags' => $tags['eventTags'],
            'stayTasteTags' => $tags['stayTasteTags'],
            'stayTypeTags' => $tags['stayTypeTags'],
            'petFacilityTags' => $tags['petFacilityTags'],
            'stayBrandTags' => $tags['stayBrandTags'],
            'newTravelTasteTags' => $tags['newTravelTasteTags'],
            'newStayTasteTags' => $tags['newStayTasteTags'],
            'newStayTypeTags' => $tags['newStayTypeTags'],
            'moongcledeal' => $moongcledeal,
            'existMoongcledeal' => $existMoongcledeal
        );

        self::render('create-moongcledeal02', ['data' => $data]);
    }

    public static function create03()
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }
        // } else {
        //     header('Location: /moongcledeals');
        // }

        $deviceType = getDeviceType();
        $selected = [];

        $moongcledeal = MoongcleDeal::find($_GET['moongcledeal_idx']);

        if (empty($_GET['moongcledeal_idx'])) {
            header('Location: /moongcledeals');
        }

        $data = array(
            'deviceType' => $deviceType,
            'selected' => $moongcledeal->selected,
        );

        self::render('create-moongcledeal03', ['data' => $data]);
    }

    public static function edit01($moongcledealIdx)
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        } else {
            header('Location: /moongcledeals');
        }

        $deviceType = getDeviceType();
        $selectedTags = [];
        $moongcledeal = [];
        $existMoongcledeal = false;

        if (!empty($moongcledealIdx)) {
            $existMoongcledeal = true;
            $moongcledeal = MoongcleDeal::find($moongcledealIdx);
        }

        $tagService = new TagService();
        $tags = $tagService->getMoongcledealTags();

        $data = array(
            'deviceType' => $deviceType,
            'selectedTags' => $selectedTags,
            'companionTags' => $tags['companionTags'],
            'petDetailTags' => $tags['petDetailTags'],
            'cityTags' => $tags['cityTags'],
            'overseasTags' => $tags['overseasTags'],
            'travelTasteTags' => $tags['travelTasteTags'],
            'eventTags' => $tags['eventTags'],
            'stayTasteTags' => $tags['stayTasteTags'],
            'stayTypeTags' => $tags['stayTypeTags'],
            'petFacilityTags' => $tags['petFacilityTags'],
            'stayBrandTags' => $tags['stayBrandTags'],
            'moongcledeal' => $moongcledeal,
            'existMoongcledeal' => $existMoongcledeal,
            'newTravelTasteTags' => $tags['newTravelTasteTags'],
            'newStayTasteTags' => $tags['newStayTasteTags'],
            'newStayTypeTags' => $tags['newStayTypeTags'],
        );

        self::render('edit-moongcledeal01', ['data' => $data]);
    }

    public static function edit02($moongcledealIdx)
    {
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        } else {
            header('Location: /moongcledeals');
        }

        $deviceType = getDeviceType();
        $selected = [];

        $moongcledeal = MoongcleDeal::find($moongcledealIdx);

        if (empty($moongcledealIdx)) {
            header('Location: /moongcledeals');
        }

        $data = array(
            'deviceType' => $deviceType,
            'moongcledeal' => $moongcledeal,
        );

        self::render('edit-moongcledeal02', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
