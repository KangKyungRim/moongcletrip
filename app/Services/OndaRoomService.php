<?php

namespace App\Services;


use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomDraft;
use App\Models\Tag;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\Image;
use App\Models\ImageDraft;

class OndaRoomService
{
	private Client $client;
	private $ondaDomain;
	private $ondaKey;

	public function __construct()
	{
		if ($_ENV['ONDA_ENV'] == 'production') {
			$this->ondaDomain = $_ENV['ONDA_PRODUCTION_DOMAIN'];
			$this->ondaKey = $_ENV['ONDA_PRODUCTION_KEY'];
		} else {
			$this->ondaDomain = $_ENV['ONDA_DEVELOPMENT_DOMAIN'];
			$this->ondaKey = $_ENV['ONDA_DEVELOPMENT_KEY'];
		}

		$this->client = new Client([
			'headers' => [
				'Authorization' => $this->ondaKey,
				'accept' => 'application/json',
			],
		]);
	}

	// public function fetchRoomtypes(int $partnerIdx): array
	// {
	// 	$response = $this->client->request('GET', $this->ondaDomain . "/properties/{$partnerIdx}/roomtypes");
	// 	return json_decode($response->getBody(), true)['roomtypes'];
	// }

	public function fetchRoomtypes(int $partnerIdx): array
	{
		try {
			// 요청 실행
			$response = $this->client->request('GET', $this->ondaDomain . "/properties/{$partnerIdx}/roomtypes");
			return json_decode($response->getBody(), true)['roomtypes'];
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			// 404나 기타 클라이언트 오류 처리
			error_log("Client error: " . $e->getMessage());
			return []; // 기본 값 반환
		} catch (\Exception $e) {
			// 기타 예외 처리
			error_log("Unexpected error: " . $e->getMessage());
			return []; // 기본 값 반환
		}
	}

	public function fetchRoomtypeDetails(int $partnerIdx, int $roomtypeIdx): array
	{
		$response = $this->client->request('GET', $this->ondaDomain . "/properties/{$partnerIdx}/roomtypes/{$roomtypeIdx}");
		return json_decode($response->getBody(), true)['roomtype'];
	}

	public function saveRoomtypes(Partner $partner): void
	{
		$roomtypes = $this->fetchRoomtypes($partner->partner_onda_idx);

		foreach ($roomtypes as $roomtype) {
			$room = Room::firstOrNew([
				'room_onda_idx' => intval($roomtype['id']),
				'room_thirdparty' => 'onda',
			]);

			if (strpos($roomtype['name'], '숙박세일페스타') !== false) {
				$roomtype['name'] = str_replace('숙박세일페스타', '깜짝할인', $roomtype['name']);
			}

			$room->fill([
				'partner_idx' => $partner->partner_idx,
				'room_name' => $roomtype['name'],
				'room_onda_idx' => intval($roomtype['id']),
				'room_thirdparty' => 'onda',
				'room_status' => $roomtype['status'],
				'room_updated_at' => Carbon::now(),
			])->save();

			$roomDraft = $room->draft ?: new RoomDraft();
			$roomDraft->fill([
				'room_idx' => $room->room_idx,
				'room_name' => $room->room_name,
				'room_onda_idx' => $room->room_onda_idx,
				'room_thirdparty' => $room->room_thirdparty,
				'room_is_approved' => true,
				'updated_at' => Carbon::now(),
			])->save();
		}
	}

	public function saveRoomtypeDetails(Room $room): void
	{
		$partner = Partner::find($room->partner_idx);
		if (!$partner) {
			throw new \Exception("Partner not found for room ID {$room->room_idx}");
		}

		$roomtype = $this->fetchRoomtypeDetails($partner->partner_onda_idx, $room->room_onda_idx);

		if (strpos($roomtype['name'], '숙박세일페스타') !== false) {
			$roomtype['name'] = str_replace('숙박세일페스타', '깜짝할인', $roomtype['name']);
		}

		$room->fill([
			'room_name' => $roomtype['name'],
			'room_status' => $roomtype['status'],
			'room_size' => $roomtype['size'],
			'room_standard_person' => $roomtype['capacity']['standard'],
			'room_max_person' => $roomtype['capacity']['max'],
			'room_other_notes' => $roomtype['description'],
			'room_details' => $roomtype['details'],
			'room_bed_type' => $roomtype['bedtype'],
			'room_updated_at' => Carbon::now(),
		])->save();

		$roomDraft = $room->draft ?: new RoomDraft();
		$roomDraft->fill([
			'room_name' => $roomtype['name'],
			'room_size' => $roomtype['size'],
			'room_standard_person' => $roomtype['capacity']['standard'],
			'room_max_person' => $roomtype['capacity']['max'],
			'room_other_notes' => $roomtype['description'],
			'room_details' => $roomtype['details'],
			'room_bed_type' => $roomtype['bedtype'],
			'room_is_approved' => true,
			'updated_at' => Carbon::now(),
		])->save();

		$this->saveTagConnections($room->room_idx, $roomtype['tags']);
		$this->saveImages($room->room_idx, $roomtype['images'], 'basic');
	}

