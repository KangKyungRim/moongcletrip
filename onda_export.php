<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/bootstrap.php';

use App\Controllers\Api\ExportApiController;

// $propertyIds = [];
// $filePath = '/data/wwwroot/moongcletrip/property_ids';

// if (file_exists($filePath)) {
// 	// 파일을 줄 단위로 읽어서 배열에 추가
// 	$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// 	foreach ($lines as $line) {
// 		// 숫자인지 확인 후 배열에 추가
// 		if (is_numeric($line)) {
// 			$propertyIds[] = (int)$line;
// 		}
// 	}
// }

// $filePath = '/data/wwwroot/moongcletrip/onda_partner_idx';
// $desiredOrder = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// if (!$desiredOrder) {
//     die("파일을 읽을 수 없습니다.");
// }

// $desiredOrderString = implode(',', $desiredOrder);

// $desiredOrderString = '430';

// ExportApiController::downloadOndaStaysStreaming($desiredOrderString);

ExportApiController::downloadOndaStaysStreamingOrderIdx();

