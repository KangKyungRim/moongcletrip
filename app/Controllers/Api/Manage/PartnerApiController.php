<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\PartnerDraft;
use App\Models\Stay;
use App\Models\StayDraft;
use App\Models\Tag;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\MoongcleTagConnection;
use App\Models\MoongcleTagConnectionDraft;
use App\Models\Image;
use App\Models\ImageDraft;
use App\Models\CancelRule;
use App\Models\CancelRuleDraft;
use App\Models\MainViewList;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;
use App\Models\MoongcleTag;
use Carbon\Carbon;

class PartnerApiController
{
	/**
	 * 파트너 정보(공개여부, 수수료) 수정
	 */
	public static function partnerStatusToggle()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['partnerCharge']) || empty($input['partnerStatus'])) {
			return ResponseHelper::jsonResponse(['error' => '필수 필드가 누락되었습니다.'], 400);
		}

		$partner = Partner::find($input['partnerIdx']);
		$partner->partner_status = $input['partnerStatus'];
		$partner->partner_charge = $input['partnerCharge'];
		$partner->save();

		$partnerDraft = PartnerDraft::where('partner_idx', $partner->partner_idx)->first();
		$partnerDraft->partner_charge = $input['partnerCharge'];
		$partnerDraft->save();

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '파트너 정보를 수정했습니다.',
			'data' => [
				'partnerIdx' => $partner->partner_idx
			]
		], 200);
	}

	/**
	 * 파트너 정보(검색 지수) 수정
	 */
	public static function editPartnerSearchIndex() {
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['partnerSearchIndex'])) {
			return ResponseHelper::jsonResponse(['error' => '필수 필드가 누락되었습니다.'], 400);
		}

		$partner = Partner::find($input['partnerIdx']);
		$partner->search_index = $input['partnerSearchIndex'];
		$partner->save();

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '파트너 정보를 수정했습니다.',
			'data' => [
				'partnerIdx' => $partner->partner_idx
			]
		], 200);
	}

	public static function createPartner()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		$partnerDetail = null;

		$partner = new Partner();
		$partner->partner_name = $input['partnerName'];
		$partner->partner_category = $input['partnerCategory'];
		$partner->partner_calculation_type = 'sellingPrice';

		if ($input['partnerCategory'] === 'stay') {
			$partnerDetail = new Stay();
			$partnerDetail->stay_status = 'disabled';
			$partnerDetail->stay_created_at = Carbon::now();
			$partnerDetail->stay_updated_at = Carbon::now();
			$partnerDetail->save();

			$partnerDetailDraft = new StayDraft();
			$partnerDetailDraft->stay_idx = $partnerDetail->stay_idx;
			$partnerDetailDraft->save();

			$partner->partner_detail_idx = $partnerDetail->stay_idx;
		}

		$partner->partner_created_at = Carbon::now();
		$partner->partner_updated_at = Carbon::now();
		$partner->save();

		$partnerDraft = new PartnerDraft();
		$partnerDraft->partner_idx = $partner->partner_idx;
		$partnerDraft->partner_name = $partner->partner_name;
		$partnerDraft->partner_category = $partner->partner_category;
		$partnerDraft->partner_calculation_type = 'sellingPrice';
		$partnerDraft->save();

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '신규 파트너를 임시 생성했습니다.',
			'data' => [
				'partnerIdx' => $partner->partner_idx
			]
		], 200);
	}

	public static function editPartner()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (
			empty($input['partnerName']) ||
			empty($input['partnerCategory']) ||
			empty($input['partnerEmail']) ||
			empty($input['partnerPhonenumber']) ||
			empty($input['checkin']) ||
			empty($input['checkout']) ||
			empty($input['stayBasicImage']) ||
			empty($input['daysBefore'])
		) {
			return ResponseHelper::jsonResponse(['error' => '필수 필드가 누락되었습니다.'], 400);
		}

		if (!filter_var($input['partnerEmail'], FILTER_VALIDATE_EMAIL)) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '유효하지 않은 이메일 형식입니다.',
				'error' => ''
			], 400);
		}

		if (!is_numeric($input['partnerPhonenumber']) || strlen($input['partnerPhonenumber']) > 11) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '유효하지 않은 전화번호 형식입니다.',
				'error' => ''
			], 400);
		}

		if (count($input['daysBefore']) !== count($input['refund']) || count($input['refund']) !== count($input['checkinTime'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '취소 규칙 데이터의 길이가 일치하지 않습니다.',
				'error' => ''
			], 500);
		}

		try {
			Capsule::beginTransaction();

			$partner = Partner::find($input['partnerIdx']);
			$partnerDraft = $partner->draft;

			$stay = $partner->partnerDetail();
			$stayDraft = $stay->draft;

			$partnerDraft->partner_name = $input['partnerName'];
			$partnerDraft->partner_zip = $input['zipcode'] ?? null;
			$partnerDraft->partner_origin_address1 = $input['address1'] ?? null;
			$partnerDraft->partner_origin_address2 = $input['address2'] ?? null;
			$partnerDraft->partner_origin_address3 = $input['address3'] ?? null;

			if (!empty($input['address1'])) {
				$address = explode(' ', $input['address1']);

				$partnerDraft->partner_address1 = $address[0];
				if (!empty($address[1])) {
					$partnerDraft->partner_address1 .= ' ' . $address[1];
				}

				$address2 = ' ' . $input['address2'] ?? '';
				if (!empty($address[2])) {
					$partnerDraft->partner_address2 = implode(' ', array_slice($address, 2)) . $address2;
				} else {
					$partnerDraft->partner_address2 = $address2;
				}

				$partnerDraft->partner_address3 = $partnerDraft->partner_origin_address3;
			}

			$partnerDraft->partner_city = $input['mapCity'] ?? null;
			$partnerDraft->partner_region = $input['mapRegion'] ?? null;
			$partnerDraft->partner_region_detail = $input['mapRegionDetail'] ?? null;
			$partnerDraft->partner_latitude = $input['latitude'] ? number_format((float) $input['latitude'], 7, '.', '') : null;
			$partnerDraft->partner_longitude = $input['longitude'] ? number_format((float) $input['longitude'], 7, '.', '') : null;
			$partnerDraft->partner_phonenumber = $input['partnerPhonenumber'];
			$partnerDraft->partner_email = $input['partnerEmail'];
			$partnerDraft->partner_reservation_phonenumber = $input['partnerReservationPhonenumber'];
			$partnerDraft->partner_reservation_email = $input['partnerReservationEmail'];
			$partnerDraft->partner_manager_phonenumber = $input['partnerManagerPhonenumber'];
			$partnerDraft->partner_manager_email = $input['partnerManagerEmail'];
			$partnerDraft->partner_search_badge = $input['searchBadge'] ?? null;
			$partnerDraft->is_approved = false;
			$partnerDraft->save();

			self::saveTagConnectionsDraft($stay->stay_idx, $input['stayType'], 'stay_type');
			self::saveTagConnectionsDraft($stay->stay_idx, $input['stayTypeDetail'], 'stay_type_detail');
			self::saveTagConnectionsDraft($stay->stay_idx, $input['facility'], 'facility');
			self::saveTagConnectionsDraft($stay->stay_idx, $input['attraction'], 'attraction');
			self::saveTagConnectionsDraft($stay->stay_idx, $input['service'], 'service');
			// self::saveTagConnectionsDraft($stay->stay_idx, $input['pet'], 'pet');
			self::saveTagConnectionsDraft($stay->stay_idx, $input['barrierfreePublic'], 'barrierfree_public');
			self::saveTagConnectionsDraft($stay->stay_idx, $input['barrierfreeRoom'], 'barrierfree_room');

			self::saveMoongcleTagConnectionsDraft($stay->stay_idx, $input['stayType'], 'stay_type');
			self::saveMoongcleTagConnectionsDraft($stay->stay_idx, $input['stayTypeDetail'], 'stay_type_detail');
			self::saveMoongcleTagConnectionsDraft($stay->stay_idx, $input['facility'], 'facility');
			self::saveMoongcleTagConnectionsDraft($stay->stay_idx, $input['attraction'], 'attraction');
			self::saveMoongcleTagConnectionsDraft($stay->stay_idx, $input['service'], 'service');
			self::saveMoongcleTagConnectionsDraft($stay->stay_idx, $input['pet'], 'pet');
			self::saveMoongcleTagConnectionsDraft($stay->stay_idx, $input['barrierfreePublic'], 'barrierfree_public');
			self::saveMoongcleTagConnectionsDraft($stay->stay_idx, $input['barrierfreeRoom'], 'barrierfree_room');

			self::saveImagesDraft($stay->stay_idx, $input['stayBasicImage'], 'basic');
			self::saveImagesDraft($stay->stay_idx, $input['bfPublicImage'], 'barrierfree_public');
			self::saveImagesDraft($stay->stay_idx, $input['bfRoomImage'], 'barrierfree_room');

			self::saveCancelRulesDraft($input['partnerIdx'], $input['daysBefore'], $input['checkinTime'], $input['refund']);

			$stayDraft->stay_checkin_rule = $input['checkin'];
			$stayDraft->stay_checkout_rule = $input['checkout'];
			$stayDraft->stay_basic_info = $input['stayBasicInfo'];
			$stayDraft->stay_important_info = $input['stayImportantInfo'];
			$stayDraft->stay_notice_info = $input['stayNoticeInfo'];
			$stayDraft->stay_amenity_info = $input['stayAmenityInfo'];
			$stayDraft->stay_breakfast_info = $input['stayBreakfastInfo'];
			$stayDraft->stay_personnel_info = $input['stayPersonnelInfo'];
			$stayDraft->stay_cancel_info = $input['stayCancelInfo'];
			$stayDraft->is_approved = false;
			$stayDraft->save();

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '파트너가 성공적으로 저장되었습니다.',
				'data' => [
					'partnerIdx' => $partner->partner_idx,
				]
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '파트너 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function approve()
	{
		// 파트너 관리자 로그인 확인
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '파트너 ID가 필요합니다.',
				'error' => ''
			], 400);
		}

		try {
			// DB 트랜잭션 시작
			Capsule::beginTransaction();

			// 파트너 초안 가져오기
			$partnerDraft = PartnerDraft::where('partner_idx', $input['partnerIdx'])
				->where('is_approved', false)
				->first();

			if (!$partnerDraft) {
				return ResponseHelper::jsonResponse([
					'success' => false,
					'message' => '승인할 초안을 찾을 수 없습니다.',
					'error' => ''
				], 404);
			}

			// 파트너 본 데이터 가져오기
			$partner = Partner::find($input['partnerIdx']);

			if (!$partner) {
				return ResponseHelper::jsonResponse([
					'success' => false,
					'message' => '파트너 정보를 찾을 수 없습니다.',
					'error' => ''
				], 404);
			}

			$stay = $partner->partnerDetail();

			if (!$stay) {
				return ResponseHelper::jsonResponse([
					'success' => false,
					'message' => '승인할 숙소 정보가 없습니다.',
					'error' => ''
				], 404);
			}

			$stayDraft = $stay->draft;

			if (!$stayDraft) {
				return ResponseHelper::jsonResponse([
					'success' => false,
					'message' => '승인할 초안이 없습니다.',
					'error' => ''
				], 404);
			}

			// 파트너 데이터 업데이트
			$partner->partner_name = $partnerDraft->partner_name;
			$partner->partner_type = $partnerDraft->partner_type ?? null;
			$partner->partner_grade = $partnerDraft->partner_grade ?? null;
			$partner->partner_zip = $partnerDraft->partner_zip ?? null;
			$partner->partner_origin_address1 = $partnerDraft->partner_origin_address1 ?? null;
			$partner->partner_origin_address2 = $partnerDraft->partner_origin_address2 ?? null;
			$partner->partner_origin_address3 = $partnerDraft->partner_origin_address3 ?? null;
			$partner->partner_address1 = $partnerDraft->partner_address1 ?? null;
			$partner->partner_address2 = $partnerDraft->partner_address2 ?? null;
			$partner->partner_address3 = $partnerDraft->partner_address3 ?? null;
			$partner->partner_city = $partnerDraft->partner_city ?? null;
			$partner->partner_region = $partnerDraft->partner_region ?? null;
			$partner->partner_region_detail = $partnerDraft->partner_region_detail ?? null;
			$partner->partner_latitude = $partnerDraft->partner_latitude ?? null;
			$partner->partner_longitude = $partnerDraft->partner_longitude ?? null;
			$partner->partner_phonenumber = $partnerDraft->partner_phonenumber;
			$partner->partner_email = $partnerDraft->partner_email;
			$partner->partner_reservation_phonenumber = $partnerDraft->partner_reservation_phonenumber ?? null;
			$partner->partner_reservation_email = $partnerDraft->partner_reservation_email ?? null;
			$partner->partner_manager_phonenumber = $partnerDraft->partner_manager_phonenumber ?? null;
			$partner->partner_manager_email = $partnerDraft->partner_manager_email ?? null;
			$partner->partner_status = 'enabled';
			$partner->partner_search_badge = $partnerDraft->partner_search_badge ?? null;
			$partner->partner_updated_at = Carbon::now();

			$partner->save();

			// 초안 승인 상태 업데이트
			$partnerDraft->is_approved = true;
			$partnerDraft->save();

			$stay->stay_checkin_rule = $stayDraft->stay_checkin_rule;
			$stay->stay_checkout_rule = $stayDraft->stay_checkout_rule;
			$stay->stay_basic_info = $stayDraft->stay_basic_info;
			$stay->stay_important_info = $stayDraft->stay_important_info;
			$stay->stay_notice_info = $stayDraft->stay_notice_info;
			$stay->stay_amenity_info = $stayDraft->stay_amenity_info;
			$stay->stay_breakfast_info = $stayDraft->stay_breakfast_info;
			$stay->stay_personnel_info = $stayDraft->stay_personnel_info;
			$stay->stay_cancel_info = $stayDraft->stay_cancel_info;
			$stay->stay_status = 'enabled';
			$stay->stay_updated_at = Carbon::now();
			$stay->save();

			self::approveTagConnections($stay->stay_idx, 'stay', 'stay_type');
			self::approveTagConnections($stay->stay_idx, 'stay', 'stay_type_detail');
			self::approveTagConnections($stay->stay_idx, 'stay', 'facility');
			self::approveTagConnections($stay->stay_idx, 'stay', 'attraction');
			self::approveTagConnections($stay->stay_idx, 'stay', 'service');
			self::approveTagConnections($stay->stay_idx, 'stay', 'pet');
			self::approveTagConnections($stay->stay_idx, 'stay', 'barrierfree_public');
			self::approveTagConnections($stay->stay_idx, 'stay', 'barrierfree_room');

			self::approveMoongcleTagConnections($stay->stay_idx, 'stay', 'stay_type');
			self::approveMoongcleTagConnections($stay->stay_idx, 'stay', 'stay_type_detail');
			self::approveMoongcleTagConnections($stay->stay_idx, 'stay', 'facility');
			self::approveMoongcleTagConnections($stay->stay_idx, 'stay', 'attraction');
			self::approveMoongcleTagConnections($stay->stay_idx, 'stay', 'service');
			self::approveMoongcleTagConnections($stay->stay_idx, 'stay', 'pet');
			self::approveMoongcleTagConnections($stay->stay_idx, 'stay', 'barrierfree_public');
			self::approveMoongcleTagConnections($stay->stay_idx, 'stay', 'barrierfree_room');

			self::approveImages($stay->stay_idx, 'stay');
			self::approveCancelRules($input['partnerIdx']);

			$stayDraft->is_approved = true;
			$stayDraft->save();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '파트너를 승인했습니다.'
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '',
				'error' => $e->getMessage()
			], 500);
		}
	}

	private function saveTagConnectionsDraft($stayId, $newTags, $subType)
	{
		// 1. 태그 ID 추출 (모든 접두사에서 숫자만 추출)
		$tagIds = array_map(function ($tag) {
			// 태그에서 숫자 부분만 추출 (접두사 상관없이)
			return (int)preg_replace('/\D/', '', $tag);
		}, $newTags);

		$moongcleTags = MoongcleTag::whereIn('tag_idx', $tagIds)
			->pluck('tag_machine_name')
			->toArray();

		// 1. 기존 태그 연결 가져오기
		$existingTags = TagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', 'stay')
			->where('connection_type', $subType)
			->pluck('tag_machine_name')
			->toArray();

		// 2. 기존 태그 중에서 새로 전달받은 태그에 없는 태그 삭제
		$tagsToDelete = array_diff($existingTags, $moongcleTags);
		if (!empty($tagsToDelete)) {
			TagConnectionDraft::where('item_idx', $stayId)
				->where('item_type', 'stay')
				->where('connection_type', $subType)
				->whereIn('tag_machine_name', $tagsToDelete)
				->delete();
		}

		// 3. 새로 전달받은 태그 중 기존에 없는 태그 추가
		$tagsToAdd = array_diff($moongcleTags, $existingTags);
		foreach ($tagsToAdd as $tagMachineName) {
			$tag = Tag::where('tag_machine_name', $tagMachineName)->first();

			TagConnectionDraft::create([
				'tag_idx' => $tag->tag_idx,
				'tag_name' => $tag->tag_name,
				'tag_machine_name' => $tag->tag_machine_name,
				'item_idx' => $stayId,
				'item_type' => 'stay',
				'connection_type' => $subType,
				'is_approved' => false
			]);
		}
	}

	private function saveMoongcleTagConnectionsDraft($stayId, $newTags, $subType)
	{
		// 1. 태그 ID 추출 (모든 접두사에서 숫자만 추출)
		$tagIds = array_map(function ($tag) {
			// 태그에서 숫자 부분만 추출 (접두사 상관없이)
			return (int)preg_replace('/\D/', '', $tag);
		}, $newTags);

		// 1. 기존 태그 연결 가져오기
		$existingTags = MoongcleTagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', 'stay')
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		// 2. 기존 태그 중에서 새로 전달받은 태그에 없는 태그 삭제
		$tagsToDelete = array_diff($existingTags, $tagIds);
		if (!empty($tagsToDelete)) {
			MoongcleTagConnectionDraft::where('item_idx', $stayId)
				->where('item_type', 'stay')
				->where('connection_type', $subType)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		// 3. 새로 전달받은 태그 중 기존에 없는 태그 추가
		$tagsToAdd = array_diff($tagIds, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			$moongcleTag = MoongcleTag::find($tagId);

			MoongcleTagConnectionDraft::create([
				'tag_idx' => $tagId,
				'tag_name' => $moongcleTag->tag_name,
				'tag_machine_name' => $moongcleTag->tag_machine_name,
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
					foreach ($newImages as $index => $newImg) {
						if ($newImg['image_origin_path'] === $existingImage->image_origin_path) {
							// 이미지 순서 업데이트
							$existingImage->image_order = $index;
							$existingImage->save();
						}
					}
				}
			}
		}

		$existingImagePaths = $existingImages->pluck('image_origin_path')->toArray();

		// 2. 새로 추가된 이미지 저장
		foreach ($newImages as $index => $newImg) {
			// 기존 이미지에 해당 이미지가 없을 때만 새로 저장
			if (!in_array($newImg['image_origin_path'], $existingImagePaths)) {
				ImageDraft::create([
					'image_entity_id' => $stayId,
					'image_entity_type' => $newImg['image_entity_type'],
					'image_type' => $newImg['image_type'],
					'image_origin_name' => $newImg['image_origin_name'],
					'image_origin_path' => $newImg['image_origin_path'],
					'image_origin_size' => filesize($_ENV['ROOT_DIRECTORY'] . '/public' . $newImg['image_origin_path']),
					'image_small_path' => $newImg['image_small_path'],
					'image_normal_path' => $newImg['image_normal_path'],
					'image_big_path' => $newImg['image_big_path'],
					'image_order' => $index,  // 이미지 순서 저장
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

	private static function approveTagConnections($stayId, $category, $subType)
	{
		// Draft와 기존 승인된 데이터를 비교하여 처리
		$existingTags = TagConnection::where('item_idx', $stayId)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		$draftTags = TagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		// 1. 기존 태그 중에서 Draft에 없는 태그 삭제
		$tagsToDelete = array_diff($existingTags, $draftTags);
		if (!empty($tagsToDelete)) {
			TagConnection::where('item_idx', $stayId)
				->where('item_type', $category)
				->where('connection_type', $subType)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		// 2. Draft 태그 중 기존에 없는 태그 추가
		$tagsToAdd = array_diff($draftTags, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			$tag = Tag::find($tagId);

			TagConnection::create([
				'tag_idx' => $tagId,
				'tag_name' => $tag->tag_name,
				'tag_machine_name' => $tag->tag_machine_name,
				'item_idx' => $stayId,
				'item_type' => $category,
				'connection_type' => $subType,
			]);
		}

		TagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->update(['is_approved' => true]);
	}

	private static function approveMoongcleTagConnections($stayId, $category, $subType)
	{
		// Draft와 기존 승인된 데이터를 비교하여 처리
		$existingTags = MoongcleTagConnection::where('item_idx', $stayId)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		$draftTags = MoongcleTagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		// 1. 기존 태그 중에서 Draft에 없는 태그 삭제
		$tagsToDelete = array_diff($existingTags, $draftTags);
		if (!empty($tagsToDelete)) {
			MoongcleTagConnection::where('item_idx', $stayId)
				->where('item_type', $category)
				->where('connection_type', $subType)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		// 2. Draft 태그 중 기존에 없는 태그 추가
		$tagsToAdd = array_diff($draftTags, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			$moongcleTag = MoongcleTag::find($tagId);

			MoongcleTagConnection::create([
				'tag_idx' => $tagId,
				'tag_name' => $moongcleTag->tag_name,
				'tag_machine_name' => $moongcleTag->tag_machine_name,
				'item_idx' => $stayId,
				'item_type' => $category,
				'connection_type' => $subType,
			]);
		}

		MoongcleTagConnectionDraft::where('item_idx', $stayId)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->update(['is_approved' => true]);
	}

	private static function approveImages($stayId, $category)
	{
		$existingImages = Image::where('image_entity_id', $stayId)
			->where('image_entity_type', $category)
			->get()
			->keyBy('image_origin_path');

		$draftImages = ImageDraft::where('image_entity_id', $stayId)
			->where('image_entity_type', $category)
			->get()
			->keyBy('image_origin_path');

		$imagesToDelete = array_diff_key($existingImages->toArray(), $draftImages->toArray());
		if (!empty($imagesToDelete)) {
			Image::where('image_entity_id', $stayId)
				->where('image_entity_type', $category)
				->whereIn('image_origin_path', array_keys($imagesToDelete))
				->delete();
		}

		$imagesToAdd = array_diff_key($draftImages->toArray(), $existingImages->toArray());
		foreach ($imagesToAdd as $draftImage) {
			Image::create([
				'image_entity_id' => $stayId,
				'image_entity_type' => $category,
				'image_type' => $draftImage['image_type'],
				'image_origin_name' => $draftImage['image_origin_name'],
				'image_origin_path' => $draftImage['image_origin_path'],
				'image_origin_size' => $draftImage['image_origin_size'],
				'image_small_path' => $draftImage['image_small_path'],
				'image_normal_path' => $draftImage['image_normal_path'],
				'image_big_path' => $draftImage['image_big_path'],
				'image_order' => $draftImage['image_order'],
			]);
		}

		foreach ($existingImages as $path => $existingImage) {
			if (isset($draftImages[$path]) && $existingImage->image_order != $draftImages[$path]->image_order) {
				Image::where('image_entity_id', $stayId)
					->where('image_entity_type', $category)
					->where('image_origin_path', $path)
					->update(['image_order' => $draftImages[$path]->image_order]);
			}
		}

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

		$commonRules = array_intersect($existingRules, $draftRules);
		foreach ($commonRules as $ruleDay) {
			$existingRule = CancelRule::where('partner_idx', $partnerIdx)
				->where('cancel_rules_day', $ruleDay)
				->first();

			$draftRule = CancelRuleDraft::where('partner_idx', $partnerIdx)
				->where('cancel_rules_day', $ruleDay)
				->first();

			// 기존 데이터와 Draft 데이터가 다를 경우 업데이트
			if ($existingRule && $draftRule) {
				if (
					$existingRule->cancel_rules_order !== $draftRule->cancel_rules_order ||
					$existingRule->cancel_rules_percent !== $draftRule->cancel_rules_percent ||
					$existingRule->cancel_rules_time !== $draftRule->cancel_rules_time
				) {
					$existingRule->update([
						'cancel_rules_order' => $draftRule->cancel_rules_order,
						'cancel_rules_percent' => $draftRule->cancel_rules_percent,
						'cancel_rules_time' => $draftRule->cancel_rules_time,
						'is_approved' => true,
					]);
				}
			}
		}

		CancelRuleDraft::where('partner_idx', $partnerIdx)
			->update(['is_approved' => true]);
	}

	public static function partnerShowMain()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx1']) || empty($input['listType'])) {
			return ResponseHelper::jsonResponse(['error' => '필수 필드가 누락되었습니다.'], 400);
		}

		$listType = $input['listType'];

		$partnerList = [];
		foreach ($input as $key => $val) {
			if (strpos($key, 'partnerIdx') === 0 && is_numeric($val)) {
				$partnerList[] = (int)$val;
			}
		}

		if (empty($partnerList)) {
			return ResponseHelper::jsonResponse(['error' => '등록할 파트너가 없습니다.'], 400);
		}

		MainViewList::where('list_type', $listType)->delete();

		foreach ($partnerList as $index => $partnerIdx) {
			$mainViewList = new MainViewList();
			$mainViewList->partner_idx = $partnerIdx;
			$mainViewList->list_type = $listType;
			$mainViewList->list_order = $index + 1;
			$mainViewList->save();
		}

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '파트너 노출 정보를 수정했습니다.',
			'data' => []
		], 200);
	}
}
