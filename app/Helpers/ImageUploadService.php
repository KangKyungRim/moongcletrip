<?php

namespace App\Helpers;

use Intervention\Image\ImageManagerStatic as Image;

class ImageUploadService
{
	public function uploadImage($tmpName, $originalName, $mimeType, $extension, $entityType, $entityId, $imageType)
	{
		// 고유한 파일 이름 생성
		$uniqueFileName = bin2hex(random_bytes(16)) . '.' . $extension;

		// 파일 저장 경로 설정
		$uploadDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/partners/' . $entityType . '/' . $entityId . '/originals/';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);  // 디렉토리 생성
		}

		// 원본 이미지 경로
		$originPath = $uploadDir . $uniqueFileName;
		move_uploaded_file($tmpName, $originPath);

		// 1. 작은 이미지 경로 및 저장
		$smallDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/partners/' . $entityType . '/' . $entityId . '/small/';
		if (!is_dir($smallDir)) {
			mkdir($smallDir, 0777, true);  // 디렉토리 생성
		}
		$smallImage = Image::make($originPath)
			->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
			->resize(250, null, function ($constraint) {
				$constraint->aspectRatio(); // 원본 비율 유지
				$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
			});
		$smallPath = $smallDir . $uniqueFileName;
		$smallImage->save($smallPath);

		// 2. 중간 이미지 경로 및 저장
		$normalDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/partners/' . $entityType . '/' . $entityId . '/normal/';
		if (!is_dir($normalDir)) {
			mkdir($normalDir, 0777, true);  // 디렉토리 생성
		}
		$normalImage = Image::make($originPath)
			->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
			->resize(500, null, function ($constraint) {
				$constraint->aspectRatio(); // 원본 비율 유지
				$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
			});
		$normalPath = $normalDir . $uniqueFileName;
		$normalImage->save($normalPath);

		$bigDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/partners/' . $entityType . '/' . $entityId . '/big/';
		if (!is_dir($bigDir)) {
			mkdir($bigDir, 0777, true);  // 디렉토리 생성
		}
		$bigImage = Image::make($originPath)
			->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
			->resize(1000, null, function ($constraint) {
				$constraint->aspectRatio(); // 원본 비율 유지
				$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
			});
		$bigPath = $bigDir . $uniqueFileName;
		$bigImage->save($bigPath);

