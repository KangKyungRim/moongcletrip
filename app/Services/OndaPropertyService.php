<?php

namespace App\Services;

use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Models\Partner;
use App\Models\PartnerDraft;
use App\Models\Stay;
use App\Models\StayDraft;
use App\Models\CancelRule;
use App\Models\CancelRuleDraft;
use App\Models\Tag;
use App\Models\TagConnection;
use App\Models\TagConnectionDraft;
use App\Models\Image;
use App\Models\ImageDraft;

class OndaPropertyService
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

	public function fetchAllProperties(): array
	{
		$response = $this->client->request('GET', $this->ondaDomain . '/properties');
		return json_decode($response->getBody(), true)['properties'];
	}

	public function fetchPropertyDetails(int $propertyIdx): array
	{
		$response = $this->client->request('GET', $this->ondaDomain . '/properties/' . $propertyIdx);
		return json_decode($response->getBody(), true)['property'];
	}

	public function saveOrUpdatePartner(array $property): Partner
	{
		$partner = Partner::firstOrNew([
			'partner_onda_idx' => intval($property['id']),
			'partner_thirdparty' => 'onda'
		]);

		$partner->partner_name = $property['name'];
		$partner->partner_onda_idx = intval($property['id']);
		$partner->partner_thirdparty = 'onda';
		$partner->partner_status = $property['status'];
		$partner->partner_updated_at = Carbon::now();
		$partner->save();

		$draft = PartnerDraft::where('partner_idx', $partner->partner_idx)->first();
		if(empty($draft)) {
			$draft = new PartnerDraft();
		}
		$draft->partner_idx = $partner->partner_idx;
		$draft->partner_onda_idx = $partner->partner_onda_idx;
		$draft->partner_thirdparty = $partner->partner_thirdparty;
		$draft->partner_name = $partner->partner_name;
		$draft->is_approved = true;
		$draft->save();

		return $partner;
	}

	public function updatePartnerDetails(Partner $partner, array $property): void
	{
		$partner->fill([
			'partner_name' => $property['name'],
			'partner_category' => 'stay',
			'partner_zip' => $property['address']['postal_code'],
			'partner_address1' => $property['address']['address1'],
			'partner_address2' => $property['address']['address2'],
			'partner_address3' => $property['address']['address_detail'],
			'partner_city' => $property['address']['city'],
			'partner_region' => $property['address']['region'],
			'partner_latitude' => $property['address']['location']['latitude'],
			'partner_longitude' => $property['address']['location']['longitude'],
			'partner_phonenumber' => $property['phone'],
			'partner_email' => $property['email'],
			'partner_status' => $property['status'],
		]);
		$partner->partner_updated_at = Carbon::now();
		$partner->save();

		$partnerDraft = PartnerDraft::where('partner_idx', $partner->partner_idx)->first();
		if(empty($partnerDraft)) {
			$partnerDraft = new PartnerDraft();
		}
		$partnerDraft->fill([
			'partner_name' => $property['name'],
			'partner_category' => 'stay',
			'partner_zip' => $property['address']['postal_code'],
			'partner_address1' => $property['address']['address1'],
			'partner_address2' => $property['address']['address2'],
			'partner_address3' => $property['address']['address_detail'],
			'partner_city' => $property['address']['city'],
			'partner_region' => $property['address']['region'],
			'partner_latitude' => $property['address']['location']['latitude'],
			'partner_longitude' => $property['address']['location']['longitude'],
			'partner_phonenumber' => $property['phone'],
			'partner_email' => $property['email'],
			'is_approved' => true,
			'updated_at' => Carbon::now(),
		])->save();
	}

	public function saveOrUpdateStay(Partner $partner, array $property): Stay
	{
		$stay = $partner->partnerDetail() ?: new Stay();
		$stay->fill([
			'stay_checkin_rule' => $property['checkin'],
			'stay_checkout_rule' => $property['checkout'],
			'stay_basic_info' => $property['descriptions']['property'],
			'stay_important_info' => $property['descriptions']['reservation'],
			'stay_notice_info' => $property['descriptions']['notice'],
			'stay_cancel_info' => $property['descriptions']['refund'],
			'stay_status' => 'enabled',
		]);
		$stay->stay_updated_at = Carbon::now();
		$stay->save();

		$partner->partner_detail_idx = $stay->stay_idx;
		$partner->save();

		$stayDraft = $stay->draft ?: new StayDraft();
		$stayDraft->fill([
			'stay_idx' => $stay->stay_idx,
			'stay_checkin_rule' => $property['checkin'],
			'stay_checkout_rule' => $property['checkout'],
			'stay_basic_info' => $property['descriptions']['property'],
			'stay_important_info' => $property['descriptions']['reservation'],
			'stay_notice_info' => $property['descriptions']['notice'],
			'stay_cancel_info' => $property['descriptions']['refund'],
			'is_approved' => true,
			'updated_at' => Carbon::now(),
		])->save();

		return $stay;
	}

	public function saveTagConnections($stayId, $newTags): void
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

			$existingTags = TagConnectionDraft::where('item_idx', $stayId)
				->where('item_type', 'stay')
				->where('connection_type', $subType)
				->pluck('tag_idx')
				->toArray();

			// 태그 별도 입력 후 해당 로직이 실행되면 별도 입력된 태그들이 모두 삭제될 것이기에 삭제 로직은 보류
			$tagsToDelete = array_diff($existingTags, $tagIds);
			if (!empty($tagsToDelete)) {
				TagConnectionDraft::where('item_idx', $stayId)
					->where('item_type', 'stay')
					->where('connection_type', $subType)
					->where('editor', 'onda')
					->whereIn('tag_idx', $tagsToDelete)
					->delete();

				TagConnection::where('item_idx', $stayId)
					->where('item_type', 'stay')
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
					'item_idx' => $stayId,
					'item_type' => 'stay',
					'connection_type' => $subType,
					'editor' => 'onda',
					'is_approved' => true,
				]);
				TagConnection::create([
					'tag_idx' => $tagId,
					'tag_name' => $tag->tag_name,
					'tag_machine_name' => $tag->tag_machine_name,
					'item_idx' => $stayId,
					'item_type' => 'stay',
					'connection_type' => $subType,
					'editor' => 'onda',
				]);
			}
		}
	}

	private function mapTags(array $newTags): array
	{
		return [
			'stay_type' => $newTags['classifications'] ?? [],
			'stay_type_detail' => $newTags['properties'] ?? [],
			'facility' => $newTags['facilities'] ?? [],
			'service' => $newTags['services'] ?? [],
			'attraction' => $newTags['attractions'] ?? [],
		];
	}

	public function saveImages($stayId, $ondaImages, $imageType): void
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

			$originalPath = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/onda/stay/' . $stayId . '/originals/';
			if (!is_dir($originalPath)) {
				mkdir($originalPath, 0777, true);
			}
			$smallPath = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/onda/stay/' . $stayId . '/small/';
			if (!is_dir($smallPath)) {
				mkdir($smallPath, 0777, true);
			}
			$normalPath = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/onda/stay/' . $stayId . '/normal/';
			if (!is_dir($normalPath)) {
				mkdir($normalPath, 0777, true);
			}
			$bigPath = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/onda/stay/' . $stayId . '/big/';
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

			array_push($newImagePaths, '/uploads/onda/stay/' . $stayId . '/originals/' . $originalFile);
			array_push($newImages, array(
				'image_entity_type' => 'stay',
				'image_type' => $imageType,
				'image_origin_name' => $originalFile,
				'image_origin_path' => '/uploads/onda/stay/' . $stayId . '/originals/' . $originalFile,
				'image_small_path' => '/uploads/onda/stay/' . $stayId . '/small/' . $smallFile,
				'image_normal_path' => '/uploads/onda/stay/' . $stayId . '/normal/' . $normalFile,
				'image_big_path' => '/uploads/onda/stay/' . $stayId . '/big/' . $bigFile,
				'image_order' => $ondaImage['order'],
			));
		}

		// 1. 기존 이미지 가져오기
		$existingImages = ImageDraft::where('image_entity_id', $stayId)
			->where('image_entity_type', 'stay')
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
					'image_entity_id' => $stayId,
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
		$existingImages = Image::where('image_entity_id', $stayId)
			->where('image_entity_type', 'stay')
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
					'image_entity_id' => $stayId,
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

	public function saveCancelRules($partnerIdx, $refundRules): void
	{
		// 새로운 규칙 배열을 저장할 공간
		$newCancelRulesData = [];
		$index = 0;

		// 1. 기존 규칙을 가져옴
		$existingCancelRules = CancelRuleDraft::where('partner_idx', $partnerIdx)->orderBy('cancel_rules_order')->get();
		$existingCancelRulesFinal = CancelRule::where('partner_idx', $partnerIdx)->orderBy('cancel_rules_order')->get();

		foreach ($refundRules as $day => $percent) {
			$unit = substr($day, -1);
			$value = intval(substr($day, 0, -1));

			switch ($unit) {
				case 'd':  // 일 단위 그대로 사용
					$dayInDays = $value;
					break;
				case 'w':  // 주 단위는 7일로 계산
					$dayInDays = $value * 7;
					break;
				case 'm':  // 월 단위는 30일로 계산
					$dayInDays = $value * 30;
					break;
				default:
					$dayInDays = $value;
					break;
			}

			// 2. 기존 규칙이 있는 경우 업데이트, 없는 경우 새로운 규칙 추가
			if (isset($existingCancelRules[$index])) {
				$existingCancelRules[$index]->update([
					'cancel_rules_order' => $index + 1,
					'cancel_rules_day' => $dayInDays,
					'cancel_rules_percent' => $percent,
					'is_approved' => true,
					'updated_at' => Carbon::now(),
				]);
			} else {
				$newCancelRulesData[] = [
					'partner_idx' => $partnerIdx,
					'cancel_rules_order' => $index + 1,
					'cancel_rules_day' => $dayInDays,
					'cancel_rules_percent' => $percent,
					'is_approved' => true,
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
				];
			}

			if (isset($existingCancelRulesFinal[$index])) {
				$existingCancelRulesFinal[$index]->update([
					'cancel_rules_order' => $index + 1,
					'cancel_rules_day' => $dayInDays,
					'cancel_rules_percent' => $percent,
					'cancel_rules_updated_at' => Carbon::now(),
				]);
			} else {
				$newCancelRulesDataFinal[] = [
					'partner_idx' => $partnerIdx,
					'cancel_rules_order' => $index + 1,
					'cancel_rules_day' => $dayInDays,
					'cancel_rules_percent' => $percent,
					'cancel_rules_created_at' => Carbon::now(),
					'cancel_rules_updated_at' => Carbon::now(),
				];
			}

			$index++;
		}

		// 3. 초과된 기존 규칙을 삭제
		if ($index < $existingCancelRules->count()) {
			CancelRuleDraft::where('partner_idx', $partnerIdx)
				->where('cancel_rules_order', '>', $index)
				->delete();
		}

		if ($index < $existingCancelRulesFinal->count()) {
			CancelRule::where('partner_idx', $partnerIdx)
				->where('cancel_rules_order', '>', $index)
				->delete();
		}

		// 4. 새로운 규칙이 있는 경우 추가로 삽입
		if (!empty($newCancelRulesData)) {
			CancelRuleDraft::insert($newCancelRulesData);
		}

		if (!empty($newCancelRulesDataFinal)) {
			CancelRule::insert($newCancelRulesDataFinal);
		}
	}
}
