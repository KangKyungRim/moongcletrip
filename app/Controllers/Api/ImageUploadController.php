<?php

namespace App\Controllers\Api;

use App\Helpers\ImageUploadService;
use App\Helpers\ImageUploadAwsService;
use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use App\Models\ImageDraft;

class ImageUploadController
{
	public function uploadTempImage()
	{
		$files = $_FILES['image'];  // 여러 파일 또는 단일 파일 모두 받을 수 있음
		$entityId = $_POST['entity_id'];
		$entityType = $_POST['entity_type'];
		$imageType = $_POST['image_type'];

		$imageService = new ImageUploadService();
		$uploadedImages = [];

		// 단일 파일 업로드 처리
		if (!is_array($files['tmp_name'])) {
			// 파일이 하나인 경우 배열로 만들어 처리
			$files = [
				'tmp_name' => [$files['tmp_name']],
				'name' => [$files['name']],
				'type' => [$files['type']],
				'extension' => pathinfo($files['name'], PATHINFO_EXTENSION)  // 확장자 추출
			];
		}

		// 업로드된 모든 파일을 처리
		foreach ($files['tmp_name'] as $index => $tmpName) {
			// 각각의 파일을 처리하여 업로드
			$imageData = $imageService->uploadImage(
				$tmpName,                // 임시 파일 경로
				$files['name'][$index],  // 원본 파일 이름
				$files['type'][$index],  // 파일 MIME 타입
				pathinfo($files['name'][$index], PATHINFO_EXTENSION),
				$entityType,
				$entityId,
				$imageType
			);

			if (!empty($imageData)) $uploadedImages[] = $imageData;  // 업로드된 이미지 정보를 저장
		}

		// 모든 업로드된 이미지를 JSON으로 응답
		return json_encode([
			'message' => '이미지가 성공적으로 업로드되었습니다.',
			'uploaded_images' => $uploadedImages
		]);
	}

	public function uploadCuratedTempImage()
	{
		$files = $_FILES['image'];  // 여러 파일 또는 단일 파일 모두 받을 수 있음
		$entityId = $_POST['entity_id'];
		$entityType = $_POST['entity_type'];
		$imageType = $_POST['image_type'];

		$imageService = new ImageUploadService();
		$uploadedImages = [];

		// 단일 파일 업로드 처리
		if (!is_array($files['tmp_name'])) {
			// 파일이 하나인 경우 배열로 만들어 처리
			$files = [
				'tmp_name' => [$files['tmp_name']],
				'name' => [$files['name']],
				'type' => [$files['type']],
				'extension' => pathinfo($files['name'], PATHINFO_EXTENSION)  // 확장자 추출
			];
		}

		// 업로드된 모든 파일을 처리
		foreach ($files['tmp_name'] as $index => $tmpName) {
			// 각각의 파일을 처리하여 업로드
			$imageData = $imageService->uploadCuratedImage(
				$tmpName,                // 임시 파일 경로
				$files['name'][$index],  // 원본 파일 이름
				$files['type'][$index],  // 파일 MIME 타입
				pathinfo($files['name'][$index], PATHINFO_EXTENSION),
				$entityType,
				$entityId,
				$imageType
			);

			if (!empty($imageData)) $uploadedImages[] = $imageData;  // 업로드된 이미지 정보를 저장
		}

		// 모든 업로드된 이미지를 JSON으로 응답
		return json_encode([
			'message' => '이미지가 성공적으로 업로드되었습니다.',
			'uploaded_images' => $uploadedImages
		]);
	}