		// 업로드 결과 반환
		return [
			'image_origin_name' => $originalName,
			'image_origin_path' => '/uploads/partners/' . $entityType . '/' . $entityId . '/originals/' . $uniqueFileName,
			'image_small_path' => '/uploads/partners/' . $entityType . '/' . $entityId . '/small/' . $uniqueFileName,
			'image_normal_path' => '/uploads/partners/' . $entityType . '/' . $entityId . '/normal/' . $uniqueFileName,
			'image_big_path' => '/uploads/partners/' . $entityType . '/' . $entityId . '/big/' . $uniqueFileName,
			'image_entity_type' => $entityType,
			'image_type' => $imageType,  // 원하는 이미지 타입
		];
	}

	public function uploadCuratedImage($tmpName, $originalName, $mimeType, $extension, $entityType, $entityId, $imageType)
	{
		// 고유한 파일 이름 생성
		$uniqueFileName = bin2hex(random_bytes(16)) . '.' . $extension;

		// 파일 저장 경로 설정
		$uploadDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/partners-curated/' . $entityType . '/' . $entityId . '/originals/';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);  // 디렉토리 생성
		}

		// 원본 이미지 경로
		$originPath = $uploadDir . $uniqueFileName;
		move_uploaded_file($tmpName, $originPath);

		// 1. 작은 이미지 경로 및 저장
		$smallDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/partners-curated/' . $entityType . '/' . $entityId . '/small/';
		if (!is_dir($smallDir)) {
			mkdir($smallDir, 0777, true);  // 디렉토리 생성
		}
		$smallImage = Image::make($originPath)
			->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
			->resize(250, null, function ($constraint) {
				$constraint->aspectRatio(); // 원본 비율 유지
				$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
			});
		$smallPath = $smallDir . $uniqueFileName;
		$smallImage->save($smallPath);

		// 2. 중간 이미지 경로 및 저장
		$normalDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/partners-curated/' . $entityType . '/' . $entityId . '/normal/';
		if (!is_dir($normalDir)) {
			mkdir($normalDir, 0777, true);  // 디렉토리 생성
		}
		$normalImage = Image::make($originPath)
			->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
			->resize(500, null, function ($constraint) {
				$constraint->aspectRatio(); // 원본 비율 유지
				$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
			});
		$normalPath = $normalDir . $uniqueFileName;
		$normalImage->save($normalPath);

		$bigDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/partners-curated/' . $entityType . '/' . $entityId . '/big/';
		if (!is_dir($bigDir)) {
			mkdir($bigDir, 0777, true);  // 디렉토리 생성
		}
		$bigImage = Image::make($originPath)
			->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
			->resize(1000, null, function ($constraint) {
				$constraint->aspectRatio(); // 원본 비율 유지
				$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
			});
		$bigPath = $bigDir . $uniqueFileName;
		$bigImage->save($bigPath);

		// 업로드 결과 반환
		return [
			'image_origin_name' => $uniqueFileName,
			'image_origin_path' => '/uploads/partners-curated/' . $entityType . '/' . $entityId . '/originals/' . $uniqueFileName,
			'image_small_path' => '/uploads/partners-curated/' . $entityType . '/' . $entityId . '/small/' . $uniqueFileName,
			'image_normal_path' => '/uploads/partners-curated/' . $entityType . '/' . $entityId . '/normal/' . $uniqueFileName,
			'image_big_path' => '/uploads/partners-curated/' . $entityType . '/' . $entityId . '/big/' . $uniqueFileName,
			'image_entity_type' => $entityType,
			'image_type' => $imageType,  // 원하는 이미지 타입
		];
	}

	public function uploadReviewImage($tmpName, $originalName, $mimeType, $extension, $reviewIdx)
	{
		if ($extension == 'mov' || $extension == 'mp4') {
			// 고유한 파일 이름 생성
			$uniqueFileName = bin2hex(random_bytes(16)) . '.' . $extension;

			// 파일 저장 경로 설정
			$uploadDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/reviews/' . $reviewIdx . '/originals/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0777, true);  // 디렉토리 생성
			}

			// 원본 이미지 경로
			$originPath = $uploadDir . $uniqueFileName;
			move_uploaded_file($tmpName, $originPath);

			// 업로드 결과 반환
			return [
				'image_origin_name' => $originalName,
				'image_origin_path' => '/uploads/reviews/' . $reviewIdx . '/originals/' . $uniqueFileName,
				'image_small_path' => '',
				'image_normal_path' => '',
				'image_big_path' => '',
			];
		} else {
			// 고유한 파일 이름 생성
			$uniqueFileName = bin2hex(random_bytes(16)) . '.' . $extension;

			// 파일 저장 경로 설정
			$uploadDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/reviews/' . $reviewIdx . '/originals/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0777, true);  // 디렉토리 생성
			}

			// 원본 이미지 경로
			$originPath = $uploadDir . $uniqueFileName;
			move_uploaded_file($tmpName, $originPath);

			// 1. 작은 이미지 경로 및 저장
			$smallDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/reviews/' . $reviewIdx . '/small/';
			if (!is_dir($smallDir)) {
				mkdir($smallDir, 0777, true);  // 디렉토리 생성
			}
			$smallImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(250, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$smallPath = $smallDir . $uniqueFileName;
			$smallImage->save($smallPath);

			// 2. 중간 이미지 경로 및 저장
			$normalDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/reviews/' . $reviewIdx . '/normal/';
			if (!is_dir($normalDir)) {
				mkdir($normalDir, 0777, true);  // 디렉토리 생성
			}
			$normalImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(500, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$normalPath = $normalDir . $uniqueFileName;
			$normalImage->save($normalPath);

			$bigDir = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/reviews/' . $reviewIdx . '/big/';
			if (!is_dir($bigDir)) {
				mkdir($bigDir, 0777, true);  // 디렉토리 생성
			}
			$bigImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(1000, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$bigPath = $bigDir . $uniqueFileName;
			$bigImage->save($bigPath);

			// 업로드 결과 반환
			return [
				'image_origin_name' => $originalName,
				'image_origin_path' => '/uploads/reviews/' . $reviewIdx . '/originals/' . $uniqueFileName,
				'image_small_path' => '/uploads/reviews/' . $reviewIdx . '/small/' . $uniqueFileName,
				'image_normal_path' => '/uploads/reviews/' . $reviewIdx . '/normal/' . $uniqueFileName,
				'image_big_path' => '/uploads/reviews/' . $reviewIdx . '/big/' . $uniqueFileName,
			];
		}
	}

	public function uploadMoongclePointImage($tmpName, $originalName, $mimeType, $extension, $partnerIdx)
	{
		if ($extension == 'mov' || $extension == 'mp4') {
			return false;
		} else {
			// 고유한 파일 이름 생성
			$uniqueFileName = bin2hex(random_bytes(16)) . '.' . $extension;

			// 파일 저장 경로 설정
			$uploadDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/moongcle_point/' . $partnerIdx . '/originals/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0777, true);  // 디렉토리 생성
			}

			// 원본 이미지 경로
			$originPath = $uploadDir . $uniqueFileName;
			move_uploaded_file($tmpName, $originPath);

			// 1. 작은 이미지 경로 및 저장
			$smallDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/moongcle_point/' . $partnerIdx . '/small/';
			if (!is_dir($smallDir)) {
				mkdir($smallDir, 0777, true);  // 디렉토리 생성
			}
			$smallImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(250, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$smallPath = $smallDir . $uniqueFileName;
			$smallImage->save($smallPath);

			// 2. 중간 이미지 경로 및 저장
			$normalDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/moongcle_point/' . $partnerIdx . '/normal/';
			if (!is_dir($normalDir)) {
				mkdir($normalDir, 0777, true);  // 디렉토리 생성
			}
			$normalImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(500, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$normalPath = $normalDir . $uniqueFileName;
			$normalImage->save($normalPath);

			$bigDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/moongcle_point/' . $partnerIdx . '/big/';
			if (!is_dir($bigDir)) {
				mkdir($bigDir, 0777, true);  // 디렉토리 생성
			}
			$bigImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(1000, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$bigPath = $bigDir . $uniqueFileName;
			$bigImage->save($bigPath);

			// 업로드 결과 반환
			return [
				'image_origin_name' => $originalName,
				'image_origin_path' => '/uploads/moongcle_point/' . $partnerIdx . '/originals/' . $uniqueFileName,
				'image_small_path' => '/uploads/moongcle_point/' . $partnerIdx . '/small/' . $uniqueFileName,
				'image_normal_path' => '/uploads/moongcle_point/' . $partnerIdx . '/normal/' . $uniqueFileName,
				'image_big_path' => '/uploads/moongcle_point/' . $partnerIdx . '/big/' . $uniqueFileName,
			];
		}
	}

	public function uploadFacilityDetailImage($tmpName, $originalName, $mimeType, $extension, $facilityIdx)
	{
		if ($extension == 'mov' || $extension == 'mp4') {
			return false;
		} else {
			// 고유한 파일 이름 생성
			$uniqueFileName = bin2hex(random_bytes(16)) . '.' . $extension;

			// 파일 저장 경로 설정
			$uploadDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/facility_detail/' . $facilityIdx . '/originals/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0777, true);  // 디렉토리 생성
			}

			// 원본 이미지 경로
			$originPath = $uploadDir . $uniqueFileName;
			move_uploaded_file($tmpName, $originPath);

			// 1. 작은 이미지 경로 및 저장
			$smallDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/facility_detail/' . $facilityIdx . '/small/';
			if (!is_dir($smallDir)) {
				mkdir($smallDir, 0777, true);  // 디렉토리 생성
			}
			$smallImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(250, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$smallPath = $smallDir . $uniqueFileName;
			$smallImage->save($smallPath);

			// 2. 중간 이미지 경로 및 저장
			$normalDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/facility_detail/' . $facilityIdx . '/normal/';
			if (!is_dir($normalDir)) {
				mkdir($normalDir, 0777, true);  // 디렉토리 생성
			}
			$normalImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(500, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$normalPath = $normalDir . $uniqueFileName;
			$normalImage->save($normalPath);

			$bigDir = $_ENV['ROOT_DIRECTORY'] . '/public/uploads/facility_detail/' . $facilityIdx . '/big/';
			if (!is_dir($bigDir)) {
				mkdir($bigDir, 0777, true);  // 디렉토리 생성
			}
			$bigImage = Image::make($originPath)
				->orientate() // EXIF 데이터를 기반으로 올바른 방향으로 조정
				->resize(1000, null, function ($constraint) {
					$constraint->aspectRatio(); // 원본 비율 유지
					$constraint->upsize(); // 이미지가 작아지는 경우 강제로 확대하지 않음
				});
			$bigPath = $bigDir . $uniqueFileName;
			$bigImage->save($bigPath);

			// 업로드 결과 반환
			return [
				'image_origin_name' => $originalName,
				'image_origin_path' => '/uploads/facility_detail/' . $facilityIdx . '/originals/' . $uniqueFileName,
				'image_small_path' => '/uploads/facility_detail/' . $facilityIdx . '/small/' . $uniqueFileName,
				'image_normal_path' => '/uploads/facility_detail/' . $facilityIdx . '/normal/' . $uniqueFileName,
				'image_big_path' => '/uploads/facility_detail/' . $facilityIdx . '/big/' . $uniqueFileName,
			];
		}
	}
}

// use Intervention\Image\ImageManagerStatic as Image;
// use Aws\S3\S3Client;

// class ImageUploadService
// {
// 	private $s3Client;
// 	private $bucketName;

// 	public function __construct()
// 	{
// 		$this->s3Client = new S3Client([
// 			'version' => 'latest',
// 			'region' => $_ENV['AWS_S3_REGION'],
// 			'credentials' => [
// 				'key'    => $_ENV['AWS_IAM_KEY'],
// 				'secret' => $_ENV['AWS_IAM_SECRET'],
// 			],
// 		]);
// 		$this->bucketName = $_ENV['AWS_S3_BUCKET'];
// 	}

// 	public function uploadImage($filePath, $fileName, $fileType, $extension, $entityType, $imageType)
// 	{
// 		$APP_ENV = $_ENV['APP_ENV'];

// 		$uniqueFileName = uniqid() . '.' . $extension;

// 		// 원본 이미지 S3에 업로드
// 		$originPath = $this->uploadToS3($filePath, "{$APP_ENV}/partners/{$entityType}/originals/{$uniqueFileName}");

// 		// 작은 이미지 변환 및 업로드
// 		$smallImage = Image::make($filePath)->resize(300, null, function ($constraint) {
// 			$constraint->aspectRatio();
// 		});
// 		$smallPath = $this->uploadImageToS3($smallImage, "{$APP_ENV}/partners/{$entityType}/small/{$uniqueFileName}");

// 		// 중간 이미지 변환 및 업로드
// 		$normalImage = Image::make($filePath)->resize(700, null, function ($constraint) {
// 			$constraint->aspectRatio();
// 		});
// 		$normalPath = $this->uploadImageToS3($normalImage, "{$APP_ENV}/partners/{$entityType}/normal/{$uniqueFileName}");

// 		return [
// 			'image_origin_path' => $originPath,
// 			'image_small_path' => $smallPath,
// 			'image_normal_path' => $normalPath,
// 			'image_entity_type' => $entityType,
// 			'image_type' => $imageType,
// 			'image_origin_name' => $fileName,  // 파일 원본 이름
// 		];
// 	}

// 	private function uploadToS3($filePath, $s3Path)
// 	{
// 		// S3에 파일 업로드
// 		$result = $this->s3Client->putObject([
// 			'Bucket' => $this->bucketName,
// 			'Key'    => $s3Path,
// 			'SourceFile' => $filePath,
// 			'ACL'    => 'public-read',
// 		]);

// 		return $result['ObjectURL'];
// 	}

// 	private function uploadImageToS3($image, $s3Path)
// 	{
// 		// 임시로 이미지를 파일로 저장
// 		$tempFilePath = tempnam(sys_get_temp_dir(), 'image');
// 		$image->save($tempFilePath);

// 		// S3에 업로드
// 		$url = $this->uploadToS3($tempFilePath, $s3Path);

// 		// 임시 파일 삭제
// 		unlink($tempFilePath);

// 		return $url;
// 	}
// }
