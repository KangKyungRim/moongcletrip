<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\Stay;
use App\Models\StayDraft;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\Image;
use App\Models\ImageDraft;
use App\Models\CancelRule;
use App\Models\CancelRuleDraft;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class StayApiController
{
	// Stay 생성 및 업데이트 API
	public static function storeDraft()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		// 입력된 데이터 가져오기
		$input = json_decode(file_get_contents("php://input"), true);

		// 필수 데이터 확인
		if (empty($input['amenities']) || empty($input['checkin']) || empty($input['checkout']) || empty($input['stayMainImage']) || empty($input['staySubImage']) || empty($input['daysBefore'])) {
			return ResponseHelper::jsonResponse(['error' => '필수 필드가 누락되었습니다.'], 400);
		}

		// DB 트랜잭션 시작
		Capsule::beginTransaction();

		try {
			$partner = Partner::find($input['partnerIdx']);

			if (!empty($partner->partner_detail_idx)) {
				$stay = $partner->partnerDetail();
				$stayDraft = $stay->draft;
			} else {
				$stay = new Stay();
				$stay->stay_status = 'disabled';
				$stay->stay_created_at = Carbon::now();
				$stay->stay_updated_at = Carbon::now();
				$stay->save();

				$stayDraft = new StayDraft();
				$stayDraft->stay_idx = $stay->stay_idx;
				$stayDraft->is_approved = false;
			}

			// 배열 길이가 동일한지 확인
			if (count($input['daysBefore']) !== count($input['refund']) || count($input['refund']) !== count($input['checkinTime'])) {
				return ResponseHelper::jsonResponse(['message' => '취소 규칙 데이터의 길이가 일치하지 않습니다.'], 500);
			}

			self::saveTagConnectionsDraft($stay->stay_idx, $input['amenities'], 'amenity');
			self::saveTagConnectionsDraft($stay->stay_idx, $input['barrierfreePublic'], 'barrierfree_public');
			self::saveTagConnectionsDraft($stay->stay_idx, $input['barrierfreeRoom'], 'barrierfree_room');

			self::saveImagesDraft($stay->stay_idx, $input['stayMainImage'], 'main');
			self::saveImagesDraft($stay->stay_idx, $input['staySubImage'], 'sub');
			self::saveImagesDraft($stay->stay_idx, $input['bfPublicImage'], 'barrierfree_public');
			self::saveImagesDraft($stay->stay_idx, $input['bfRoomImage'], 'barrierfree_room');

			self::saveCancelRulesDraft($input['partnerIdx'], $input['daysBefore'], $input['checkinTime'], $input['refund']);

			$stayDraft->stay_checkin_rule = $input['checkin'];
			$stayDraft->stay_checkout_rule = $input['checkout'];
			$stayDraft->stay_basic_info = $input['stayBasicInfo'];
			$stayDraft->stay_important_info = $input['stayImportantInfo'];
			$stayDraft->stay_amenity_info = $input['stayAmenityInfo'];
			$stayDraft->stay_breakfast_info = $input['stayBreakfastInfo'];
			$stayDraft->stay_personnel_info = $input['stayPersonnelInfo'];
			$stayDraft->stay_cancel_info = $input['stayCancelInfo'];
			$stayDraft->is_approved = false;
			$stayDraft->save();

			if (empty($partner->partner_detail_idx)) {
				$partner->partner_detail_idx = $stay->stay_idx;
				$partner->save();
			}

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse([
				'message' => '숙소 정보가 성공적으로 저장되었습니다.',
				'partner_id' => $partner->partner_idx,
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	private function saveTagConnectionsDraft($stayId, $newTags, $subType)
	{
		// 1. 태그 ID 추출 (모든 접두사에서 숫자만 추출)
		$tagIds = array_map(function ($tag) {
			// 태그에서 숫자 부분만 추출 (접두사 상관없이)
			return (int)preg_replace('/\D/', '', $tag);
		}, $newTags);

		// 1. 기존 태그 연결 가져오기
		$existingTags = TagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', 'stay')
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		// 2. 기존 태그 중에서 새로 전달받은 태그에 없는 태그 삭제
		$tagsToDelete = array_diff($existingTags, $tagIds);
		if (!empty($tagsToDelete)) {
			TagConnectionDraft::where('item_idx', $stayId)
				->where('item_type', 'stay')
				->where('connection_type', $subType)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		// 3. 새로 전달받은 태그 중 기존에 없는 태그 추가
		$tagsToAdd = array_diff($tagIds, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			TagConnectionDraft::create([
				'tag_idx' => $tagId,
				'item_idx' => $stayId,
				'item_type' => 'stay',
				'connection_type' => $subType,
				'is_approved' => false
			]);
		}
	}

	private function saveImagesDraft($stayId, $newImages, $imageType)
	{
		$newImages = isset($newImages) ? json_decode($newImages, true) : [];

		foreach ($newImages as &$newImage) {
			if (strpos($newImage['image_origin_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_origin_path'];
				$newPath = str_replace('/noid/', "/$stayId/", $oldPath);

				// 디렉토리가 없는 경우 생성
				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				// 파일 이동
				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_origin_path'] = str_replace('/noid/', "/$stayId/", $newImage['image_origin_path']);
				}
			}
			if (strpos($newImage['image_small_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_small_path'];
				$newPath = str_replace('/noid/', "/$stayId/", $oldPath);

				// 디렉토리가 없는 경우 생성
				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				// 파일 이동
				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_small_path'] = str_replace('/noid/', "/$stayId/", $newImage['image_small_path']);
				}
			}
			if (strpos($newImage['image_normal_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_normal_path'];
				$newPath = str_replace('/noid/', "/$stayId/", $oldPath);

				// 디렉토리가 없는 경우 생성
				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				// 파일 이동
				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_normal_path'] = str_replace('/noid/', "/$stayId/", $newImage['image_normal_path']);
				}
			}
			if (strpos($newImage['image_big_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_big_path'];
				$newPath = str_replace('/noid/', "/$stayId/", $oldPath);

				// 디렉토리가 없는 경우 생성
				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				// 파일 이동
				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_big_path'] = str_replace('/noid/', "/$stayId/", $newImage['image_big_path']);
				}
			}
		}

		// 1. 기존 이미지 가져오기
		$existingImages = ImageDraft::where('image_entity_id', $stayId)
			->where('image_entity_type', 'stay')
			->where('image_type', $imageType)
			->get();

		// 새로 전송된 이미지 경로 추출
		$newImagePaths = array_column($newImages, 'image_origin_path');

		if (empty($newImages)) {
			// 새 이미지가 없을 경우 모든 기존 이미지 삭제
			foreach ($existingImages as $existingImage) {
				// 이미지 파일 삭제 (필요 시)
				// unlink(public_path($existingImage->image_origin_path));

				// DB에서 해당 이미지 삭제
				$existingImage->delete();
			}
		} else {
			// 기존 이미지 중에서 새로 전송된 이미지에 포함되지 않은 이미지를 삭제
			foreach ($existingImages as $existingImage) {
				if (!in_array($existingImage->image_origin_path, $newImagePaths)) {
					// 이미지 파일 삭제 (필요 시)
					// unlink(public_path($existingImage->image_origin_path));

					// DB에서 해당 이미지 삭제
					$existingImage->delete();
				} else {
					// 기존 이미지가 새로운 이미지 배열에 존재하는 경우 순서 업데이트
					foreach ($newImages as $index => $newImage) {
						if ($newImage['image_origin_path'] === $existingImage->image_origin_path) {
							// 이미지 순서 업데이트
							$existingImage->image_order = $index + 1;
							$existingImage->save();
						}
					}
				}
			}
		}

		// 1. 기존 이미지와 비교하여 삭제된 이미지 처리
		$existingImagePaths = $existingImages->pluck('image_origin_path')->toArray();

		// 2. 새로 추가된 이미지 저장
		foreach ($newImages as $index => $newImage) {
			// 기존 이미지에 해당 이미지가 없을 때만 새로 저장
			if (!in_array($newImage['image_origin_path'], $existingImagePaths)) {
				ImageDraft::create([
					'image_entity_id' => $stayId,
					'image_entity_type' => $newImage['image_entity_type'],
					'image_type' => $newImage['image_type'],
					'image_origin_name' => $newImage['image_origin_name'],
					'image_origin_path' => $newImage['image_origin_path'],
					'image_origin_size' => filesize($_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_origin_path']),
					'image_small_path' => $newImage['image_small_path'],
					'image_normal_path' => $newImage['image_normal_path'],
					'image_order' => $index + 1,  // 이미지 순서 저장
					'is_approved' => false
				]);
			}
		}
	}

	private function saveCancelRulesDraft($partnerIdx, $daysBefore, $checkinTime, $refund)
	{
		// 새로 전달받은 규칙을 저장할 배열
		$newCancelRulesData = [];

		// 기존 규칙을 가져옵니다.
		$existingCancelRules = CancelRuleDraft::where('partner_idx', $partnerIdx)->get();

		// 1. 기존 규칙과 새로 받은 규칙을 비교
		foreach ($daysBefore as $index => $day) {
			$existingRule = $existingCancelRules->firstWhere('cancel_rules_day', $day);

			if ($existingRule) {
				// 기존 규칙이 있으면 업데이트
				$existingRule->update([
					'cancel_rules_order' => $index + 1,  // 순서 업데이트
					'cancel_rules_percent' => $refund[$index],  // 환불 비율 업데이트
					'cancel_rules_time' => $checkinTime[$index],  // 시간 업데이트
					'is_approved' => false,  // 업데이트 후 미승인 상태로 유지
				]);
			} else {
				// 기존 규칙이 없으면 새로 추가할 규칙 배열에 저장
				$newCancelRulesData[] = [
					'partner_idx' => $partnerIdx,
					'cancel_rules_order' => $index + 1,
					'cancel_rules_day' => $day,
					'cancel_rules_percent' => $refund[$index],
					'cancel_rules_time' => $checkinTime[$index],
					'is_approved' => false,
				];
			}
		}

		// 2. 새로 넘어온 값에는 없지만 기존에 있던 규칙을 삭제
		foreach ($existingCancelRules as $existingRule) {
			if (!in_array($existingRule->cancel_rules_day, $daysBefore)) {
				$existingRule->delete();
			}
		}

		// 3. 새로 추가할 규칙이 있으면 데이터베이스에 삽입
		if (!empty($newCancelRulesData)) {
			CancelRuleDraft::insert($newCancelRulesData);
		}
	}

	public static function approve()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		// 입력된 데이터 가져오기
		$input = json_decode(file_get_contents("php://input"), true);

		try {
			// 파트너와 관련된 스테이 데이터 가져오기
			$partner = Partner::find($input['partnerIdx']);
			if (!$partner) {
				return ResponseHelper::jsonResponse(['error' => '파트너를 찾을 수 없습니다.'], 404);
			}

			$stay = $partner->partnerDetail();  // 파트너와 연결된 Stay 정보
			if (!$stay) {
				return ResponseHelper::jsonResponse(['error' => '승인할 숙소 정보가 없습니다.'], 404);
			}

			// 초안 데이터를 가져오기
			$stayDraft = $stay->draft;
			if (!$stayDraft) {
				return ResponseHelper::jsonResponse(['error' => '승인할 초안이 없습니다.'], 404);
			}

			// DB 트랜잭션 시작
			Capsule::beginTransaction();

			// 1. 기본 정보 업데이트
			$stay->stay_checkin_rule = $stayDraft->stay_checkin_rule;
			$stay->stay_checkout_rule = $stayDraft->stay_checkout_rule;
			$stay->stay_basic_info = $stayDraft->stay_basic_info;
			$stay->stay_important_info = $stayDraft->stay_important_info;
			$stay->stay_amenity_info = $stayDraft->stay_amenity_info;
			$stay->stay_breakfast_info = $stayDraft->stay_breakfast_info;
			$stay->stay_personnel_info = $stayDraft->stay_personnel_info;
			$stay->stay_cancel_info = $stayDraft->stay_cancel_info;
			$stay->stay_status = 'enabled';
			$stay->stay_updated_at = Carbon::now();  // 업데이트 시간 저장
			$stay->save();

			// 2. 태그 연결 승인 (draft에서 실제로 반영)
			self::approveTagConnections($stay->stay_idx, 'stay');

			// 3. 이미지 승인 (draft 이미지들을 실제로 반영)
			self::approveImages($stay->stay_idx, 'stay');

			// 4. 취소 규칙 승인
			self::approveCancelRules($input['partnerIdx']);

			// 5. 초안 상태 업데이트 (승인됨으로 표시)
			$stayDraft->is_approved = true;
			$stayDraft->save();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse([
				'message' => '숙소 정보가 성공적으로 승인되었습니다.',
				'stay_id' => $stay->stay_idx,
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	private static function approveTagConnections($stayId, $category)
	{
		// Draft와 기존 승인된 데이터를 비교하여 처리
		$existingTags = TagConnection::where('item_idx', $stayId)
			->where('item_type', $category)
			->pluck('tag_idx')
			->toArray();

		$draftTags = TagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', $category)
			->pluck('tag_idx')
			->toArray();

		// 1. 기존 태그 중에서 Draft에 없는 태그 삭제
		$tagsToDelete = array_diff($existingTags, $draftTags);
		if (!empty($tagsToDelete)) {
			TagConnection::where('item_idx', $stayId)
				->where('item_type', $category)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		// 2. Draft 태그 중 기존에 없는 태그 추가
		$tagsToAdd = array_diff($draftTags, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			TagConnection::create([
				'tag_idx' => $tagId,
				'item_idx' => $stayId,
				'item_type' => $category,
			]);
		}

		// Draft는 삭제하지 않고 상태만 업데이트
		TagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', $category)
			->update(['is_approved' => true]);
	}

	private static function approveImages($stayId, $category)
	{
		// 기존 승인된 이미지와 Draft 이미지를 비교
		$existingImages = Image::where('image_entity_id', $stayId)
			->where('image_entity_type', $category)
			->pluck('image_origin_path')
			->toArray();

		$draftImages = ImageDraft::where('image_entity_id', $stayId)
			->where('image_entity_type', $category)
			->pluck('image_origin_path')
			->toArray();

		// 1. 기존 이미지 중에서 Draft에 없는 이미지 삭제
		$imagesToDelete = array_diff($existingImages, $draftImages);
		if (!empty($imagesToDelete)) {
			Image::where('image_entity_id', $stayId)
				->where('image_entity_type', $category)
				->whereIn('image_origin_path', $imagesToDelete)
				->delete();
		}

		// 2. Draft 이미지 중 기존에 없는 이미지 추가
		$imagesToAdd = array_diff($draftImages, $existingImages);
		foreach ($imagesToAdd as $draftImage) {
			$imageData = ImageDraft::where('image_origin_path', $draftImage)->first();
			Image::create([
				'image_entity_id' => $stayId,
				'image_entity_type' => $category,
				'image_type' => $imageData->image_type,
				'image_origin_name' => $imageData->image_origin_name,
				'image_origin_path' => $imageData->image_origin_path,
				'image_small_path' => $imageData->image_small_path,
				'image_normal_path' => $imageData->image_normal_path,
				'image_order' => $imageData->image_order,
			]);
		}

		// Draft는 삭제하지 않고 상태만 업데이트
		ImageDraft::where('image_entity_id', $stayId)
			->where('image_entity_type', $category)
			->update(['is_approved' => true]);
	}

	private static function approveCancelRules($partnerIdx)
	{
		// 기존 승인된 취소 규칙과 Draft 취소 규칙을 비교
		$existingRules = CancelRule::where('partner_idx', $partnerIdx)
			->pluck('cancel_rules_day')
			->toArray();

		$draftRules = CancelRuleDraft::where('partner_idx', $partnerIdx)
			->pluck('cancel_rules_day')
			->toArray();

		// 1. 기존 규칙 중에서 Draft에 없는 규칙 삭제
		$rulesToDelete = array_diff($existingRules, $draftRules);
		if (!empty($rulesToDelete)) {
			CancelRule::where('partner_idx', $partnerIdx)
				->whereIn('cancel_rules_day', $rulesToDelete)
				->delete();
		}

		// 2. Draft 규칙 중 기존에 없는 규칙 추가
		$rulesToAdd = array_diff($draftRules, $existingRules);
		foreach ($rulesToAdd as $ruleDay) {
			$draftRule = CancelRuleDraft::where('partner_idx', $partnerIdx)
				->where('cancel_rules_day', $ruleDay)->first();

			CancelRule::create([
				'partner_idx' => $draftRule->partner_idx,
				'cancel_rules_order' => $draftRule->cancel_rules_order,
				'cancel_rules_day' => $draftRule->cancel_rules_day,
				'cancel_rules_percent' => $draftRule->cancel_rules_percent,
				'cancel_rules_time' => $draftRule->cancel_rules_time,
				'is_approved' => true,
			]);
		}

		// Draft는 삭제하지 않고 상태만 업데이트
		CancelRuleDraft::where('partner_idx', $partnerIdx)
			->update(['is_approved' => true]);
	}
}
