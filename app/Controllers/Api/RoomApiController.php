<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomDraft;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\Image;
use App\Models\ImageDraft;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Database;

use Carbon\Carbon;

class RoomApiController
{
	// Room 생성 및 업데이트 API
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
		if (empty($input['roomMainImage']) || empty($input['roomSubImage'])) {
			return ResponseHelper::jsonResponse(['error' => '필수 필드가 누락되었습니다.'], 400);
		}

		// DB 트랜잭션 시작
		Capsule::beginTransaction();

		try {
			$partner = Partner::find($input['partnerIdx']);

			if (!empty($input['roomIdx'])) {
				$room = Room::where('room_idx', $input['roomIdx'])->first();
				$RoomDraft = $room->draft;
			} else {
				$room = new Room();
				$room->partner_idx = $input['partnerIdx'];
				$room->room_name = $input['roomName'];
				$room->room_status = 'disabled';
				$room->room_created_at = Carbon::now();
				$room->room_updated_at = Carbon::now();
				$room->save();

				$RoomDraft = new RoomDraft();
				$RoomDraft->room_idx = $room->room_idx;
				$RoomDraft->room_is_approved = false;
			}

			self::saveTagConnectionsDraft($room->room_idx, $input['amenities'], 'amenity');
			self::saveTagConnectionsDraft($room->room_idx, $input['barrierfreeRoom'], 'barrierfree_room');
			self::saveTagConnectionsDraft($room->room_idx, $input['view'], 'view');

			self::saveImagesDraft($room->room_idx, $input['roomMainImage'], 'main');
			self::saveImagesDraft($room->room_idx, $input['roomSubImage'], 'sub');

			$RoomDraft->room_name = $input['roomName'];
			$RoomDraft->room_bed_type = $input['roomBeds'];
			$RoomDraft->room_size = $input['roomSize'];
			$RoomDraft->room_child_age = $input['childAge'];
			$RoomDraft->room_tiny_month = $input['tinyMonth'];
			$RoomDraft->room_standard_person = $input['standardPerson'];
			$RoomDraft->room_max_person = $input['maxPerson'];
			$RoomDraft->room_adult_additional_price = $input['adultAdditionalPrice'];
			$RoomDraft->room_child_additional_price = $input['childAdditionalPrice'];
			$RoomDraft->room_tiny_additional_price = $input['tinyAdditionalPrice'];
			$RoomDraft->room_other_notes = $input['roomOtherNotes'];
			$RoomDraft->room_is_approved = false;
			$RoomDraft->save();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse([
				'message' => '객실 정보가 성공적으로 저장되었습니다.',
				'partner_id' => $partner->partner_idx,
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	private function saveTagConnectionsDraft($roomId, $newTags, $subType)
	{
		// 1. 태그 ID 추출 (모든 접두사에서 숫자만 추출)
		$tagIds = array_map(function ($tag) {
			// 태그에서 숫자 부분만 추출 (접두사 상관없이)
			return (int)preg_replace('/\D/', '', $tag);
		}, $newTags);

		// 1. 기존 태그 연결 가져오기
		$existingTags = TagConnectionDraft::where('item_idx', $roomId)
			->where('item_type', 'room')
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		// 2. 기존 태그 중에서 새로 전달받은 태그에 없는 태그 삭제
		$tagsToDelete = array_diff($existingTags, $tagIds);
		if (!empty($tagsToDelete)) {
			TagConnectionDraft::where('item_idx', $roomId)
				->where('item_type', 'room')
				->where('connection_type', $subType)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		// 3. 새로 전달받은 태그 중 기존에 없는 태그 추가
		$tagsToAdd = array_diff($tagIds, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			TagConnectionDraft::create([
				'tag_idx' => $tagId,
				'item_idx' => $roomId,
				'item_type' => 'room',
				'connection_type' => $subType,
				'is_approved' => false
			]);
		}
	}

	private function saveImagesDraft($roomId, $newImages, $imageType)
	{
		$newImages = isset($newImages) ? json_decode($newImages, true) : [];

		foreach ($newImages as &$newImage) {
			if (strpos($newImage['image_origin_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_origin_path'];
				$newPath = str_replace('/noid/', "/$roomId/", $oldPath);

				// 디렉토리가 없는 경우 생성
				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				// 파일 이동
				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_origin_path'] = str_replace('/noid/', "/$roomId/", $newImage['image_origin_path']);
				}
			}
			if (strpos($newImage['image_small_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_small_path'];
				$newPath = str_replace('/noid/', "/$roomId/", $oldPath);

				// 디렉토리가 없는 경우 생성
				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				// 파일 이동
				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_small_path'] = str_replace('/noid/', "/$roomId/", $newImage['image_small_path']);
				}
			}
			if (strpos($newImage['image_normal_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_normal_path'];
				$newPath = str_replace('/noid/', "/$roomId/", $oldPath);

				// 디렉토리가 없는 경우 생성
				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				// 파일 이동
				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_normal_path'] = str_replace('/noid/', "/$roomId/", $newImage['image_normal_path']);
				}
			}
			if (strpos($newImage['image_big_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_big_path'];
				$newPath = str_replace('/noid/', "/$roomId/", $oldPath);

				// 디렉토리가 없는 경우 생성
				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				// 파일 이동
				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_big_path'] = str_replace('/noid/', "/$roomId/", $newImage['image_big_path']);
				}
			}
		}

		// 1. 기존 이미지 가져오기
		$existingImages = ImageDraft::where('image_entity_id', $roomId)
			->where('image_entity_type', 'room')
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
					'image_entity_id' => $roomId,
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

			$room = $partner->partnerDetail();  // 파트너와 연결된 Room 정보
			if (!$room) {
				return ResponseHelper::jsonResponse(['error' => '승인할 숙소 정보가 없습니다.'], 404);
			}

			// 초안 데이터를 가져오기
			$RoomDraft = $room->draft;
			if (!$RoomDraft) {
				return ResponseHelper::jsonResponse(['error' => '승인할 초안이 없습니다.'], 404);
			}

			// DB 트랜잭션 시작
			Capsule::beginTransaction();

			// 1. 기본 정보 업데이트
			$room->room_checkin_rule = $RoomDraft->room_checkin_rule;
			$room->room_checkout_rule = $RoomDraft->room_checkout_rule;
			$room->room_basic_info = $RoomDraft->room_basic_info;
			$room->room_important_info = $RoomDraft->room_important_info;
			$room->room_amenity_info = $RoomDraft->room_amenity_info;
			$room->room_breakfast_info = $RoomDraft->room_breakfast_info;
			$room->room_personnel_info = $RoomDraft->room_personnel_info;
			$room->room_cancel_info = $RoomDraft->room_cancel_info;
			$room->room_status = 'enabled';
			$room->room_updated_at = Carbon::now();  // 업데이트 시간 저장
			$room->save();

			// 2. 태그 연결 승인 (draft에서 실제로 반영)
			self::approveTagConnections($room->room_idx, 'room');

			// 3. 이미지 승인 (draft 이미지들을 실제로 반영)
			self::approveImages($room->room_idx, 'room');

			// 4. 취소 규칙 승인
			self::approveCancelRules($input['partnerIdx']);

			// 5. 초안 상태 업데이트 (승인됨으로 표시)
			$RoomDraft->is_approved = true;
			$RoomDraft->save();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse([
				'message' => '숙소 정보가 성공적으로 승인되었습니다.',
				'room_id' => $room->room_idx,
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	private static function approveTagConnections($roomId, $category)
	{
		// Draft와 기존 승인된 데이터를 비교하여 처리
		$existingTags = TagConnection::where('item_idx', $roomId)
			->where('item_type', $category)
			->pluck('tag_idx')
			->toArray();

		$draftTags = TagConnectionDraft::where('item_idx', $roomId)
			->where('item_type', $category)
			->pluck('tag_idx')
			->toArray();

		// 1. 기존 태그 중에서 Draft에 없는 태그 삭제
		$tagsToDelete = array_diff($existingTags, $draftTags);
		if (!empty($tagsToDelete)) {
			TagConnection::where('item_idx', $roomId)
				->where('item_type', $category)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		// 2. Draft 태그 중 기존에 없는 태그 추가
		$tagsToAdd = array_diff($draftTags, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			TagConnection::create([
				'tag_idx' => $tagId,
				'item_idx' => $roomId,
				'item_type' => $category,
			]);
		}

		// Draft는 삭제하지 않고 상태만 업데이트
		TagConnectionDraft::where('item_idx', $roomId)
			->where('item_type', $category)
			->update(['is_approved' => true]);
	}

	private static function approveImages($roomId, $category)
	{
		// 기존 승인된 이미지와 Draft 이미지를 비교
		$existingImages = Image::where('image_entity_id', $roomId)
			->where('image_entity_type', $category)
			->pluck('image_origin_path')
			->toArray();

		$draftImages = ImageDraft::where('image_entity_id', $roomId)
			->where('image_entity_type', $category)
			->pluck('image_origin_path')
			->toArray();

		// 1. 기존 이미지 중에서 Draft에 없는 이미지 삭제
		$imagesToDelete = array_diff($existingImages, $draftImages);
		if (!empty($imagesToDelete)) {
			Image::where('image_entity_id', $roomId)
				->where('image_entity_type', $category)
				->whereIn('image_origin_path', $imagesToDelete)
				->delete();
		}

		// 2. Draft 이미지 중 기존에 없는 이미지 추가
		$imagesToAdd = array_diff($draftImages, $existingImages);
		foreach ($imagesToAdd as $draftImage) {
			$imageData = ImageDraft::where('image_origin_path', $draftImage)->first();
			Image::create([
				'image_entity_id' => $roomId,
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
		ImageDraft::where('image_entity_id', $roomId)
			->where('image_entity_type', $category)
			->update(['is_approved' => true]);
	}

	public function toggleRoomActive($roomIdx)
	{
		try {
			// DB 트랜잭션 시작
			Capsule::beginTransaction();

			// roomIdx로 객실 찾기
			$room = Room::find($roomIdx);

			if (!$room) {
				return ResponseHelper::jsonResponse(['error' => 'Room not found'], 404);
			}

			// room_status 상태 반전

			if ($room->room_status === 'enabled') $room->room_status = 'disabled';
			else $room->room_status = 'enabled';
			$room->save();

			// DB 커밋
			Capsule::commit();

			// 성공적인 응답 반환
			return ResponseHelper::jsonResponse([
				'message' => 'Room active status updated successfully',
				'room_status' => $room->room_status
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => 'Failed to toggle room active status'], 500);
		}
	}

	public function roomDetailInfo($roomIdx)
	{
		$partner = null;
		$bindings = [];
		$roomTagList = [];

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
            WHERE 
                r.room_idx = :roomIdx
            ORDER BY r.room_order ASC, r.room_idx ASC;
        ";

        $bindings = [
            'roomIdx' => $roomIdx
        ];

        // 쿼리 실행
        $room = Database::getInstance()->getConnection()->select($sql, $bindings);
		$room = $room[0];
		$partner = Partner::find($room->partner_idx);

		$sql = "
            SELECT 
				MAX(room_price_additional_adult) AS max_additional_adult,
				MAX(room_price_additional_child) AS max_additional_child,
				MAX(room_price_additional_tiny) AS max_additional_tiny
			FROM room_prices
			WHERE room_idx = :roomIdx
        ";

        $bindings = [
            'roomIdx' => $roomIdx
        ];

        // 쿼리 실행
        $extraCharge = Database::getInstance()->getConnection()->select($sql, $bindings);
		$extraCharge = $extraCharge[0];

		$sql = "
            SELECT
                tc.*
            FROM rooms r
            LEFT JOIN tag_connections tc ON r.room_idx = tc.item_idx AND tc.item_type = 'room'
            WHERE r.room_idx = :roomIdx
        ";

        $roomTags = Database::getInstance()->getConnection()->select($sql, $bindings);
		foreach ($roomTags as $tags) {
            if (!isset($roomTagList[$tags->connection_type])) {
                $roomTagList[$tags->connection_type] = [];
            }

            $roomTagList[$tags->connection_type][] = $tags;
        }

		// 템플릿 파일 불러오기
		ob_start();
		include $_ENV['ROOT_DIRECTORY'] . '/public' . "/../app/Views/app/blocks/room-detail-full-modal.php";
		$html = ob_get_clean();

		// JSON으로 HTML 전송
		echo json_encode(['html' => $html]);
	}
}
