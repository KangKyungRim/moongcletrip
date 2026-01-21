<?php

namespace App\Controllers\Api;

use App\Services\ImportService;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

class ExcelUploadController
{
	public function tagExcelUpload()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if (!$checkUser || $checkUser->partner_user_level < 4) {
			return ResponseHelper::jsonResponse(['error' => 'Unauthorized'], 403);
		}

		if (!isset($_FILES['tagsFile']) || $_FILES['tagsFile']['error'] !== UPLOAD_ERR_OK) {
			return ResponseHelper::jsonResponse(['error' => 'File upload failed'], 400);
		}

		$directory = $_ENV['ROOT_DIRECTORY'] . '/public' . '/uploads/excel/';
		$uploadedFile = $_FILES['tagsFile']['tmp_name'];
		$destinationPath = $directory . $_FILES['tagsFile']['name'];

		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		if (!move_uploaded_file($uploadedFile, $destinationPath)) {
			return ResponseHelper::jsonResponse(['error' => 'File saving failed'], 500);
		}

		try {
			ImportService::importTags($destinationPath);
			header('Location: /manage/moongcleoffers/onda');
			return ResponseHelper::jsonResponse(['message' => 'Tags imported successfully']);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}
}