	private function saveTagConnections(int $roomId, array $newTags): void
	{
		$mappingTags = $this->mapTags($newTags);
		foreach ($mappingTags as $subType => $tags) {
			$existingTagsInDB = Tag::whereIn('tag_name', $tags)
				->pluck('tag_name', 'tag_idx')
				->toArray();

			$tagsToCreate = array_diff($tags, array_values($existingTagsInDB));

			foreach ($tagsToCreate as $newTag) {
				$tag = Tag::create([
					'tag_name' => $newTag,
					'tag_machine_name' => '',
				]);

				$existingTagsInDB[$tag->tag_idx] = $tag->tag_name;
			}

			$tagIds = array_keys($existingTagsInDB);

			$existingTags = TagConnectionDraft::where('item_idx', $roomId)
				->where('item_type', 'room')
				->where('connection_type', $subType)
				->pluck('tag_idx')
				->toArray();

			// 태그 별도 입력 후 해당 로직이 실행되면 별도 입력된 태그들이 모두 삭제될 것이기에 삭제 로직은 보류
			$tagsToDelete = array_diff($existingTags, $tagIds);
			if (!empty($tagsToDelete)) {
				TagConnectionDraft::where('item_idx', $roomId)
					->where('item_type', 'room')
					->where('connection_type', $subType)
					->where('editor', 'onda')
					->whereIn('tag_idx', $tagsToDelete)
					->delete();

				TagConnection::where('item_idx', $roomId)
					->where('item_type', 'room')
					->where('connection_type', $subType)
					->where('editor', 'onda')
					->whereIn('tag_idx', $tagsToDelete)
					->delete();
			}

			$tagsToAdd = array_diff($tagIds, $existingTags);
			foreach ($tagsToAdd as $tagId) {
				$tag = Tag::find($tagId);

				TagConnectionDraft::create([
					'tag_idx' => $tagId,
					'tag_name' => $tag->tag_name,
					'tag_machine_name' => $tag->tag_machine_name,
					'item_idx' => $roomId,
					'item_type' => 'room',
					'connection_type' => $subType,
					'editor' => 'onda',
					'is_approved' => true,
				]);
				TagConnection::create([
					'tag_idx' => $tagId,
					'tag_name' => $tag->tag_name,
					'tag_machine_name' => $tag->tag_machine_name,
					'item_idx' => $roomId,
					'item_type' => 'room',
					'connection_type' => $subType,
					'editor' => 'onda',
				]);
			}
		}
	}

	private function mapTags(array $newTags): array
	{
		return [
			'roomtype' => $newTags['roomtypes'] ?? [],
			'view' => $newTags['views'] ?? [],
			'room_amenity' => $newTags['amenities'] ?? []
		];
	}

