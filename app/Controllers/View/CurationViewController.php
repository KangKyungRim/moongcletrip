<?php

namespace App\Controllers\View;

use App\Models\Notification;
use App\Models\Curation;
use App\Models\CurationItem;
use App\Models\Banner;

use App\Helpers\MiddleHelper;
use App\Helpers\ImageUploadAwsService;


use Database;

/**
 * APP > 큐레이션(홈)
 */
class CurationViewController {
    
    /**
     * 큐레이션 목록 (홈)
     * 히어로배너, 큐레이션, 배너, 뭉클맘리뷰
     */
    public static function curations() {
        $perPageRow = 10;

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

        //1. 큐레이션 목록
        //한페이지에 10개 노출이지만, 3개 + 배너, 뭉클맘 후기 + 7개 노출
        //상시 또는 기간
        $curationsAll = Curation::select('curation_idx', 'curation_title', 'curation_description')
            ->where('is_active',1)      //노출
            ->where(function($q) {
                $today = date('Y-m-d');
                $q->whereNull('curation_visible_from')->orWhere('curation_visible_from', '<=', $today);
            })
            ->where(function($q) {
                $today = date('Y-m-d');
                $q->whereNull('curation_visible_to')->orWhere('curation_visible_to', '>', $today);
            })
            ->whereHAs('curationItems', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('curation_order', 'ASC')
            ->orderBy('curation_idx', 'DESC')
            ->limit($perPageRow)
            ->with(['curationItems' => function($q) {
                //큐레이션 아이템
                $q->select(
                    'curation_items.curation_item_idx', 
                    'curation_items.curation_idx', 
                    'curation_items.target_type', 
                    'curation_items.target_idx',
                    'curation_items.target_description',
                    'curation_items.target_thumbnail_path',
                    'curation_items.target_tags',
                    // partners 테이블에서 partner_name 추가
                    'partners.partner_name as target_name'
                )// partners 테이블을 LEFT JOIN
                    ->leftJoin('partners', function ($join) {
                        $join->on('curation_items.target_idx', '=', 'partners.partner_idx')
                            ->where('curation_items.target_type', '=', 'partner');
                    })
                    ->where('is_active',1)                      //노출
                    ->orderBy('curation_item_order', 'ASC')
                    ->orderBy('curation_item_idx', 'DESC');
            }])
            ->get();

        // ✅ 썸네일 CloudFront URL 변환(추가 필드 target_thumbnail_url)
        $imageService = new ImageUploadAwsService();
        $curationsAll->each(function ($c) use ($imageService) {
            $c->curationItems->each(function ($item) use ($imageService) {
                $item->target_thumbnail_url = $item->target_thumbnail_path
                    ? $imageService->getCloudFrontUrl($item->target_thumbnail_path)
                    : null;
            });
        });

        $topCount = 3;
        $top3     = $curationsAll->slice(0, $topCount)->values();
        $bottom7  = $curationsAll->slice($topCount, 7)->values(); // 남은 개수만큼

        //2.뭉클맘 리뷰
        $reviewSql = "
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
            LIMIT 15;
        ";

        $reviews = Database::getInstance()->getConnection()->select($reviewSql);

        foreach ($reviews as &$review) {
            if (!empty($review->tag_list)) {
                $tag = json_decode($review->tag_list, true);
                $review->encoded_tags = base64_encode(json_encode($tag));
            } else {
                $review->encoded_tags = null;
            }
        }

        //3.히어로배너
        $heroBanners = Banner::select('banner_idx', 'banner_link_type', 'banner_type', 'banner_link_url','banner_image_path')
            ->where('is_active',1)      //노출
            ->where('banner_type',0)    //히어로배너
            ->orderBy('banner_order', 'ASC')
            ->orderBy('banner_idx', 'DESC')
            ->get();

        //4.띠배너
        $ribbonBanners = Banner::select('banner_idx', 'banner_link_type', 'banner_type', 'banner_link_url','banner_image_path')
            ->where('is_active',1)      //노출
            ->where('banner_type',1)    //띠배너
            ->orderBy('banner_order', 'ASC')
            ->orderBy('banner_idx', 'DESC')
            ->get();


        $data = array(
            'deviceType'            => $deviceType,
            'user'                  => $user,
            'isGuest'               => $isGuest,
            'unreadMoocledealCount' => $unreadMoocledealCount,
            'topCurations'          => $top3,       //큐레이션 상위 3개
            'buttomCurations'       => $bottom7,    //큐레이션 하위 7개
            'reviews'               => $reviews,    //뭉클맘 리얼 후기
            'heroBanners'           => $heroBanners,    //히어로배너
            'ribbonBanners'         => $ribbonBanners  //띠배너
        );

        self::render('curations', ['data' => $data]);
    }

    /**
     * 큐레이션 상세
     */
    public static function curationDetail($curationIdx) {
        $deviceType = getDeviceType();
        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        //큐레이션
        $curation = Curation::select('curation_title')
            ->find($curationIdx);

        $data = array(
            'deviceType'            => $deviceType,
            'user'                  => $user,
            'isGuest'               => $isGuest,
            'page_title_01'         => $curation->curation_title
        );

        self::render('curationDetail', ['data' => $data]);
    }

    private static function render($view, $data = []) {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}