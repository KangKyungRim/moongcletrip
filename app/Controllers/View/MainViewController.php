<?php

namespace App\Controllers\View;

use App\Models\Notification;
use App\Models\MoongcleMatch;
use App\Models\MainViewList;
use App\Models\StayMoongcleOffer;

use App\Services\TagService;

use App\Helpers\MiddleHelper;

use Database;
use RedisManager;

class MainViewController
{
    public static function main()
    {
        $deviceType = getDeviceType();

        MiddleHelper::checkUserAction();
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;
        $unreadMoocledealCount = 0;
        $moongcledeals = null;
        $mainMoongcledeal = null;
        $mainMoongcledealOffers = null;

        $redis = RedisManager::getInstance();

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }

            $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
                ->where('is_read', false)
                ->where('notification_type', 'moongcledeal')
                ->distinct()
                ->count(['base_idx', 'target_idx']);

            $bindings = [
                'userIdx' => $user->user_idx ?: $user->guest_idx
            ];

            $sql = "
                SELECT 
                    m.*
                FROM moongcletrip.moongcledeals m
                WHERE m.user_idx = :userIdx
                    AND m.status = 'matched'
                ORDER BY m.represent DESC, m.moongcledeal_idx DESC;
            ";

            $moongcledeals = Database::getInstance()->getConnection()->select($sql, $bindings);