	private function saveImages(int $roomId, array $ondaImages, string $imageType): void
	{
		$newImages = [];
		$newImagePaths = [];

		foreach ($ondaImages as $ondaImage) {
			$originalFile = basename($ondaImage['original']);
			$smallFile = basename($ondaImage['250px']);
			$normalFile = basename($ondaImage['500px']);
			$bigFile = basename($ondaImage['1000px']);

			$originalFile = str_replace('-original.', '.', $originalFile);
			$smallFile = str_replace('-250.', '.', $smallFile);
			$normalFile = str_replace('-500.', '.', $normalFile);
			$bigFile = str_replace('-1000.', '.', $bigFile);

			$originalPath = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/onda/room/' . $roomId . '/originals/';
			if (!is_dir($originalPath)) {
				mkdir($originalPath, 0777, true);
			}
			$smallPath = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/onda/room/' . $roomId . '/small/';
			if (!is_dir($smallPath)) {
				mkdir($smallPath, 0777, true);
			}
			$normalPath = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/onda/room/' . $roomId . '/normal/';
			if (!is_dir($normalPath)) {
				mkdir($normalPath, 0777, true);
			}
			$bigPath = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/onda/room/' . $roomId . '/big/';
			if (!is_dir($bigPath)) {
				mkdir($bigPath, 0777, true);
			}

			$originalFilePath = $originalPath . $originalFile;
			if (!file_exists($originalFilePath)) {
				$originalImage = file_get_contents($ondaImage['original']);
				file_put_contents($originalFilePath, $originalImage);
			}

			$smallFilePath = $smallPath . $smallFile;
			if (!file_exists($smallFilePath)) {
				$smallImage = file_get_contents($ondaImage['250px']);
				file_put_contents($smallFilePath, $smallImage);
			}

			$normalFilePath = $normalPath . $normalFile;
			if (!file_exists($normalFilePath)) {
				$normalImage = file_get_contents($ondaImage['500px']);
				file_put_contents($normalFilePath, $normalImage);
			}

			$bigFilePath = $bigPath . $bigFile;
			if (!file_exists($bigFilePath)) {
				$bigImage = file_get_contents($ondaImage['1000px']);
				file_put_contents($bigFilePath, $bigImage);
			}

			array_push($newImagePaths, '/uploads/onda/room/' . $roomId . '/originals/' . $originalFile);
			array_push($newImages, array(
				'image_entity_type' => 'room',
				'image_type' => $imageType,
				'image_origin_name' => $originalFile,
				'image_origin_path' => '/uploads/onda/room/' . $roomId . '/originals/' . $originalFile,
				'image_small_path' => '/uploads/onda/room/' . $roomId . '/small/' . $smallFile,
				'image_normal_path' => '/uploads/onda/room/' . $roomId . '/normal/' . $normalFile,
				'image_big_path' => '/uploads/onda/room/' . $roomId . '/big/' . $bigFile,
				'image_order' => $ondaImage['order'],
			));
		}

		// 1. 기존 이미지 가져오기
		$existingImages = ImageDraft::where('image_entity_id', $roomId)
			->where('image_entity_type', 'room')
			->where('image_type', $imageType)
			->get();

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
							$existingImage->image_order = $index;
							$existingImage->updated_at = Carbon::now();
							$existingImage->save();
						}
					}
				}
			}
		}

		$existingImagePaths = $existingImages->pluck('image_origin_path')->toArray();

		// 2. 새로 추가된 이미지 저장
		foreach ($newImages as $newImage) {
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
					'image_big_path' => $newImage['image_big_path'],
					'image_order' => $newImage['image_order'],
					'is_approved' => true
				]);
			}
		}

		// 1. 기존 이미지 가져오기
		$existingImages = Image::where('image_entity_id', $roomId)
			->where('image_entity_type', 'room')
			->where('image_type', $imageType)
			->get();

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
							$existingImage->image_order = $newImage['image_order'];
							$existingImage->image_updated_at = Carbon::now();
							$existingImage->save();
						}
					}
				}
			}
		}

		$existingImagePaths = $existingImages->pluck('image_origin_path')->toArray();

		// 2. 새로 추가된 이미지 저장
		foreach ($newImages as $index => $newImage) {
			// 기존 이미지에 해당 이미지가 없을 때만 새로 저장
			if (!in_array($newImage['image_origin_path'], $existingImagePaths)) {
				Image::create([
					'image_entity_id' => $roomId,
					'image_entity_type' => $newImage['image_entity_type'],
					'image_type' => $newImage['image_type'],
					'image_origin_name' => $newImage['image_origin_name'],
					'image_origin_path' => $newImage['image_origin_path'],
					'image_origin_size' => filesize($_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_origin_path']),
					'image_small_path' => $newImage['image_small_path'],
					'image_normal_path' => $newImage['image_normal_path'],
					'image_big_path' => $newImage['image_big_path'],
					'image_order' => $newImage['image_order'],
				]);
			}
		}
	}
}
