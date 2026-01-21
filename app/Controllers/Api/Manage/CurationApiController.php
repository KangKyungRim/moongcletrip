<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Curation;
use App\Models\CurationItem;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ImageUploadAwsService;

use Carbon\Carbon;

use Database;

/**
 * 큐레이션 관리
 */
class CurationApiController
{
    /**
     * 큐레이션 목록 조회
     */
    public static function getCurations() {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();

		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '403 Unauthorized',    
                ]
            ], 403);
		}

        $input = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
        $page = max((int)($input['page'] ?? 1), 1);
        $size = min(max((int)($input['size'] ?? 20), 1), 100);
        $offset = ($page - 1) * $size;


        $sort = $input['sort'] ?? 'curation_order,asc';
        // "컬럼,정렬" 분리
        list($column, $direction) = array_pad(explode(',', $sort), 2, null);
        // 허용 컬럼 목록 정의
        $allowedColumns = ['created_at', 'updated_at', 'curation_title', 'curation_idx', 'curation_order'];
        // 검증
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'curation_order';
        }
        $direction = strtolower($direction);
        if (!in_array($direction, ['asc','desc'], true)) {
            $direction = 'asc';
        }
        // 최종 정렬문
        $orderBy = $column . ' ' . $direction;


        //총 개수
        $total = Curation::query()->count();
        //큐레이션 목록
        $curations = Curation::query()
            ->with(['curationItems' => function($q) {
                //큐레이션 아이템
                $q->select(
                    'curation_items.curation_item_idx', 
                    'curation_items.curation_idx', 
                    'curation_items.target_type', 
                    'curation_items.target_idx',
                    'partners.partner_name as target_name', //파트너 숙소명
                    'curation_items.target_description',
                    'curation_items.target_thumbnail_path',
                    'curation_items.target_tags',
                    'curation_items.curation_item_order',
                    'curation_items.is_active',
                    'curation_items.partner_user_idx',
                    'curation_items.created_at',
                    'curation_items.updated_at',
                    // partners 테이블에서 partner_name 추가
                )// partners 테이블을 LEFT JOIN
                    ->leftJoin('partners', function ($join) {
                        $join->on('curation_items.target_idx', '=', 'partners.partner_idx')
                            ->where('curation_items.target_type', '=', 'partner');
                    })
                    ->orderBy('curation_item_order', 'ASC')
                    ->orderBy('curation_item_idx', 'DESC');
            }])
            ->orderBy($column, $direction)
            ->offset($offset)
            ->limit($size)
            ->get();

        return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => true,
                    'message' => '조회에 성공했습니다.',    
                ],
				'body' => $curations,
                'page' => [
                    'pageRows' => $size,
                    'currPage' => $page,
                    'totalRows' => $total
                ]
			], 200);
    }

    /**
     * 큐레이션 상세 조회
     */
    public static function getCuration($curationIdx) {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();

		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '403 Unauthorized',    
                ]
            ], 403);
		}

        //큐레이션 상세
        $curation = Curation::query()
            ->with(['curationItems' => function($q) {
                //큐레이션 아이템
                $q->select(
                    'curation_items.curation_item_idx', 
                    'curation_items.curation_idx', 
                    'curation_items.target_type', 
                    'curation_items.target_idx',
                    'partners.partner_name as target_name', //파트너 숙소명
                    'curation_items.target_description',
                    'curation_items.target_thumbnail_path',
                    'curation_items.target_tags',
                    'curation_items.curation_item_order',
                    'curation_items.is_active',
                    'curation_items.partner_user_idx',
                    'curation_items.created_at',
                    'curation_items.updated_at',
                    // partners 테이블에서 partner_name 추가
                )// partners 테이블을 LEFT JOIN
                    ->leftJoin('partners', function ($join) {
                        $join->on('curation_items.target_idx', '=', 'partners.partner_idx')
                            ->where('curation_items.target_type', '=', 'partner');
                    })
                    ->orderBy('curation_item_order', 'ASC')
                    ->orderBy('curation_item_idx', 'DESC');
            }])
            ->find($curationIdx);

        return ResponseHelper::jsonResponse2([
            'header'    => [
                'success' => true,
                'message' => '큐레이션 조회에 성공했습니다.',    
            ],
            'body' => $curation
        ], 200);
    }

    /**
     * 큐레이션 비/활성화 수정
     */
    public static function putCurationActive($curationIdx) {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();
        
		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '403 Unauthorized',    
                ]
            ], 403);
		}

        try {

            $curation = Curation::find($curationIdx);
            $curation->is_active        = $curation->is_active ? 0 : 1;
            $curation->partner_user_idx = $checkUser->partner_user_idx;
            $curation->save();

            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => true,
                    'message' => '큐레이션 수정에 성공했습니다.',    
                ],
				'body' => [
                    'curationIdx' => $curation->curation_idx
                ]
			]
            , 200);

        } catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '큐레이션 수정에 실패했습니다.',    
                ],
				'error' => $e->getMessage()
			], 500);
		}
    }

    /**
     * 큐레이션 아이템 비/활성화 수정
     */
    public static function putCurationItemActive($curationIdx, $curationItemIdx) {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();
        
		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '403 Unauthorized',    
                ]
            ], 403);
		}

        try {

            $curationItem = CurationItem::find($curationItemIdx);
            $curationItem->is_active        = $curationItem->is_active ? 0 : 1;
            $curationItem->partner_user_idx = $checkUser->partner_user_idx;
            $curationItem->save();

            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => true,
                    'message' => '큐레이션 아이템 수정에 성공했습니다.',    
                ],
				'body' => [
                    'curationIdx' => $curationItem->curation_idx,
                    'curationItemIdx' => $curationItem->curation_item_idx
                ]
			]
            , 200);

        } catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '큐레이션 아이템 수정에 실패했습니다.',    
                ],
				'error' => $e->getMessage()
			], 500);
		}
    }

    /**
     * 큐레이션 노출순서 수정
     */
    public static function putCurationOrder() {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();
        
		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '403 Unauthorized',    
                ]
            ], 403);
		}

        try {

            $input = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];

            if (!is_array($input) || count($input) < 1) {
                return ResponseHelper::jsonResponse2([
                    'header'    => [
                        'success' => false,
                        'message' => '필수 필드가 유효하지 않습니다.'
                    ],
                    'error' => $errors
                ], 400);

            }    

            $curationIdxs = [];
            $curationOrders = [];

            
            foreach ($input as $i => $item) {
                // --- curationIdx 검증 ---
                // 'required' 규칙
                if (!isset($item['curationIdx'])) {
                    $errors["items.$i.curationIdx"] = '필수 항목입니다.';
                } 
                // 'integer' 규칙
                else if (!is_int($item['curationIdx']) && !ctype_digit($item['curationIdx'])) {
                    $errors["items.$i.curationIdx"] = '정수여야 합니다.';
                } else {
                    $curationIdxs[] = (int)$item['curationIdx'];
                }

                // --- curationOrder 검증 ---
                // 'required' 규칙
                if (!isset($item['curationOrder'])) {
                    $errors["items.$i.curationOrder"] = '필수 항목입니다.';
                }
                // 'integer' 규칙
                else if (!is_int($item['curationOrder']) && !ctype_digit($item['curationOrder'])) {
                    $errors["items.$i.curationOrder"] = '정수여야 합니다.';
                }
                // 'min:1' 규칙
                else if ((int)$item['curationOrder'] < 1) {
                    $errors["items.$i.curationOrder"] = '최소값은 1입니다.';
                } else {
                    $curationOrders[] = (int)$item['curationOrder'];
                }
            }

            // 개별 검증에서 에러가 있었다면 더 진행하지 않음
            if (!empty($errors)) {
                return ResponseHelper::jsonResponse2([
                    'header'    => [
                        'success' => false,
                        'message' => '필수 필드가 유효하지 않습니다.'
                    ],
                    'error' => $errors
                ], 400);
            }

            // order 중복 검증
            if (count($curationOrders) !== count(array_unique($curationOrders))) {
                $errors['curationOrder'] = '순서 값은 중복될 수 없습니다.';
            }

            //curation_idx 검증
            if (!empty($curationIdxs)) {
                $existingCount = Curation::whereIn('curation_idx', $curationIdxs)->count();
                if ($existingCount !== count($curationIdxs)) {
                    $errors['curationIdx'] = '존재하지 않는 큐레이션 IDX가 포함되어 있습니다.';
                }
            }

            if (!empty($errors)) {
                return ResponseHelper::jsonResponse2([
                    'header'    => [
                        'success' => false,
                        'message' => '필수 필드가 유효하지 않습니다.'
                    ],
                    'error' => $errors
                ], 400);
            }

            //db curation_order update
            foreach ($input as $item) {
                Curation::where('curation_idx', $item['curationIdx'])
                        ->update(['curation_order' => $item['curationOrder']]);
            }
            
            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => true,
                    'message' => '큐레이션 순서변경에 성공했습니다.',    
                ]
			]
            , 200);

        } catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '큐레이션 순서변경에 실패했습니다.',    
                ],
				'error' => $e->getMessage()
			], 500);
		}
    }
    
    /**
     * 큐레이션아이템 노출순서 수정
     */
    public static function putCurationItemOrder($curationIdx) {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();
        
		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '403 Unauthorized',    
                ]
            ], 403);
		}

        try {

            $input = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];

            if (!is_array($input) || count($input) < 1) {
                return ResponseHelper::jsonResponse2([
                    'header'    => [
                        'success' => false,
                        'message' => '필수 필드가 유효하지 않습니다.'
                    ],
                    'error' => $errors
                ], 400);

            }    

            $curationItemIdxs = [];
            $curationItemOrders = [];
            
            foreach ($input as $i => $item) {
                // --- curationItemIdx 검증 ---
                // 'required' 규칙
                if (!isset($item['curationItemIdx'])) {
                    $errors["items.$i.curationItemIdx"] = '필수 항목입니다.';
                } 
                // 'integer' 규칙
                else if (!is_int($item['curationItemIdx']) && !ctype_digit($item['curationItemIdx'])) {
                    $errors["items.$i.curationItemIdx"] = '정수여야 합니다.';
                } else {
                    $curationItemIdxs[] = (int)$item['curationItemIdx'];
                }

                // --- curationItemOrder 검증 ---
                // 'required' 규칙
                if (!isset($item['curationItemOrder'])) {
                    $errors["items.$i.curationItemOrder"] = '필수 항목입니다.';
                }
                // 'integer' 규칙
                else if (!is_int($item['curationItemOrder']) && !ctype_digit($item['curationItemOrder'])) {
                    $errors["items.$i.curationItemOrder"] = '정수여야 합니다.';
                }
                // 'min:1' 규칙
                else if ((int)$item['curationItemOrder'] < 1) {
                    $errors["items.$i.curationItemOrder"] = '최소값은 1입니다.';
                } else {
                    $curationItemOrders[] = (int)$item['curationItemOrder'];
                }
            }

            // 개별 검증에서 에러가 있었다면 더 진행하지 않음
            if (!empty($errors)) {
                return ResponseHelper::jsonResponse2([
                    'header'    => [
                        'success' => false,
                        'message' => '필수 필드가 유효하지 않습니다.'
                    ],
                    'error' => $errors
                ], 400);
            }

            // order 중복 검증
            if (count($curationItemOrders) !== count(array_unique($curationItemOrders))) {
                $errors['curationItemOrder'] = '순서 값은 중복될 수 없습니다.';
            }

            //curation_idx 검증
            if (!empty($curationItemIdxs)) {
                $existingCount = CurationItem::whereIn('curation_item_idx', $curationItemIdxs)->where('curation_idx', $curationIdx)->count();
                if ($existingCount !== count($curationItemIdxs)) {
                    $errors['curationItemIdx'] = '존재하지 않는 큐레이션아이템 IDX가 포함되어 있습니다.';
                }
            }

            if (!empty($errors)) {
                return ResponseHelper::jsonResponse2([
                    'header'    => [
                        'success' => false,
                        'message' => '필수 필드가 유효하지 않습니다.'
                    ],
                    'error' => $errors
                ], 400);
            }

            //db curation_order update
            foreach ($input as $item) {
                CurationItem::where('curation_item_idx', $item['curationItemIdx'])
                    ->where('curation_idx', $curationIdx)
                    ->update(['curation_item_order' => $item['curationItemOrder']]);
            }
            
            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => true,
                    'message' => '큐레이션아이템 순서변경에 성공했습니다.',    
                ]
			]
            , 200);

        } catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '큐레이션아이템 순서변경에 실패했습니다.',    
                ],
				'error' => $e->getMessage()
			], 500);
		}
    }

    /**
     * 큐레이션 등록
     */
    public static function postCuration() {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();

		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '403 Unauthorized',    
                ]
            ], 403);
		}
        
        //request 검증
        //$input = json_decode(file_get_contents("php://input"), true);
        $input = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
        [$ok, $errors, $normalized] = self::validateCurationPayload($input);
        if (!$ok) {
            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '필수 필드가 유효하지 않습니다.'
                ],
                'error' => $errors
            ], 422);

        }    

        try {
            Capsule::beginTransaction();

             // 큐레이터 order 채번
            $nextOrder = ((int) Curation::max('curation_order')) + 1;

            //새 엔티티
            $curation = new Curation();
            $curation->curation_title           = $normalized['curationTitle'];
            $curation->curation_description     = $normalized['curationDescription'] ?? null;
            $curation->curation_visible_from    = !empty($normalized['curationVisibleFrom']) ? $normalized['curationVisibleFrom'] : null;
            $curation->curation_visible_to      = !empty($normalized['curationVisibleTo']) ? $normalized['curationVisibleTo'] : null;
            $curation->curation_order           = $nextOrder;
            $curation->partner_user_idx         = $checkUser->partner_user_idx;
            $curation->save();

            $returnCurationItems = [];
            $order = 1;
            foreach ($normalized['curationItems'] as $item) {
                $curationItem = new CurationItem();
                $curationItem->curation_idx         = $curation->curation_idx;
                $curationItem->target_idx           = (int)$item['targetIdx'];
                $curationItem->target_description   = $item['targetDescription'] ?? null;
                $curationItem->target_thumbnail_path= $item['targetThumbnailPath'] ?? null;
                if (array_key_exists('targetTags', $item)) {
                    $curationItem->target_tags      = $item['targetTags']; // 모델 casts['tags'=>'array']면 OK
                }
                $curationItem->curation_item_order  = $item['curationItemOrder'] ?? $order++;
                $curationItem->partner_user_idx     = $checkUser->partner_user_idx;
                $curationItem->save();

                $returnCurationItems[] = ['curationItemIdx' => $curationItem->curation_item_idx];
            }

            Capsule::commit();

            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => true,
                    'message' => '큐레이션 저장에 성공했습니다.',    
                ],
				'body' => [
                    'curationIdx'  => $curation->curation_idx,
                    'curationItems' => $returnCurationItems
                    //'curation_order'=> $curation->curation_order,
                ]
			]
            , 200);

        } catch (\Exception $e) {
			Capsule::rollBack();
            error_log($e);
            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '큐레이션 저장에 실패했습니다.',    
                ],
				'error' => $e->getMessage()
			], 500);
		}

        
    }

    /**
     * 큐레이션 수정
     */
    public static function putCuration($curationIdx) {
        $checkUser = MiddleHelper::checkPartnerLoginCookie();

		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '403 Unauthorized',    
                ]
            ], 403);
		}

        //requesst 검증
        //$input = json_decode(file_get_contents("php://input"), true);
        $input = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];

        //path 와 body의 curationIdx 검증
        if (isset($input['curationIdx']) && (int)$input['curationIdx'] !== (int)$curationIdx) {
            return ResponseHelper::jsonResponse2([
                'header' => ['success' => false, 'message' => 'path_id_body_id_conflict'],
                'error'  => 'curationIdx in path and body are different'
            ], 409);
        }

        [$ok, $errors, $normalized] = self::validateCurationPayload($input);
        if (!$ok) {
            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '필수 필드가 유효하지 않습니다.'
                ],
                'error' => $errors
            ], 422);

        }    

        //s3에 삭제할 키들 모아두는 버퍼
        $s3DeleteKeys = [];

        try {
            Capsule::beginTransaction();

            //기존 엔티티
            $curation = Curation::query()->find($curationIdx);

            if (!$curation) {
                Capsule::rollBack();
                return ResponseHelper::jsonResponse2([
                    'header' => ['success' => false, 'message' => '수정할 큐레이션이 없습니다.']
                ], 404);
            }

            $curation->curation_title           = $normalized['curationTitle'];
            $curation->curation_description     = $normalized['curationDescription'] ?? null;
            $curation->curation_visible_from    = $normalized['curationVisibleFrom'] ?? null;
            $curation->curation_visible_to      = $normalized['curationVisibleTo'] ?? null;
            $curation->partner_user_idx         = $checkUser->partner_user_idx;
            $curation->save();  //큐레이션 수정

            //하위 : 큐레이션 아이템 전체 교체
            //    - 전달된 목록 기준으로 upsert
            //    - 전달되지 않은 기존 아이템은 삭제
            $returnCurationItems = [];
            $seenIds = [];
            $order = 1;
            foreach ($normalized['curationItems'] as $item) {
                $curationItemIdx = isset($item['curationItemIdx']) ? (int)$item['curationItemIdx'] : null;
                if($curationItemIdx) {
                    //같은 curation의 아이템만 수정
                    $curationItem = CurationItem::where('curation_idx', $curationIdx)
                        ->where('curation_item_idx', $curationItemIdx)
                        ->first();
                    if (!$curationItem) {
                        Capsule::rollBack();
                        return ResponseHelper::jsonResponse2([
                            'header' => ['success' => false, 'message' => '수정될 큐레이션 숙소가 존재하지 않습니다.'],
                            'error'  => "curation_item_idx={$curationItemIdx}"
                        ], 404);
                    }

                    // ✅ 기존 썸네일 경로 백업 (변경 시 기존 키 S3에서 삭제하기 위함)
                    $oldThumbKey = $curationItem->target_thumbnail_path ?? null;

                } else {
                    //신규 큐레이션 아이템 생성
                    $curationItem = new CurationItem();
                    $curationItem->curation_idx = $curationIdx;

                    $oldThumbKey = null;
                }
               
                $curationItem->target_idx           = (int)$item['targetIdx'];
                $curationItem->target_description   = $item['targetDescription'] ?? null;
                
                // ✅ 경로 변경 감지
                $newThumbKey = $item['targetThumbnailPath'] ?? null;
                if ($oldThumbKey && $newThumbKey && $oldThumbKey !== $newThumbKey) {
                    $s3DeleteKeys[] = $oldThumbKey; // 이전 키는 커밋 후 삭제
                }

                $curationItem->target_thumbnail_path= $item['targetThumbnailPath'] ?? null;
                if (array_key_exists('targetTags', $item)) {
                    $curationItem->target_tags      = $item['targetTags']; // 모델 casts['tags'=>'array']면 OK
                }
                $curationItem->curation_item_order  = $item['curationItemOrder'] ?? $order++;
                $curationItem->partner_user_idx     = $checkUser->partner_user_idx;
                $curationItem->save();  //등록/생성

                $returnCurationItems[] = ['curationItemIdx' => $curationItem->curation_item_idx];
                $seenIds[] = (int)$curationItem->curation_item_idx;
            }
            //E::foreach curation items
            // 전달되지 않은 기존 아이템 정리(삭제)
            // 전달되지 않아 삭제될 아이템들의 썸네일 키 수집 후 삭제
            if (count($seenIds) > 0) {
                $toDelete = CurationItem::where('curation_idx', $curationIdx)
                    ->whereNotIn('curation_item_idx', $seenIds)
                    ->get(['curation_item_idx', 'target_thumbnail_path']);

                foreach ($toDelete as $row) {
                    if (!empty($row->target_thumbnail_path)) {
                        $s3DeleteKeys[] = $row->target_thumbnail_path;
                    }
                }

                // DB 삭제
                CurationItem::where('curation_idx', $curationIdx)
                    ->whereNotIn('curation_item_idx', $seenIds)
                    ->delete();
            } else {
                // 규칙: PUT은 전체 교체 → 아이템 0개 전달은 모두 삭제
                $toDeleteAll = CurationItem::where('curation_idx', $curationIdx)
                    ->get(['curation_item_idx', 'target_thumbnail_path']);

                foreach ($toDeleteAll as $row) {
                    if (!empty($row->target_thumbnail_path)) {
                        $s3DeleteKeys[] = $row->target_thumbnail_path;
                    }
                }

                CurationItem::where('curation_idx', $curationIdx)->delete();
            }

            Capsule::commit();

            // ✅ 트랜잭션 커밋 후 S3 삭제(실패해도 DB는 유지)
            $imageService = new ImageUploadAwsService();
            $s3Errors = [];
            foreach (array_unique($s3DeleteKeys) as $key) {
                try {
                    $ok = $imageService->deleteObjectByKey($key); // 아래 2) 참고
                    if (!$ok) {
                        $s3Errors[] = ['key' => $key, 'message' => 'deleteObjectByKey returned false'];
                    }
                } catch (\Throwable $e) {
                    $s3Errors[] = ['key' => $key, 'message' => $e->getMessage()];
                }
            }

            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => true,
                    'message' => '큐레이션 수정에 성공했습니다.',    
                ],
				'body' => [
                    'curationIdx'  => $curation->curation_idx,
                    'curationItems' => $returnCurationItems
                    //'curation_order'=> $curation->curation_order,
                ]
			]
            , 200);

        } catch (\Exception $e) {
			Capsule::rollBack();

            return ResponseHelper::jsonResponse2([
                'header'    => [
                    'success' => false,
                    'message' => '큐레이션 수정에 실패했습니다.',    
                ],
				'error' => $e->getMessage()
			], 500);
		}
    }

    /**
     * 큐레이션 등록/수정 input 검증
     */
    function validateCurationPayload(array $input): array {
        $errors = [];

        // 제목: required
        if (!isset($input['curationTitle']) || trim((string)$input['curationTitle']) === '') {
            $errors['curationTitle'] = 'required';
        }

        // 날짜 포맷(YYYY-MM-DD) 검증
        $dateRe = '/^\d{4}-\d{2}-\d{2}$/';
        $from = $input['curationVisibleFrom'] ?? null;
        $to   = $input['curationVisibleTo']   ?? null;

        if ($from !== null && $from !== '' && !preg_match($dateRe, $from)) {
            $errors['curationVisibleFrom'] = 'invalid_date_yyyy_mm_dd';
        }
        if ($to !== null && $to !== '' && !preg_match($dateRe, $to)) {
            $errors['curationVisibleTo'] = 'invalid_date_yyyy_mm_dd';
        }
        if (empty($errors['curationVisibleFrom']) && empty($errors['curationVisibleTo'])
            && $from && $to && ($from > $to)) {
            $errors['visibleRange'] = 'from_must_be_before_to';
        }

        // 아이템 배열: required, array, min length 1
        if (!isset($input['curationItems']) || !is_array($input['curationItems']) || count($input['curationItems']) < 1) {
            $errors['curationItems'] = 'array_min_1';
        } else {
            // 각 아이템 검증
            $allowedTargetTypes = ['partner','hotel','package','product','etc'];
            foreach ($input['curationItems'] as $i => $item) {
                // targetType
                // if (!isset($item['targetType']) || !in_array($item['targetType'], $allowedTargetTypes, true)) {
                //     $errors["curationItems.$i.targetType"] = 'invalid_enum';
                // }
                // targetIdx (숫자 required)
                if (!isset($item['targetIdx']) || !is_numeric($item['targetIdx'])) {
                    $errors["curationItems.$i.targetIdx"] = 'required_numeric';
                }

                // (선택) 태그: 배열 or JSON 문자열 → 배열
                if (array_key_exists('targetTags', $item) && $item['targetTags'] !== null) {
                    $tags = $item['targetTags'];

                    if (is_string($tags)) {
                        $decoded = json_decode($tags, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $tags = $decoded;
                            $input['curationItems'][$i]['targetTags'] = $decoded; // 정규화
                        } else {
                            $errors["curationItems.$i.targetTags"] = 'invalid_json';
                        }
                    }

                    if (!isset($errors["curationItems.$i.targetTags"])) {
                        if (!is_array($tags)) {
                            $errors["curationItems.$i.targetTags"] = 'must_be_array';
                        } else {
                            foreach ($tags as $t => $tag) {
                                if (!is_array($tag) || !isset($tag['label']) || !isset($tag['icon'])) {
                                    $errors["curationItems.$i.targetTags.$t"] = 'object_with_label_ko_and_icon_required';
                                    continue;
                                }
                                if (trim((string)$tag['label']) === '' || trim((string)$tag['icon']) === '') {
                                    $errors["curationItems.$i.targetTags.$t"] = 'label_ko_and_icon_must_be_nonempty';
                                }
                            }
                        }
                    }
                }
            }
        }

        return [empty($errors), $errors, $input]; // 정규화된 $input도 함께 반환
    }

}