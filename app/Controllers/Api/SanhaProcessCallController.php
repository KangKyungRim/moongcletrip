<?php

namespace App\Controllers\Api;

use App\Models\RoomPrice;

use App\Services\SanhaIntegrationService;
use App\Helpers\ResponseHelper;

class SanhaProcessCallController
{
	public static function handleProcess()
	{
		try {
			$rawPostData = file_get_contents('php://input');

			$clientIp = $_SERVER['REMOTE_ADDR'];
			$sanhaWhiteList = explode(',', $_ENV['SANHA_IP']);

			if (!in_array($clientIp, $sanhaWhiteList)) {
				return ResponseHelper::jsonResponse(['error' => 'No Permission'], 400);
			}

			SanhaIntegrationService::processCall($rawPostData);

			exit;
		} catch (\Exception $e) {
			exit;
		}
	}

	public static function handleAvailabilityAndRates()
	{
		try {
			$rawPostData = file_get_contents('php://input');

			$clientIp = $_SERVER['REMOTE_ADDR'];
			$sanhaWhiteList = explode(',', $_ENV['SANHA_IP']);

			if (!in_array($clientIp, $sanhaWhiteList)) {
				return ResponseHelper::jsonResponse(['error' => 'No Permission'], 400);
			}

			SanhaIntegrationService::availabilityAndRates($rawPostData);

			exit;
		} catch (\Exception $e) {
			exit;
		}
	}
}
