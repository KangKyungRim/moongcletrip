<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Collection as Collection;

use Database;

use App\Models\Curation;
use App\Models\CurationItem;
use App\Models\StayMoongcleOffer;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ImageUploadAwsService;

use Carbon\Carbon;

/**
 * APP > 큐레이션 API
 */
class CurationApiController {
    /**
     * 큐레이션 목록 API (페이징 > 1)
     */
    public static function getCurations() {
        $perPageRow = 10;

        $page = max((int)($_GET['page'] ?? 2), 1);
        $size = min(max((int)($_GET['size'] ?? $perPageRow), 1), 100);
        $offset = ($page - 1) * $size;

        //총 개수
        //$today = Carbon::now();
        $today = date('Y-m-d');

        //조건
        $base = Curation::query()
            ->where('is_active', 1) //노출
            ->where(function($q) use ($today) {
                $q->whereNull('curation_visible_from')
                ->orWhereDate('curation_visible_from', '<=', $today);
            })
            ->where(function($q) use ($today) {
                $q->whereNull('curation_visible_to')
                ->orWhereDate('curation_visible_to', '>=', $today);
            })->whereHAs('curationItems', function($q) {
                $q->where('is_active', 1);
            });

        //total : 총개수
        $total = (clone $base)->count();
        //큐레이션 목록
        //$imageService = new ImageUploadAwsService();
        // /$cdnUrl = $imageService->getCloudFrontUrl($key)
        $curations = (clone $base)->select('curation_idx', 'curation_title', 'curation_description')
            ->orderBy('curation_order', 'ASC')
            ->orderBy('curation_idx', 'DESC')
            ->offset($offset)
            ->limit($size)
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
        $curations->each(function ($c) use ($imageService) {
            $c->curationItems->each(function ($item) use ($imageService) {
                $item->target_thumbnail_url = $item->target_thumbnail_path
                    ? $imageService->getCloudFrontUrl($item->target_thumbnail_path)
                    : null;
            });
        });

        return ResponseHelper::jsonResponse([
				'header'    => [
                    'success' => true,
                    'message' => '조회에 성공했습니다.',    
                ],
				'body' => $curations,
                'page' => [
                    'pageRows' => $curations->count(),
                    'currPage' => $page,
                    'totalRows' => $total
                ]
			], 200);
    }

