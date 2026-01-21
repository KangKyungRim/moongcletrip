<?php

namespace App\Controllers\Api;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

class EmailAuthController
{
	// 카카오 로그인 URL 생성
	public static function redirect()
	{
		$headers = getallheaders();

		$data = array(
			'headers' => $headers,
        );

        self::render('email-redirect', ['data' => $data]);
	}

	private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
