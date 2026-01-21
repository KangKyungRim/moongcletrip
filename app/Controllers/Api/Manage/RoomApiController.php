<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomDraft;
use App\Models\Tag;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\MoongcleTagConnection;
use App\Models\MoongcleTagConnectionDraft;
use App\Models\Image;
use App\Models\ImageDraft;
use App\Models\MoongcleTag;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

// 도미토리	dormitory_beds
// 싱글 베드	single_beds
// 슈퍼 싱글 베드	super_single_beds
// 세미 더블 베드	semi_double_beds
// 더블 베드	double_beds
// 퀸 베드	queen_beds
// 킹 베드	king_beds
// 할리우드 베드 hollywood_twin_beds
// 이층 침대	double_story_beds
// 벙크 베드	bunk_beds
// 간이 침대	rollaway_beds
// 요이불 세트 futon_beds
// 캡슐 침대	capsule_beds
// 소파 베드	sofa_beds
// 에어 베드 air_beds

class RoomApiController
{
	public static function createRoom()
	{
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
				'message' => '파트너를 선택해주세요.',
				'error' => ''
			], 400);
		}

		$partner = Partner::find($input['partnerIdx']);

		$room = new Room();
		$room->room_thirdparty = $partner->partner_thirdparty;
		$room->room_name = $input['roomName'];
		$room->partner_idx = $input['partnerIdx'];
		$room->save();

		$roomDraft = new RoomDraft();
		$roomDraft->room_idx = $room->room_idx;
		$roomDraft->room_thirdparty = $partner->partner_thirdparty;
		$roomDraft->room_order = $input['roomOrder'] ?? 0;
		$roomDraft->room_name = $input['roomName'];
		$roomDraft->room_details = $input['roomDetails'];
		$roomDraft->room_bed_type = $input['roomBeds'];
		$roomDraft->room_size = $input['roomSize'];
		$roomDraft->room_child_age = $input['childAge'];
		$roomDraft->room_tiny_month = $input['infantMonth'];
		$roomDraft->room_standard_person = $input['standardPerson'];
		$roomDraft->room_max_person = $input['maxPerson'];
		$roomDraft->room_adult_additional_price = $input['extraAdultFee'];
		$roomDraft->room_child_additional_price = $input['extraChildFee'];
		$roomDraft->room_tiny_additional_price = $input['extraInfantFee'];
		$roomDraft->room_other_notes = $input['roomOtherNotes'];
		$roomDraft->room_is_approved = false;
		$roomDraft->save();

		self::saveTagConnectionsDraft($room->room_idx, $input['view'], 'view');
		self::saveTagConnectionsDraft($room->room_idx, $input['roomtype'], 'roomtype');
		self::saveTagConnectionsDraft($room->room_idx, $input['amenity'], 'room_amenity');
		self::saveTagConnectionsDraft($room->room_idx, $input['barrierfreeRoom'], 'barrierfree_room');

		self::saveMoongcleTagConnectionsDraft($room->room_idx, $input['view'], 'view');
		self::saveMoongcleTagConnectionsDraft($room->room_idx, $input['roomtype'], 'roomtype');
		self::saveMoongcleTagConnectionsDraft($room->room_idx, $input['amenity'], 'room_amenity');
		self::saveMoongcleTagConnectionsDraft($room->room_idx, $input['barrierfreeRoom'], 'barrierfree_room');

		self::saveImagesDraft($room->room_idx, $input['roomBasicImage'], 'basic');

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '신규 객실을 생성했습니다.',
			'data' => [
				'partnerIdx' => $partner->partner_idx,
				'roomIdx' => $room->room_idx
			]
		], 200);
	}

	public static function editRoom()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['roomIdx'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 항목이 비어있습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$room = Room::find($input['roomIdx']);
			$roomDraft = $room->draft;

			$roomDraft->room_order = $input['roomOrder'] ?? 0;
			$roomDraft->room_name = $input['roomName'];
			$roomDraft->room_details = $input['roomDetails'];
			$roomDraft->room_bed_type = $input['roomBeds'];
			$roomDraft->room_size = $input['roomSize'];
			$roomDraft->room_child_age = $input['childAge'];
			$roomDraft->room_tiny_month = $input['infantMonth'];
			$roomDraft->room_standard_person = $input['standardPerson'];
			$roomDraft->room_max_person = $input['maxPerson'];
			$roomDraft->room_adult_additional_price = $input['extraAdultFee'];
			$roomDraft->room_child_additional_price = $input['extraChildFee'];
			$roomDraft->room_tiny_additional_price = $input['extraInfantFee'];
			$roomDraft->room_other_notes = $input['roomOtherNotes'];
			$roomDraft->room_is_approved = false;
			$roomDraft->save();

			self::saveTagConnectionsDraft($room->room_idx, $input['view'], 'view');
			self::saveTagConnectionsDraft($room->room_idx, $input['roomtype'], 'roomtype');
			self::saveTagConnectionsDraft($room->room_idx, $input['amenity'], 'room_amenity');
			self::saveTagConnectionsDraft($room->room_idx, $input['barrierfreeRoom'], 'barrierfree_room');

			self::saveMoongcleTagConnectionsDraft($room->room_idx, $input['view'], 'view');
			self::saveMoongcleTagConnectionsDraft($room->room_idx, $input['roomtype'], 'roomtype');
			self::saveMoongcleTagConnectionsDraft($room->room_idx, $input['amenity'], 'room_amenity');
			self::saveMoongcleTagConnectionsDraft($room->room_idx, $input['barrierfreeRoom'], 'barrierfree_room');

			self::saveImagesDraft($room->room_idx, $input['roomBasicImage'], 'basic');

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '객실을 성공적으로 저장했습니다.',
				'data' => [
					'roomIdx' => $room->room_idx,
				]
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '객실 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function copyRoom()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['roomIdx'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 항목이 비어있습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$originalRoom = Room::find($input['roomIdx']);
			$originalRoomDraft = $originalRoom->draft;

			if ($originalRoom) {
				$newRoom = $originalRoom->replicate();
				$newRoom->room_name = $originalRoom->room_name . ' - 복제본';
				$newRoom->room_status = 'disabled';
				$newRoom->save();
			}

			if ($originalRoomDraft) {
				$newRoomDraft = $originalRoomDraft->replicate();
				$newRoomDraft->room_idx = $newRoom->room_idx;
				$newRoomDraft->room_name = $originalRoomDraft->room_name . ' - 복제본';
				$newRoomDraft->save();
			}

			if ($newRoom && $newRoomDraft) {
				$tagConnectionsDraft = TagConnectionDraft::where('item_idx', $originalRoom->room_idx)
					->where('item_type', 'room')
					->get();

				foreach ($tagConnectionsDraft as $tagConnection) {
					$newTagConnection = $tagConnection->replicate();
					$newTagConnection->item_idx = $newRoom->room_idx;
					$newTagConnection->save();
				}

				$moongcleTagConnectionsDraft = MoongcleTagConnectionDraft::where('item_idx', $originalRoom->room_idx)
					->where('item_type', 'room')
					->get();

				foreach ($moongcleTagConnectionsDraft as $tagConnection) {
					$newTagConnection = $tagConnection->replicate();
					$newTagConnection->item_idx = $newRoom->room_idx;
					$newTagConnection->save();
				}

				$tagConnections = TagConnection::where('item_idx', $originalRoom->room_idx)
					->where('item_type', 'room')
					->get();

				foreach ($tagConnections as $tagConnection) {
					$newTagConnection = $tagConnection->replicate();
					$newTagConnection->item_idx = $newRoom->room_idx;
					$newTagConnection->save();
				}

				$moongcleTagConnections = MoongcleTagConnection::where('item_idx', $originalRoom->room_idx)
					->where('item_type', 'room')
					->get();

				foreach ($moongcleTagConnections as $tagConnection) {
					$newTagConnection = $tagConnection->replicate();
					$newTagConnection->item_idx = $newRoom->room_idx;
					$newTagConnection->save();
				}

				$imagesDraft = ImageDraft::where('image_entity_id', $originalRoom->room_idx)
					->where('image_entity_type', 'room')
					->get();

				foreach ($imagesDraft as $image) {
					$newImage = $image->replicate();
					$newImage->image_entity_id = $newRoom->room_idx;
					$newImage->save();
				}

				$images = Image::where('image_entity_id', $originalRoom->room_idx)
					->where('image_entity_type', 'room')
					->get();

				foreach ($images as $image) {
					$newImage = $image->replicate();
					$newImage->image_entity_id = $newRoom->room_idx;
					$newImage->save();
				}
			} else {
				Capsule::rollBack();
				return ResponseHelper::jsonResponse([
					'success' => false,
					'message' => '객실 저장에 실패했습니다.',
					'error' => $e->getMessage()
				], 500);
			}

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '객실을 성공적으로 복제했습니다.',
				'data' => [
					'roomIdx' => $newRoom->room_idx,
				]
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '객실 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function changeRoomOrder()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['rooms'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 항목이 비어있습니다.',
				'error' => ''
			], 400);
		}

		try {
			foreach ($input['rooms'] as $roomOrder => $roomIdx) {
				$room = Room::find($roomIdx);

				if ($room->partner_idx == $input['partnerIdx']) {
					$room->room_order = $roomOrder;
					$room->save();

					$RoomDraft = $room->draft;
					$RoomDraft->room_order = $roomOrder;
					$RoomDraft->save();
				}
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '순서를 변경했습니다.'
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '',
				'error' => $e->getMessage()
			], 500);
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

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '파트너를 선택해주세요.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$room = Room::find($input['roomIdx']);
			$roomDraft = $room->draft;

			$room->room_name = $roomDraft->room_name;
			$room->room_details = $roomDraft->room_details;
			$room->room_bed_type = $roomDraft->room_bed_type;
			$room->room_size = $roomDraft->room_size;
			$room->room_child_age = $roomDraft->room_child_age;
			$room->room_tiny_month = $roomDraft->room_tiny_month;
			$room->room_standard_person = $roomDraft->room_standard_person;
			$room->room_max_person = $roomDraft->room_max_person;
			$room->room_adult_additional_price = $roomDraft->room_adult_additional_price;
			$room->room_child_additional_price = $roomDraft->room_child_additional_price;
			$room->room_tiny_additional_price = $roomDraft->room_tiny_additional_price;
			$room->room_other_notes = $roomDraft->room_other_notes;
			$room->save();

			$roomDraft->room_is_approved = true;
			$roomDraft->save();

			self::approveTagConnections($room->room_idx, 'room', 'view');
			self::approveTagConnections($room->room_idx, 'room', 'roomtype');
			self::approveTagConnections($room->room_idx, 'room', 'room_amenity');
			self::approveTagConnections($room->room_idx, 'room', 'barrierfree_room');

			self::approveMoongcleTagConnections($room->room_idx, 'room', 'view');
			self::approveMoongcleTagConnections($room->room_idx, 'room', 'roomtype');
			self::approveMoongcleTagConnections($room->room_idx, 'room', 'room_amenity');
			self::approveMoongcleTagConnections($room->room_idx, 'room', 'barrierfree_room');

			self::approveImages($room->room_idx, 'room');

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '객실을 승인했습니다.'
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '',
				'error' => $e->getMessage()
			], 500);
		}
	}

	private function saveTagConnectionsDraft($roomIdx, $newTags, $subType)
	{
		$tagIds = array_map(function ($tag) {
			return (int)preg_replace('/\D/', '', $tag);
		}, $newTags);

		$moongcleTags = MoongcleTag::whereIn('tag_idx', $tagIds)
			->pluck('tag_machine_name')
			->toArray();

		$existingTags = TagConnectionDraft::where('item_idx', $roomIdx)
			->where('item_type', 'room')
			->where('connection_type', $subType)
			->pluck('tag_machine_name')
			->toArray();

		$tagsToDelete = array_diff($existingTags, $moongcleTags);
		if (!empty($tagsToDelete)) {
			TagConnectionDraft::where('item_idx', $roomIdx)
				->where('item_type', 'room')
				->where('connection_type', $subType)
				->whereIn('tag_machine_name', $tagsToDelete)
				->delete();
		}

		$tagsToAdd = array_diff($moongcleTags, $existingTags);
		foreach ($tagsToAdd as $tagMachineName) {
			$tag = Tag::where('tag_machine_name', $tagMachineName)->first();

			TagConnectionDraft::create([
				'tag_idx' => $tag->tag_idx,
				'tag_name' => $tag->tag_name,
				'tag_machine_name' => $tag->tag_machine_name,
				'item_idx' => $roomIdx,
				'item_type' => 'room',
				'connection_type' => $subType,
				'is_approved' => false
			]);
		}
	}

	private function saveMoongcleTagConnectionsDraft($roomIdx, $newTags, $subType)
	{
		$tagIds = array_map(function ($tag) {
			return (int)preg_replace('/\D/', '', $tag);
		}, $newTags);

		$existingTags = MoongcleTagConnectionDraft::where('item_idx', $roomIdx)
			->where('item_type', 'room')
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		$tagsToDelete = array_diff($existingTags, $tagIds);
		if (!empty($tagsToDelete)) {
			MoongcleTagConnectionDraft::where('item_idx', $roomIdx)
				->where('item_type', 'room')
				->where('connection_type', $subType)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		$tagsToAdd = array_diff($tagIds, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			$moongcleTag = MoongcleTag::find($tagId);

			MoongcleTagConnectionDraft::create([
				'tag_idx' => $tagId,
				'tag_name' => $moongcleTag->tag_name,
				'tag_machine_name' => $moongcleTag->tag_machine_name,
				'item_idx' => $roomIdx,
				'item_type' => 'room',
				'connection_type' => $subType,
				'is_approved' => false
			]);
		}
	}

	private function saveImagesDraft($roomIdx, $newImages, $imageType)
	{
		$newImages = isset($newImages) ? json_decode($newImages, true) : [];

		foreach ($newImages as &$newImage) {
			if (strpos($newImage['image_origin_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_origin_path'];
				$newPath = str_replace('/noid/', "/$roomIdx/", $oldPath);

				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_origin_path'] = str_replace('/noid/', "/$roomIdx/", $newImage['image_origin_path']);
				}
			}
			if (strpos($newImage['image_small_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_small_path'];
				$newPath = str_replace('/noid/', "/$roomIdx/", $oldPath);

				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_small_path'] = str_replace('/noid/', "/$roomIdx/", $newImage['image_small_path']);
				}
			}
			if (strpos($newImage['image_normal_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_normal_path'];
				$newPath = str_replace('/noid/', "/$roomIdx/", $oldPath);

				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_normal_path'] = str_replace('/noid/', "/$roomIdx/", $newImage['image_normal_path']);
				}
			}
			if (strpos($newImage['image_big_path'], '/noid/') !== false) {
				$oldPath = $_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_big_path'];
				$newPath = str_replace('/noid/', "/$roomIdx/", $oldPath);

				$newDir = dirname($newPath);
				if (!is_dir($newDir)) {
					mkdir($newDir, 0777, true);
				}

				if (file_exists($oldPath)) {
					rename($oldPath, $newPath);
					$newImage['image_big_path'] = str_replace('/noid/', "/$roomIdx/", $newImage['image_big_path']);
				}
			}
		}

		$existingImages = ImageDraft::where('image_entity_id', $roomIdx)
			->where('image_entity_type', 'room')
			->where('image_type', $imageType)
			->get();

		$newImagePaths = array_column($newImages, 'image_origin_path');

		if (empty($newImages)) {
			foreach ($existingImages as $existingImage) {
				// 이미지 파일 삭제 (필요 시)
				// unlink(public_path($existingImage->image_origin_path));

				$existingImage->delete();
			}
		} else {
			foreach ($existingImages as $existingImage) {
				if (!in_array($existingImage->image_origin_path, $newImagePaths)) {
					// 이미지 파일 삭제 (필요 시)
					// unlink(public_path($existingImage->image_origin_path));

					$existingImage->delete();
				} else {
					foreach ($newImages as $index => $newImg) {
						if ($newImg['image_origin_path'] === $existingImage->image_origin_path) {
							$existingImage->image_order = $index + 1;
							$existingImage->save();
						}
					}
				}
			}
		}

		$existingImagePaths = $existingImages->pluck('image_origin_path')->toArray();

		foreach ($newImages as $index => $newImg) {
			if (!in_array($newImg['image_origin_path'], $existingImagePaths)) {
				ImageDraft::create([
					'image_entity_id' => $roomIdx,
					'image_entity_type' => $newImg['image_entity_type'],
					'image_type' => $newImg['image_type'],
					'image_origin_name' => $newImg['image_origin_name'],
					'image_origin_path' => $newImg['image_origin_path'],
					'image_origin_size' => filesize($_ENV['ROOT_DIRECTORY'] . '/public' . $newImg['image_origin_path']),
					'image_small_path' => $newImg['image_small_path'],
					'image_normal_path' => $newImg['image_normal_path'],
					'image_big_path' => $newImg['image_big_path'],
					'image_order' => $index + 1,
					'is_approved' => false
				]);
			}
		}
	}

	private static function approveTagConnections($roomIdx, $category, $subType)
	{
		$existingTags = TagConnection::where('item_idx', $roomIdx)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		$draftTags = TagConnectionDraft::where('item_idx', $roomIdx)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		$tagsToDelete = array_diff($existingTags, $draftTags);
		if (!empty($tagsToDelete)) {
			TagConnection::where('item_idx', $roomIdx)
				->where('item_type', $category)
				->where('connection_type', $subType)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		$tagsToAdd = array_diff($draftTags, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			$tag = Tag::find($tagId);

			TagConnection::create([
				'tag_idx' => $tagId,
				'tag_name' => $tag->tag_name,
				'tag_machine_name' => $tag->tag_machine_name,
				'item_idx' => $roomIdx,
				'item_type' => $category,
				'connection_type' => $subType,
			]);
		}

		TagConnectionDraft::where('item_idx', $roomIdx)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->update(['is_approved' => true]);
	}

	private static function approveMoongcleTagConnections($roomIdx, $category, $subType)
	{
		$existingTags = MoongcleTagConnection::where('item_idx', $roomIdx)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		$draftTags = MoongcleTagConnectionDraft::where('item_idx', $roomIdx)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->pluck('tag_idx')
			->toArray();

		$tagsToDelete = array_diff($existingTags, $draftTags);
		if (!empty($tagsToDelete)) {
			MoongcleTagConnection::where('item_idx', $roomIdx)
				->where('item_type', $category)
				->where('connection_type', $subType)
				->whereIn('tag_idx', $tagsToDelete)
				->delete();
		}

		$tagsToAdd = array_diff($draftTags, $existingTags);
		foreach ($tagsToAdd as $tagId) {
			$moongcleTag = MoongcleTag::find($tagId);

			MoongcleTagConnection::create([
				'tag_idx' => $tagId,
				'tag_name' => $moongcleTag->tag_name,
				'tag_machine_name' => $moongcleTag->tag_machine_name,
				'item_idx' => $roomIdx,
				'item_type' => $category,
				'connection_type' => $subType,
			]);
		}

		MoongcleTagConnectionDraft::where('item_idx', $roomIdx)
			->where('item_type', $category)
			->where('connection_type', $subType)
			->update(['is_approved' => true]);
	}

	private static function approveImages($roomIdx, $category)
	{
		$existingImages = Image::where('image_entity_id', $roomIdx)
			->where('image_entity_type', $category)
			->get()
			->keyBy('image_origin_path');

		$draftImages = ImageDraft::where('image_entity_id', $roomIdx)
			->where('image_entity_type', $category)
			->get()
			->keyBy('image_origin_path');

		$imagesToDelete = array_diff_key($existingImages->toArray(), $draftImages->toArray());
		if (!empty($imagesToDelete)) {
			Image::where('image_entity_id', $roomIdx)
				->where('image_entity_type', $category)
				->whereIn('image_origin_path', array_keys($imagesToDelete))
				->delete();
		}

		$imagesToAdd = array_diff_key($draftImages->toArray(), $existingImages->toArray());
		foreach ($imagesToAdd as $draftImage) {
			Image::create([
				'image_entity_id' => $roomIdx,
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
				Image::where('image_entity_id', $roomIdx)
					->where('image_entity_type', $category)
					->where('image_origin_path', $path)
					->update(['image_order' => $draftImages[$path]->image_order]);
			}
		}

		ImageDraft::where('image_entity_id', $roomIdx)
			->where('image_entity_type', $category)
			->update(['is_approved' => true]);
	}

	public static function editStatus()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['roomIdx']) || empty($input['roomStatus'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수값이 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$room = Room::find($input['roomIdx']);

			$room->room_status = $input['roomStatus'];
			$room->save();

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '객실 상태값을 변경했습니다.'
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