    /**
     * 큐레이션 상세 조회
     */
    public static function getCuration($curationIdx) {

        $userIdx = null;
        if (!empty($_SESSION['user_idx'])) {
            $userIdx = (int) $_SESSION['user_idx'];
        } elseif ($u = MiddleHelper::checkLoginCookie()) {
            $userIdx = (int) $u->user_idx;
        }
        //error_log('userIdx:'.$userIdx);

        //큐레이션
        //$curation = Curation::select('curation_idx', 'curation_title', 'curation_description','curation_visible_from','curation_visible_to','is_active')
        //    ->find($curationIdx);
        $curation = Capsule::table('curations')
            ->select('curation_idx','curation_title','curation_description','curation_visible_from','curation_visible_to','is_active')
            ->where('curation_idx', $curationIdx)
            ->first();
        
        if(!$curation) {
            return ResponseHelper::jsonResponse2([
                    'header'    => [
                        'success' => false,
                        'message' => '해당 큐레이션을 찾을 수 없습니다.',    
                    ],
                    'body' => []
                ], 404);
        }

        //큐레이션 상세
        $curationItemsSql = "
            SELECT
                ci.curation_item_idx,
                ci.curation_idx,
                ci.target_idx,
                ci.target_description,
                ci.target_thumbnail_path,
                ci.target_tags,
                p.partner_idx,
                p.partner_detail_idx,
                p.image_curated,
                p.partner_name   AS target_name,
                p.partner_region AS target_region,
                p.partner_city   AS target_city,
                p.partner_address1 as target_address1
            FROM curation_items ci
            LEFT JOIN partners p ON ci.target_type = 'partner' AND p.partner_idx   = ci.target_idx -- 숙소
            WHERE 
                ci.curation_idx = :curationIdx
                AND ci.is_active    = 1 -- 노출중
            ORDER BY ci.curation_item_order ASC, ci.curation_item_idx DESC
        ";
        $curationItemsParam = [
            'curationIdx' => (int)$curationIdx
        ];
        //$curationItems = Database::getInstance()->getConnection()->select($curationItemsSql, $curationItemsParam);
        $curationItems = Capsule::select($curationItemsSql, $curationItemsParam);

        if(!$curationItems) {
            return ResponseHelper::jsonResponse2([
                    'header'    => [
                        'success' => false,
                        'message' => '해당 큐레이션을 찾을 수 없습니다.',    
                    ],
                    'body' => []
                ], 404);
        }

        // 파트너 partner_idx 가져오기
        $items = Collection::make($curationItems);
        $partnerIds = $items->pluck('partner_idx')->toArray();

        // ㄴ 숙소 가격
        $from = date('Y-m-d');
        $to   = date('Y-m-d', strtotime('+30 days'));
        $ph2 = implode(',', array_fill(0, count($partnerIds), '?'));
        $priceSql = "
            WITH rp_candidates AS (
                SELECT
                    r.partner_idx,
                    rp.room_price_date   AS price_date,
                    rp.room_price_sale   AS price_sale,
                    rp.room_price_basic  AS price_basic
                FROM rooms r
                JOIN room_rateplan rr ON rr.room_idx = r.room_idx AND rr.room_rateplan_status = 'enabled'
                JOIN room_inventories i	ON i.room_rateplan_idx = rr.room_rateplan_idx
                JOIN room_prices rp ON rp.room_rateplan_idx = rr.room_rateplan_idx AND rp.room_price_date   = i.inventory_date
                WHERE r.partner_idx IN ($ph2)
                    AND r.room_status = 'enabled'
                    AND i.inventory_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    AND i.inventory_quantity  > 0
                    AND i.is_closed           = 0
                    AND rp.is_closed          = 0
                    AND rp.room_price_sale    > 0
            ),
            mo_candidates AS (
                SELECT
                    r.partner_idx,
                    mp.moongcleoffer_price_date AS price_date,
                    mp.moongcleoffer_price_sale AS price_sale,
                    mp.moongcleoffer_price_basic  as price_basic
                FROM moongcleoffers mo
                JOIN room_rateplan rr ON rr.room_rateplan_idx = mo.base_product_idx AND rr.room_rateplan_status  = 'enabled'
                JOIN rooms r ON r.room_idx = rr.room_idx AND r.room_status = 'enabled'
                JOIN moongcleoffer_prices mp ON mp.moongcleoffer_idx = mo.moongcleoffer_idx
                JOIN room_inventories i ON i.room_rateplan_idx = rr.room_rateplan_idx AND i.inventory_date = mp.moongcleoffer_price_date
                WHERE mo.moongcleoffer_status = 'enabled'
                    AND mo.moongcleoffer_category = 'roomRateplan'
                    AND r.partner_idx IN ($ph2)
                    AND mp.moongcleoffer_price_sale > 0
                    AND mp.moongcleoffer_price_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                    AND i.inventory_quantity  > 0
                    AND i.is_closed           = 0
            ),
            all_prices AS (
                SELECT * FROM rp_candidates
                UNION ALL
                SELECT * FROM mo_candidates
            )
            SELECT 
                partner_idx,
                price_sale  AS min_price_sale,
                price_basic AS min_price_basic,
                price_date  AS min_price_date
            FROM (
                SELECT ap.*,
                    ROW_NUMBER() OVER (
                        PARTITION BY partner_idx
                        ORDER BY price_sale ASC, price_date ASC
                    ) AS rn
                FROM all_prices ap
            ) x
            WHERE rn = 1
        ";
        $priceRows = Capsule::select($priceSql, array_merge($partnerIds, $partnerIds));
        $priceByPartner = [];
        foreach ($priceRows as $r) {
            $priceByPartner[(int)$r->partner_idx] = $r;
        }

        // ㄴ 숙소 찜
        if ($userIdx) {
            $ph = implode(',', array_fill(0, count($partnerIds), '?'));
            $sqlFav = "
                SELECT partner_idx, MIN(favorite_idx) AS favorite_idx
                FROM partner_favorites
                WHERE user_idx = ? AND target='partner' AND partner_idx IN ($ph)
                GROUP BY partner_idx
            ";
            //$favRows = Database::getInstance()->getConnection()->select($sqlFav, array_merge([$userIdx], $partnerIds));
            $favRows = Capsule::select($sqlFav, array_merge([$userIdx], $partnerIds));
            foreach ($favRows as $r) $favSet[(int)$r->partner_idx] = (int)$r->favorite_idx; // partner_idx => favorite_idx
        }


        // ㄴ 큐레이션아이템 이미지 or 큐레이트 이미지 가져오기
        //    -> partner의 partner_detail_idx
        $curatedIds = $items->where('image_curated', 1)->pluck('partner_detail_idx')->toArray();
        $normalIds = $items->where('image_curated', 0)->pluck('partner_detail_idx')->toArray();
        $imagesByEntity = [];

        // ㄴ 큐레이트 이미지
        if (!empty($curatedIds)) {
            $ph  = implode(',', array_fill(0, count($curatedIds), '?'));
            $sql = "
                SELECT image_entity_id, image_big_path
                FROM curated_images
                WHERE image_entity_type='stay' AND image_type='basic'
                    AND image_entity_id IN ($ph)
                ORDER BY image_entity_id, image_order, image_idx
            ";
            //$rows = Database::getInstance()->getConnection()->select($sql, $curatedIds);
            $rows = Capsule::select($sql, $curatedIds);
            $cnt = [];
            foreach ($rows as $r) {
                $eid = $r->image_entity_id;
                $imagesByEntity[$eid][] = $r->image_big_path;
            }
        }

        // ㄴ 이미지
        if (!empty($normalIds)) {
            $ph  = implode(',', array_fill(0, count($normalIds), '?'));
            $sql = "
                SELECT image_entity_id, image_big_path
                FROM images
                WHERE image_entity_type='stay' AND image_type='basic'
                    AND image_entity_id IN ($ph)
                ORDER BY image_entity_id, image_order, image_idx
            ";
            //$rows = Database::getInstance()->getConnection()->select($sql, $normalIds);
            $rows = Capsule::select($sql, $normalIds);
            $cnt = [];
            foreach ($rows as $r) {
                $eid = (int)$r->image_entity_id;
                $imagesByEntity[$eid][] = $r->image_big_path;                
            }
        }

        // ㄴ 진행중인 뭉클오퍼
        $aggRows = collect();
        $aggRows = StayMoongcleOffer::select(
                'partner_idx',
                Capsule::raw('COUNT(*) AS moongcleoffer_count'),
                Capsule::raw('MIN(sale_end_date) AS sale_end_date')
            )
            ->where('stay_moongcleoffer_status', 'enabled')
            ->whereIn('partner_idx', $partnerIds)
            ->where(function ($q) {
                // 마감일이 없거나 미래인 것만
                $q->whereNull('sale_end_date')
                ->orWhere('sale_end_date', '>=', Capsule::raw('NOW()'));
            })
            ->groupBy('partner_idx')
            ->get()
            ->keyBy('partner_idx');

        //error_log('실시간 뭉클딜 aggRows'.print_r($aggRows, true));
        

        // ㄴ 숙소 가격
        // ㄴ 숙소 찜
        // ㄴ 큐레이션아이템 썸네일 CloudFront URL 변환(추가 필드 target_thumbnail_url)
        // ㄴ 숙소 image or curated_image 추가
        // ㄴ 큐레이션아이템 태그 (문자열->배열)
        // ㄴ 뱃지정보 : 실시간뭉클딜 및 뭉클딜
        $imageService = new ImageUploadAwsService();
        foreach ($curationItems as $item) {
            //숙소 가격
            $item->priceInfo = $priceByPartner[$item->partner_idx] ?? null;

            //숙소 찜
            $fid = $favSet[$item->target_idx] ?? null;
            $item->is_favorited = $fid ? 1 : 0;
            $item->favorite_idx = $fid;
            //썸네일
            $item->target_thumbnail_url = $item->target_thumbnail_path ? $imageService->getCloudFrontUrl($item->target_thumbnail_path) : null;
            //숙소 이미지
            $item->images = $imagesByEntity[$item->partner_detail_idx] ?? [] ;
            //큐레이션아이템 태그
            if (is_string($item->target_tags)) {
                $decoded = json_decode($item->target_tags, true);
                $item->target_tags = is_array($decoded) ? $decoded : [];
            }
            //실시간뭉클딜 뱃지
            $stat = $aggRows->get($item->target_idx);
            $realtime_popularity = [
                'moongcleoffer_count'           => $stat ? (int)$stat->moongcleoffer_count : 0,                 //진행중인 뭉클오퍼 갯수
                'moongcleoffer_sale_end_date'   => $stat ? $stat->sale_end_date : null,                         //진행중인 뭉클오퍼 판매종료기간
            ];
            $item->badge_info = $realtime_popularity;
        }

        return ResponseHelper::jsonResponse2([
				'header'    => [
                    'success' => true,
                    'message' => '조회에 성공했습니다.',    
                ],
				'body' => [
                    'curation' => $curation,
                    'curationItems' => $curationItems
                ]
			], 200);
    }
}