	public function uploadMoongclePointTempImage()
	{
		$files = $_FILES['image'];  // 여러 파일 또는 단일 파일 모두 받을 수 있음
		$entityId = $_POST['entity_id'];

		$imageService = new ImageUploadService();
		$uploadedImages = [];

		// 단일 파일 업로드 처리
		if (!is_array($files['tmp_name'])) {
			// 파일이 하나인 경우 배열로 만들어 처리
			$files = [
				'tmp_name' => [$files['tmp_name']],
				'name' => [$files['name']],
				'type' => [$files['type']],
				'extension' => pathinfo($files['name'], PATHINFO_EXTENSION)  // 확장자 추출
			];
		}

		// 업로드된 모든 파일을 처리
		foreach ($files['tmp_name'] as $index => $tmpName) {
			// 각각의 파일을 처리하여 업로드
			$imageData = $imageService->uploadMoongclePointImage(
				$tmpName,                // 임시 파일 경로
				$files['name'][$index],  // 원본 파일 이름
				$files['type'][$index],  // 파일 MIME 타입
				pathinfo($files['name'][$index], PATHINFO_EXTENSION),
				$entityId
			);

			if (!empty($imageData)) $uploadedImages[] = $imageData;  // 업로드된 이미지 정보를 저장
		}

		// 모든 업로드된 이미지를 JSON으로 응답
		return json_encode([
			'message' => '이미지가 성공적으로 업로드되었습니다.',
			'uploaded_images' => $uploadedImages
		]);
	}

	public function uploadFacilityDetailTempImage()
	{
		$files = $_FILES['image'];  // 여러 파일 또는 단일 파일 모두 받을 수 있음
		$entityId = $_POST['entity_id'];

		$imageService = new ImageUploadService();
		$uploadedImages = [];

		// 단일 파일 업로드 처리
		if (!is_array($files['tmp_name'])) {
			// 파일이 하나인 경우 배열로 만들어 처리
			$files = [
				'tmp_name' => [$files['tmp_name']],
				'name' => [$files['name']],
				'type' => [$files['type']],
				'extension' => pathinfo($files['name'], PATHINFO_EXTENSION)  // 확장자 추출
			];
		}

		// 업로드된 모든 파일을 처리
		foreach ($files['tmp_name'] as $index => $tmpName) {
			// 각각의 파일을 처리하여 업로드
			$imageData = $imageService->uploadFacilityDetailImage(
				$tmpName,                // 임시 파일 경로
				$files['name'][$index],  // 원본 파일 이름
				$files['type'][$index],  // 파일 MIME 타입
				pathinfo($files['name'][$index], PATHINFO_EXTENSION),
				$entityId
			);

			if (!empty($imageData)) $uploadedImages[] = $imageData;  // 업로드된 이미지 정보를 저장
		}

		// 모든 업로드된 이미지를 JSON으로 응답
		return json_encode([
			'message' => '이미지가 성공적으로 업로드되었습니다.',
			'uploaded_images' => $uploadedImages
		]);
	}

