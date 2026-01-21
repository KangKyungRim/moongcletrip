<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\CuratedImage;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class CurateImageApiController
{
	public static function curateImages()
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
		if (empty($input['mainImage'])) {
			return ResponseHelper::jsonResponse(['error' => '필수 필드가 누락되었습니다.'], 400);
		}

		// DB 트랜잭션 시작
		Capsule::beginTransaction();

		try {
			$partner = Partner::find($input['partnerIdx']);

			$stay = $partner->partnerDetail();

			self::saveImages($stay->stay_idx, $input['mainImage'], 'basic');

			$partner->image_curated = true;
			$partner->save();

			// DB 커밋
			Capsule::commit();

			// 성공 응답
			return ResponseHelper::jsonResponse([
				'message' => '이미지 정보가 성공적으로 저장되었습니다.',
				'partner_id' => $partner->partner_idx,
			], 200);
		} catch (\Exception $e) {
			// 오류 발생 시 롤백
			Capsule::rollBack();
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	private function saveImages($stayId, $newImages, $imageType)
	{
		$newImages = isset($newImages) ? json_decode($newImages, true) : [];

		// 1. 기존 이미지 가져오기
		$existingImages = CuratedImage::where('image_entity_id', $stayId)
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

		$existingImagePaths = $existingImages->pluck('image_origin_path')->toArray();

		// 2. 새로 추가된 이미지 저장
		foreach ($newImages as $index => $newImage) {
			// 기존 이미지에 해당 이미지가 없을 때만 새로 저장
			if (!in_array($newImage['image_origin_path'], $existingImagePaths)) {
				CuratedImage::create([
					'image_entity_id' => $stayId,
					'image_entity_type' => $newImage['image_entity_type'],
					'image_type' => $newImage['image_type'],
					'image_origin_name' => $newImage['image_origin_name'],
					'image_origin_path' => $newImage['image_origin_path'],
					'image_origin_size' => filesize($_ENV['ROOT_DIRECTORY'] . '/public' . $newImage['image_origin_path']),
					'image_small_path' => $newImage['image_small_path'],
					'image_normal_path' => $newImage['image_normal_path'],
					'image_big_path' => $newImage['image_big_path'],
					'image_order' => $index + 1,  // 이미지 순서 저장
				]);
			}
		}
	}
}
