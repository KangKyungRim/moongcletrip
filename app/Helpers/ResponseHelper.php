<?php

namespace App\Helpers;

use App\Helpers\CaseHelper;

class ResponseHelper
{
	// JSON 응답을 처리하는 헬퍼 함수
	public static function jsonResponse($data, $statusCode = 200)
	{
		header('Content-Type: application/json', true, $statusCode);
		echo json_encode($data);
		exit;
	}

	//JSON 응답을 처리하는 헬퍼 함수
	//ResponseHelper 내에서 body/에러 payload에만 적용
	public static function jsonResponse2($data, $statusCode = 200) {
		if (array_key_exists('body', $data)) {
            $data['body'] = CaseHelper::camelKeys($data['body']);
        }
        if (array_key_exists('error', $data)) {
            $data['error'] = CaseHelper::camelKeys($data['error']);
        }
        if (array_key_exists('errors', $data)) {
            $data['errors'] = CaseHelper::camelKeys($data['errors']);
        }

		header('Content-Type: application/json', true, $statusCode);
		echo json_encode($data);
		exit;
	}
}