	/**
	 * 큐레이터 아이템 썸네일 이미지 등록
	 * curation_items 의 Thumbnail (숙소)
	 */
	public function uploadCurationThumbnailImage() {
		
		try {
			$checkUser = MiddleHelper::checkPartnerLoginCookie();

			if (!$checkUser || $checkUser->partner_user_level < 4) {
				return ResponseHelper::jsonResponse2([
					'header'    => [
						'success' => false,
						'message' => '403 Unauthorized',    
					]
				], 403);
			}

			if (!isset($_FILES['image'])) {
				return ResponseHelper::jsonResponse2([
					'header' => ['success' => false, 'message' => 'image 필드가 없습니다.']
				], 400);
			}

			$files = self::normalizeFiles($_FILES['image']);
			if (empty($files)) {
				return ResponseHelper::jsonResponse2([
					'header' => ['success' => false, 'message' => '업로드할 파일이 없습니다.']
				], 400);
			}

			$imageService = new ImageUploadAwsService();

			// 허용 MIME
			$allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];

			$uploadedImages = [];
			$errors = [];

			// 업로드된 모든 파일을 처리
			foreach ($files as $f) {
				$tmp = $f['tmp_name'];
				// MIME 결정: 파일에서 우선, 실패 시 폼의 type 보조
				$mime = @mime_content_type($tmp) ?: ($f['type'] ?? 'application/octet-stream');
				if (!isset($allowed[$mime])) {
					$errors[] = ['name'=>$f['name'],'error'=>'허용되지 않는 이미지 형식'];
					continue;
				}
				$ext  = $allowed[$mime];

				// 키 생성: {env}/curations/YYYY/MM/{uuid}.{ext}
				$env  = $_ENV['APP_ENV'] ?? 'development';
				$uuid = bin2hex(random_bytes(16));
				$key  = sprintf('%s/curations/%s/%s/%s.%s', $env, date('Y'), date('m'), $uuid, $ext);

				// 업로드
				$s3Url = $imageService->upload($tmp, $key, $mime);
				if (!$s3Url) {
					$errors[] = ['name'=>$f['name'],'error'=>'S3 업로드 실패'];
					continue;
				}

				$cdnUrl = $imageService->getCloudFrontUrl($key);
				$uploadedImages[] = [
					'orgFileName' => $f['name'],
					'key'  => $key,
					'cdn'  => $cdnUrl,
					'mime' => $mime,
					'size' => $f['size'] ?? null
				];
			}

			if (empty($uploadedImages)) {
				return ResponseHelper::jsonResponse2([
					'header' => ['success' => false, 'message' => '업로드 실패'],
					'errors' => $errors
				], 400);
			}

			return ResponseHelper::jsonResponse2([
				'header' => ['success' => true, 'message' => '이미지가 성공적으로 업로드되었습니다.'],
				'body'   => $uploadedImages,
				'errors' => $errors // 부분 실패가 있으면 참고용
			], 200);
		} catch (Exception $e) {
            return ResponseHelper::jsonResponse2(['header' => ['success' => false, 'message' => $e->getMessage()]], 500);
        }
	}

	/**
	 * 큐레이터 아이템 썸네일 이미지 삭제
	 */
	public function deleteCurationThumbnailImage() {
		try {
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
			$fileKey = $input['key'] ?? null;
			if (empty($fileKey)) {
				return ResponseHelper::jsonResponse2([
					'header' => ['success' => false, 'message' => '삭제할 파일 key가 존재하지 않습니다.']
				], 400);
			}
	
	
			$imageService = new ImageUploadAwsService();
			$imageService->deleteObjectByKey($fileKey);
	
			return ResponseHelper::jsonResponse2([
                'header' => ['success' => true, 'message' => '이미지가 성공적으로 삭제되었습니다.'],
            ], 200);
		} catch (Exception $e) {
            return ResponseHelper::jsonResponse2(['header' => ['success' => false, 'message' => $e->getMessage()]], 500);
        }
	}

	private static function normalizeFiles(array $files): array {
		// 단일 업로드를 다중 형태로 정규화
		if (isset($files['tmp_name']) && is_string($files['tmp_name'])) {
			if (($files['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || empty($files['tmp_name']) || !is_file($files['tmp_name'])) {
				return [];
			}
			return [[
				'name'     => $files['name'] ?? '',
				'type'     => $files['type'] ?? '',
				'tmp_name' => $files['tmp_name'],
				'error'    => $files['error'] ?? UPLOAD_ERR_OK,
				'size'     => $files['size'] ?? 0,
			]];
		}

		// 다중 업로드
		if (isset($files['tmp_name']) && is_array($files['tmp_name'])) {
			$out = [];
			$count = count($files['tmp_name']);
			for ($i = 0; $i < $count; $i++) {
				if (($files['error'][$i] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) continue;
				if (empty($files['tmp_name'][$i]) || !is_file($files['tmp_name'][$i])) continue;
				$out[] = [
					'name'     => $files['name'][$i] ?? '',
					'type'     => $files['type'][$i] ?? '',
					'tmp_name' => $files['tmp_name'][$i],
					'error'    => $files['error'][$i] ?? UPLOAD_ERR_OK,
					'size'     => $files['size'][$i] ?? 0,
				];
			}
			return $out;
		}
		return [];
	}

	public function saveImageMetadata()
	{
		$imageDataList = json_decode(file_get_contents('php://input'), true);

		foreach ($imageDataList as $imageData) {
			ImageDraft::create([
				'image_entity_id' => $imageData['entity_id'],
				'image_entity_type' => $imageData['entity_type'],
				'image_type' => $imageData['image_type'],
				'image_origin_name' => $imageData['image_origin_name'],
				'image_origin_path' => $imageData['image_origin_path'],
				'image_small_path' => $imageData['image_small_path'],
				'image_normal_path' => $imageData['image_normal_path'],
				'image_big_path' => $imageData['image_big_path'],
			]);
		}

		return json_encode(['message' => '이미지 정보가 성공적으로 저장되었습니다.']);
	}
}
