<?php

namespace App\Helpers;

//use Intervention\Image\ImageManagerStatic as Image;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;


/**
 * AWS S3 이미지 업로드
 */
class ImageUploadAwsService {
	private S3Client $s3;
    private string $bucket;

	public function __construct()
	{
		$config = [
			'version' => 'latest',
			'region'  => $_ENV['AWS_S3_REGION'],
			'suppress_php_deprecation_warning' => true	//php 7.4* 대응
		];

		if ($_ENV['APP_ENV'] === 'development') {
			$config['credentials'] = [
				'key'    => $_ENV['AWS_IAM_KEY'],
				'secret' => $_ENV['AWS_IAM_SECRET']
			];
		}
		
		$config['signature_version'] = 'v4';

		//error_log('credentials'.print_R($config));

		$this->s3 = new S3Client($config);
		$this->bucket = $_ENV['AWS_S3_BUCKET'];
	}

	/**
	 * 이미지 업로드
	 */
	public function upload(string $localFilePath, string $key, string $mimeType = 'image/jpeg'): ?string
    {
        try {
            $result = $this->s3->putObject([
                'Bucket'      => $this->bucket,
                'Key'         => $key,
                'SourceFile'  => $localFilePath,
                'ContentType' => $mimeType,
                'CacheControl'=> 'public, max-age=31536000, immutable',
            ]);

            // 업로드된 파일 URL (S3 URL, CloudFront 쓰려면 직접 조합)
            return $result['ObjectURL'];

        } catch (AwsException $e) {
            // 로그 남기기
            error_log("S3 업로드 실패: " . $e->getMessage());
            return null;
        }
    }

	/**
     * CloudFront URL로 변환
     */
    public function getCloudFrontUrl(string $key): string
    {
        $cdn = 'https://da8bm4e9mdvmt.cloudfront.net'; // 배포 도메인으로 교체
        return "{$cdn}/{$key}";
    }

	// S3 객체 삭제
    public function deleteObjectByKey(?string $key): bool
    {
        if (!$key) {
			throw new \InvalidArgumentException('삭제할 S3 객체 키가 비어있습니다.');
		}

        try {
            $this->s3->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
            ]);
            return true;
        } catch (\Throwable $e) {
            // 여기서 logger 사용 가능하면 교체
            error_log("S3 삭제 실패: key={$key}, err=".$e->getMessage());
            return false;
        }
    }
	
	/*
	public function __construct()
	{
		$this->s3Client = new S3Client([
			'version' => 'latest',
			'region' => $_ENV['AWS_S3_REGION'],
			'credentials' => [
				'key'    => $_ENV['AWS_IAM_KEY'],
				'secret' => $_ENV['AWS_IAM_SECRET'],
			],
		]);
		$this->bucketName = $_ENV['AWS_S3_BUCKET'];
	}

	public function uploadImage($filePath, $fileName, $fileType, $extension, $entityType, $imageType)
	{
		$APP_ENV = $_ENV['APP_ENV'];

		$uniqueFileName = uniqid() . '.' . $extension;

		// 원본 이미지 S3에 업로드
		$originPath = $this->uploadToS3($filePath, "{$APP_ENV}/partners/{$entityType}/originals/{$uniqueFileName}");

		// 작은 이미지 변환 및 업로드
		$smallImage = Image::make($filePath)->resize(300, null, function ($constraint) {
			$constraint->aspectRatio();
		});
		$smallPath = $this->uploadImageToS3($smallImage, "{$APP_ENV}/partners/{$entityType}/small/{$uniqueFileName}");

		// 중간 이미지 변환 및 업로드
		$normalImage = Image::make($filePath)->resize(700, null, function ($constraint) {
			$constraint->aspectRatio();
		});
		$normalPath = $this->uploadImageToS3($normalImage, "{$APP_ENV}/partners/{$entityType}/normal/{$uniqueFileName}");

		return [
			'image_origin_path' => $originPath,
			'image_small_path' => $smallPath,
			'image_normal_path' => $normalPath,
			'image_entity_type' => $entityType,
			'image_type' => $imageType,
			'image_origin_name' => $fileName,  // 파일 원본 이름
		];
	}

	private function uploadToS3($filePath, $s3Path)
	{
		// S3에 파일 업로드
		$result = $this->s3Client->putObject([
			'Bucket' => $this->bucketName,
			'Key'    => $s3Path,
			'SourceFile' => $filePath,
			'ACL'    => 'public-read',
		]);

		return $result['ObjectURL'];
	}

	private function uploadImageToS3($image, $s3Path)
	{
		// 임시로 이미지를 파일로 저장
		$tempFilePath = tempnam(sys_get_temp_dir(), 'image');
		$image->save($tempFilePath);

		// S3에 업로드
		$url = $this->uploadToS3($tempFilePath, $s3Path);

		// 임시 파일 삭제
		unlink($tempFilePath);

		return $url;
	}*/
}