            if (!empty($moongcledeals[0])) {
                $mainMoongcledeal = $moongcledeals[0];

                $moongcleMatches = MoongcleMatch::where('moongcledeal_idx', $mainMoongcledeal->moongcledeal_idx)
                    ->whereIn('notification_status', ['sent', 'token_x'])
                    ->orderBy('notification_time', 'DESC')
                    ->get();

                $matchedIds = $moongcleMatches->pluck('product_idx')->toArray();

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
                    $mainMoongcledealOffers = Database::getInstance()->getConnection()->select($sql, $bindings);
                }
            }
        }

        $tagService = new TagService();
        $tags = $tagService->getMoongcledealTags();

        $sql = "
             SELECT 
                r.*,
                u.user_nickname,
                p.partner_address1,
                (SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('origin_path', ri.review_image_origin_path, 'path', ri.review_image_normal_path, 'extension', ri.review_image_extension, 'order', ri.review_image_order))
                FROM moongcletrip.review_images ri 
                WHERE ri.review_idx = r.review_idx) AS image_list,
                (SELECT 
                    JSON_ARRAYAGG(JSON_OBJECT('tag_name', rt.tag_name, 'tag_machine_name', rt.tag_machine_name, 'order', rt.tag_order))
                FROM moongcletrip.review_tags rt 
                WHERE rt.review_idx = r.review_idx) AS tag_list,
                (
                    SELECT img.image_small_path
                    FROM moongcletrip.images img
                    WHERE img.image_entity_id = p.partner_detail_idx
                    AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
                    ORDER BY img.image_order ASC
                    LIMIT 1
                ) AS partner_image_path,
                GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS partner_types
            FROM moongcletrip.reviews r 
            LEFT JOIN moongcletrip.users u ON u.user_idx = r.user_idx 
            LEFT JOIN moongcletrip.partners p ON p.partner_idx = r.partner_idx 
            LEFT JOIN moongcletrip.tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type'
            WHERE r.is_active = true AND r.rating >= 4
            GROUP BY r.review_idx
            ORDER BY r.created_at DESC
            LIMIT 10;
        ";

        $reviews = Database::getInstance()->getConnection()->select($sql);

        foreach ($reviews as &$review) {
            if (!empty($review->tag_list)) {
                $tag = json_decode($review->tag_list, true);
                $review->encoded_tags = base64_encode(json_encode($tag));
            } else {
                $review->encoded_tags = null;
            }
        }

        $grouped = MainViewList::where('list_type', '!=', 'realtime_popularity')
            ->orderBy('list_order', 'ASC')
            ->get()
            ->groupBy('list_type');

        $groupedPartnerData = [];

        foreach ($grouped as $listType => $entries) {
            $partnerIds = $entries->pluck('partner_idx')->toArray();

            if (empty($partnerIds)) continue;

            $placeholders = implode(',', array_fill(0, count($partnerIds), '?'));

            $partnerIdsHash = md5(json_encode($partnerIds));
            $redisKey = "partner_data:{$listType}:{$partnerIdsHash}";
            $cacheTTL = 3600;

            $partnerData = $redis->get($redisKey);
            $partnerData = json_decode($partnerData);

            if (empty($partnerData)) {
                $sql = "
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
                        AND rp.room_price_sale > 0
                        AND r.room_status = 'enabled'
                        AND rr.room_rateplan_status = 'enabled'
                        GROUP BY r.partner_idx
                    ),
                    image_data AS (
                        SELECT 
                            img.image_entity_id AS stay_id,
                            img.image_normal_path
                        FROM moongcletrip.images img
                        WHERE img.image_entity_type = 'stay' AND img.image_type = 'basic'
                        AND img.image_order = (
                            SELECT MIN(img_sub.image_order)
                            FROM moongcletrip.images img_sub
                            WHERE img_sub.image_entity_id = img.image_entity_id
                                AND img_sub.image_entity_type = 'stay' AND img.image_type = 'basic'
                        )
                    )
                    SELECT
                        p.partner_idx,
                        p.partner_name,
                        p.partner_address1,
                        p.average_discount,
                        price_data.lowest_price,
                        price_data.basic_price,
                        id.image_normal_path,
                        GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
                        GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types
                    FROM moongcletrip.partners p
                    LEFT JOIN price_data ON p.partner_idx = price_data.partner_idx
                    LEFT JOIN image_data id ON p.partner_detail_idx = id.stay_id
                    LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
                    LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
                    WHERE p.partner_category = 'stay'
                    AND p.partner_status = 'enabled'
                    AND p.partner_idx IN ($placeholders)
                    AND price_data.lowest_price IS NOT NULL
                    GROUP BY p.partner_idx
                    ORDER BY RAND();
                ";

                $partnerData = Database::getInstance()->getConnection()->select($sql, $partnerIds);
                $redis->setex($redisKey, $cacheTTL, json_encode($partnerData));
            }

            $groupedPartnerData[$listType] = $partnerData;
        }

        $popularity = null;

        $popularityIdxArray = MainViewList::where('list_type', 'realtime_popularity')
            ->orderBy('list_order', 'ASC')
            ->pluck('partner_idx')
            ->toArray();

        if (!empty($popularityIdxArray)) {
            $bindingsPlaceholders = implode(',', array_fill(0, count($popularityIdxArray), '?'));

            $partnerIdsHash = md5(json_encode($popularityIdxArray));
            $redisKey = "partner_data:realtime_popularity:{$partnerIdsHash}";
            $cacheTTL = 3600;

            $popularity = $redis->get($redisKey);
            $popularity = json_decode($popularity);

            if (empty($popularity)) {
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
                    FROM partners p
                    LEFT JOIN moongcleoffers mo ON p.partner_idx = mo.partner_idx
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
                        AND p.partner_idx IN ($bindingsPlaceholders)
                    GROUP BY p.partner_idx;
                ";

                // $bindings = array_merge($matchedIds, $matchedIds);
                $popularity = Database::getInstance()->getConnection()->select($sql, $popularityIdxArray);
                $redis->setex($redisKey, $cacheTTL, json_encode($popularity));
            }
        }

        foreach ($popularity as $key => $value) {
            $count = StayMoongcleOffer::where('partner_idx', $value->partner_idx)
                ->where('stay_moongcleoffer_status', 'enabled')
                ->count();

            $popularity[$key]->moongcleoffer_count = $count;

            if ($count > 0) {
                $stayMoongcleoffer = StayMoongcleOffer::where('partner_idx', $value->partner_idx)
                    ->where('stay_moongcleoffer_status', 'enabled')
                    ->orderBy('sale_end_date', 'ASC')
                    ->get();

                $popularity[$key]->sale_end_date = $stayMoongcleoffer[0]->sale_end_date;

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

                $popularity[$key]->benefits = $benefits;
            } else {
                $popularity[$key]->sale_end_date = null;
            }
        }

        $recommendStays = [];
        $recommendStayTags = [
            [
                [
                    'tag_name' => '호텔',
                    'tag_machine_name' => 'hotel',
                ],
                [
                    'tag_name' => '리조트',
                    'tag_machine_name' => 'resort',
                ]
            ],
            [
                [
                    'tag_name' => '풀빌라',
                    'tag_machine_name' => 'private_pool_villa',
                ],
                [
                    'tag_name' => '펜션',
                    'tag_machine_name' => 'pension',
                ]
            ],
            [
                [
                    'tag_name' => '감성 넘치는 분위기',
                    'tag_machine_name' => 'emotional_vibe',
                ],
                [
                    'tag_name' => '감성 숙소',
                    'tag_machine_name' => 'emotional_accommodation',
                ]
            ],
            [
                [
                    'tag_name' => '글램핑',
                    'tag_machine_name' => 'glamping',
                ],
                [
                    'tag_name' => '캠핑',
                    'tag_machine_name' => 'camping',
                ]
            ],
            [
                [
                    'tag_name' => '한옥',
                    'tag_machine_name' => 'hanok_traditional_house',
                ],
                [
                    'tag_name' => '독채형',
                    'tag_machine_name' => 'private_house_type',
                ]
            ],
            [
                [
                    'tag_name' => '데이트장소 추천',
                    'tag_machine_name' => 'romantic_spot_recommendation',
                ],
                [
                    'tag_name' => '로맨틱 분위기',
                    'tag_machine_name' => 'romantic_atmosphere',
                ],
                [
                    'tag_name' => '커플기념일',
                    'tag_machine_name' => 'couple_anniversary',
                ]
            ],
            [
                [
                    'tag_name' => '키즈펜션',
                    'tag_machine_name' => 'kids_friendly_pension',
                ],
                [
                    'tag_name' => '아이와 갈만한 곳',
                    'tag_machine_name' => 'places_to_visit_with_kids',
                ],
            ],
            [
                [
                    'tag_name' => '반려동물 동반가능',
                    'tag_machine_name' => 'pet_friendly',
                ],
                [
                    'tag_name' => '애견펜션',
                    'tag_machine_name' => 'pet_friendly_pension',
                ],
            ],
        ];

        foreach ($recommendStayTags as $key => $recommendStayTag) {
            $recommendStays[$key] = [];
            $recommendStays[$key]['tags'] = $recommendStayTag;
            $recommendStays[$key]['encoded_tags'] = base64_encode(json_encode($recommendStayTag));
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'isGuest' => $isGuest,
            'unreadMoocledealCount' => $unreadMoocledealCount,
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
            'reviews' => $reviews,
            'groupedPartnerData' => $groupedPartnerData,
            'mainMoongcledeal' => $mainMoongcledeal,
            'mainMoongcledealOffers' => $mainMoongcledealOffers,
            'popularity' => $popularity,
            'recommendStays' => $recommendStays
        );

        self::render('main', ['data' => $data]);
    }

    public static function wrongApproach()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType
        );

        self::render('wrong-approach', ['data' => $data]);
    }

    public static function onboarding()
    {
        $deviceType = getDeviceType();

        $data = array(
            'deviceType' => $deviceType
        );

        self::render('onboarding', ['data' => $data]);
    }

    // public static function test()
    // {
    //     $deviceType = getDeviceType();

    //     MiddleHelper::checkUserAction();
    //     $user = MiddleHelper::checkLoginCookie();
    //     $isGuest = true;
    //     $unreadMoocledealCount = 0;
    //     $moongcledeals = null;
    //     $mainMoongcledeal = null;
    //     $mainMoongcledealOffers = null;

    //     if ($user) {
    //         if ($user->user_is_guest == false) {
    //             $isGuest = false;
    //         }

    //         $unreadMoocledealCount = Notification::where('user_idx', $user->user_idx ?: $user->guest_idx)
    //             ->where('is_read', false)
    //             ->where('notification_type', 'moongcledeal')
    //             ->distinct()
    //             ->count(['base_idx', 'target_idx']);

    //         $bindings = [
    //             'userIdx' => $user->user_idx ?: $user->guest_idx
    //         ];

    //         $sql = "
    //             SELECT 
    //                 m.*
    //             FROM moongcletrip.moongcledeals m
    //             WHERE m.user_idx = :userIdx
    //                 AND m.status = 'matched'
    //             ORDER BY m.represent DESC, m.moongcledeal_idx DESC;
    //         ";

    //         $moongcledeals = Database::getInstance()->getConnection()->select($sql, $bindings);

    //         if (!empty($moongcledeals[0])) {
    //             $mainMoongcledeal = $moongcledeals[0];

    //             $moongcleMatches = MoongcleMatch::where('moongcledeal_idx', $mainMoongcledeal->moongcledeal_idx)
    //                 ->whereIn('notification_status', ['sent', 'token_x'])
    //                 ->orderBy('notification_time', 'DESC')
    //                 ->get();

    //             $matchedIds = $moongcleMatches->pluck('product_idx')->toArray();

    //             if (!empty($matchedIds)) {
    //                 $bindingsPlaceholders = implode(',', array_fill(0, count($matchedIds), '?'));

    //                 $sql = "
    //                     SELECT
    //                         mo.*,
    //                         p.partner_idx,
    //                         p.partner_name,
    //                         p.partner_address1,
    //                         r.room_status,
    //                         (
    //                             SELECT mp.moongcleoffer_price_sale
    //                             FROM moongcletrip.moongcleoffer_prices mp
    //                             WHERE mp.moongcleoffer_idx = mo.moongcleoffer_idx
    //                             AND mp.moongcleoffer_price_sale > 0
    //                             ORDER BY mp.moongcleoffer_price_sale ASC
    //                             LIMIT 1
    //                         ) AS lowest_price,
    //                         (
    //                             SELECT mp.moongcleoffer_price_basic
    //                             FROM moongcletrip.moongcleoffer_prices mp
    //                             WHERE mp.moongcleoffer_idx = mo.moongcleoffer_idx
    //                             AND mp.moongcleoffer_price_sale = (
    //                                 SELECT MIN(mp_inner.moongcleoffer_price_sale)
    //                                 FROM moongcletrip.moongcleoffer_prices mp_inner
    //                                 WHERE mp_inner.moongcleoffer_idx = mo.moongcleoffer_idx
    //                                     AND mp_inner.moongcleoffer_price_sale > 0
    //                             )
    //                             LIMIT 1
    //                         ) AS basic_price,
    //                         (
    //                             SELECT img.image_normal_path
    //                             FROM moongcletrip.images img
    //                             WHERE img.image_entity_id = p.partner_detail_idx
    //                             AND img.image_entity_type = 'stay' AND img.image_type = 'basic'
    //                             ORDER BY img.image_order ASC
    //                             LIMIT 1
    //                         ) AS image_normal_path,
    //                         GROUP_CONCAT(DISTINCT tc.tag_name ORDER BY tc.tag_name ASC SEPARATOR ':-:') AS tags,
    //                         GROUP_CONCAT(DISTINCT tc1.tag_name ORDER BY tc1.tag_name ASC SEPARATOR ':-:') AS types,
    //                         (SELECT 
    //                             JSON_ARRAYAGG(JSON_OBJECT('tag_name', ct.tag_name, 'tag_machine_name', ct.tag_machine_name))
    //                         FROM curated_tags ct 
    //                         WHERE ct.item_idx = mo.moongcleoffer_idx AND ct.item_type = 'moongcleoffer') AS curated_tags,
    //                         (SELECT 
    //                             JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                         FROM moongcletrip.benefit_item bi 
    //                         WHERE bi.item_idx = r.room_idx AND bi.item_type = 'room') AS room_benefits,
    //                         (SELECT 
    //                             JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                         FROM moongcletrip.benefit_item bi 
    //                         WHERE bi.item_idx = rr.rateplan_idx AND bi.item_type = 'rateplan') AS rateplan_benefits,
    //                         (SELECT 
    //                             JSON_ARRAYAGG(JSON_OBJECT('benefit_name', bi.benefit_name))
    //                         FROM moongcletrip.benefit_item bi 
    //                         WHERE bi.item_idx = mo.moongcleoffer_idx AND bi.item_type = 'moongcleoffer') AS moongcleoffer_benefits
    //                     FROM moongcleoffers mo
    //                     LEFT JOIN partners p ON p.partner_idx = mo.partner_idx
    //                     LEFT JOIN room_rateplan rr ON mo.base_product_idx = rr.room_rateplan_idx
    //                     LEFT JOIN rooms r ON rr.room_idx = r.room_idx 
    //                     LEFT JOIN room_inventories i ON r.room_idx = i.room_idx AND rr.rateplan_idx = i.rateplan_idx
    //                     LEFT JOIN moongcleoffer_prices mp ON mo.moongcleoffer_idx = mp.moongcleoffer_idx AND i.inventory_date = mp.moongcleoffer_price_date
    //                     LEFT JOIN tag_connections tc ON p.partner_detail_idx = tc.item_idx AND tc.item_type = 'stay' AND tc.connection_type = 'stay_type_detail'
    //                     LEFT JOIN tag_connections tc1 ON p.partner_detail_idx = tc1.item_idx AND tc1.item_type = 'stay' AND tc1.connection_type = 'stay_type'
    //                     WHERE mo.moongcleoffer_status = 'enabled'
    //                         AND mo.moongcleoffer_category = 'roomRateplan'
    //                         AND p.partner_status = 'enabled'
    //                         AND rr.room_rateplan_status = 'enabled'
    //                         AND mo.moongcleoffer_idx IN ($bindingsPlaceholders)
    //                     GROUP BY mo.moongcleoffer_idx
    //                     ORDER BY FIELD(mo.moongcleoffer_idx, $bindingsPlaceholders);
    //                 ";

    //                 $bindings = array_merge($matchedIds, $matchedIds);
    //                 $mainMoongcledealOffers = Database::getInstance()->getConnection()->select($sql, $bindings);
    //             }
    //         }
    //     }

    //     $tagService = new TagService();
    //     $mainTags = $tagService->getHomeMainTags();

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
    //         WHERE r.is_active = true
    //             AND (r.review_idx = 200 OR r.review_idx = 14)
    //         GROUP BY r.review_idx
    //         ORDER BY r.created_at DESC;
    //     ";

    //     $reviewsV1 = Database::getInstance()->getConnection()->select($sql);

    //     foreach ($reviewsV1 as &$review) {
    //         if (!empty($review->tag_list)) {
    //             $tags = json_decode($review->tag_list, true);
    //             $review->encoded_tags = base64_encode(json_encode($tags));
    //         } else {
    //             $review->encoded_tags = null;
    //         }
    //     }

    //     $recommendStays = [];
    //     $recommendStayTags = [
    //         [
    //             [
    //                 'tag_name' => '호텔',
    //                 'tag_machine_name' => 'hotel',
    //             ],
    //             [
    //                 'tag_name' => '리조트',
    //                 'tag_machine_name' => 'resort',
    //             ]
    //         ],
    //         [
    //             [
    //                 'tag_name' => '풀빌라',
    //                 'tag_machine_name' => 'private_pool_villa',
    //             ],
    //             [
    //                 'tag_name' => '펜션',
    //                 'tag_machine_name' => 'pension',
    //             ]
    //         ],
    //         [
    //             [
    //                 'tag_name' => '감성 넘치는 분위기',
    //                 'tag_machine_name' => 'emotional_vibe',
    //             ],
    //             [
    //                 'tag_name' => '감성 숙소',
    //                 'tag_machine_name' => 'emotional_accommodation',
    //             ]
    //         ],
    //         [
    //             [
    //                 'tag_name' => '글램핑',
    //                 'tag_machine_name' => 'glamping',
    //             ],
    //             [
    //                 'tag_name' => '캠핑',
    //                 'tag_machine_name' => 'camping',
    //             ]
    //         ],
    //         [
    //             [
    //                 'tag_name' => '한옥',
    //                 'tag_machine_name' => 'hanok_traditional_house',
    //             ],
    //             [
    //                 'tag_name' => '독채형',
    //                 'tag_machine_name' => 'private_house_type',
    //             ]
    //         ],
    //         [
    //             [
    //                 'tag_name' => '데이트장소 추천',
    //                 'tag_machine_name' => 'romantic_spot_recommendation',
    //             ],
    //             [
    //                 'tag_name' => '로맨틱 분위기',
    //                 'tag_machine_name' => 'romantic_atmosphere',
    //             ],
    //             [
    //                 'tag_name' => '커플기념일',
    //                 'tag_machine_name' => 'couple_anniversary',
    //             ]
    //         ],
    //         [
    //             [
    //                 'tag_name' => '키즈펜션',
    //                 'tag_machine_name' => 'kids_friendly_pension',
    //             ],
    //             [
    //                 'tag_name' => '아이와 갈만한 곳',
    //                 'tag_machine_name' => 'places_to_visit_with_kids',
    //             ],
    //         ],
    //         [
    //             [
    //                 'tag_name' => '반려동물 동반가능',
    //                 'tag_machine_name' => 'pet_friendly',
    //             ],
    //             [
    //                 'tag_name' => '애견펜션',
    //                 'tag_machine_name' => 'pet_friendly_pension',
    //             ],
    //         ],
    //     ];

    //     foreach ($recommendStayTags as $key => $recommendStayTag) {
    //         $recommendStays[$key] = [];
    //         $recommendStays[$key]['tags'] = $recommendStayTag;
    //         $recommendStays[$key]['encoded_tags'] = base64_encode(json_encode($recommendStayTag));
    //     }

    //     // $hotStayMoongcleofferIdx = [
    //     //     810,
    //     //     203,
    //     //     7359,
    //     //     663,
    //     //     136,
    //     //     5968,
    //     //     6352,
    //     //     5470,
    //     //     9835,
    //     //     7232,
    //     //     947,
    //     //     2123,
    //     //     1072,
    //     //     4630,
    //     //     5476,
    //     //     4751,
    //     //     3650,
    //     //     2935,
    //     //     567,
    //     //     1608
    //     // ];

    //     $hoStayPartnerIdx = [14385, 14366, 14462, 14407, 14249, 14200, 14203, 11813, 10098, 10404];

    //     $randomIndexes = array_rand($hoStayPartnerIdx, 10);

    //     $selectedIds = array_map(function ($index) use ($hoStayPartnerIdx) {
    //         return $hoStayPartnerIdx[$index];
    //     }, $randomIndexes);

    //     $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));

    //     $sql = "
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
    //             GROUP BY r.partner_idx
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
    //             p.partner_idx,
    //             p.partner_name,
    //             p.partner_address1,
    //             p.average_discount,
    //             price_data.lowest_price,
    //             price_data.basic_price,
    //             id.image_normal_path
    //         FROM moongcletrip.partners p
    //         LEFT JOIN price_data ON p.partner_idx = price_data.partner_idx
    //         LEFT JOIN image_data id ON p.partner_detail_idx = id.stay_id
    //         WHERE p.partner_category = 'stay'
    //         AND p.partner_status = 'enabled'
    //         AND p.partner_idx IN ($placeholders)
    //         AND price_data.lowest_price IS NOT NULL
    //         ORDER BY RAND();
    //     ";

    //     $hotStays = Database::getInstance()->getConnection()->select($sql, $selectedIds);

    //     foreach ($hotStays as &$hotStay) {
    //         if ($hotStay->lowest_price >= 10000) {
    //             $result = round($hotStay->lowest_price / 10000, 1) . '만';
    //         } elseif ($hotStay->lowest_price >= 1000) {
    //             $result = round($hotStay->lowest_price / 1000, 1) . '천';
    //         } else {
    //             $result = intval($hotStay->lowest_price);
    //         }

    //         $hotStay->lowest_price_korean = $result;
    //     }

    //     $data = array(
    //         'deviceType' => $deviceType,
    //         'user' => $user,
    //         'isGuest' => $isGuest,
    //         'mainTags' => $mainTags,
    //         'reviewsV1' => $reviewsV1,
    //         'unreadMoocledealCount' => $unreadMoocledealCount,
    //         'recommendStays' => $recommendStays,
    //         'hotStays' => $hotStays,
    //         'mainMoongcledeal' => $mainMoongcledeal,
    //         'mainMoongcledealOffers' => $mainMoongcledealOffers
    //     );

    //     self::render('main-old', ['data' => $data]);
    // }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
