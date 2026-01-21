<?php

namespace App\Controllers\Api;

use App\Services\OndaRateplanService;
use App\Models\Room;
use App\Models\RoomRateplan;

use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class OndaRateplanController
{
	public static function storeRateplans()
	{
		try {
			$rooms = Room::where('room_thirdparty', 'onda')->get();
			$service = new OndaRateplanService();

			foreach ($rooms as $room) {
				$service->saveRateplans($room);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 레이트플랜 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeDetailRateplans()
	{
		try {
			$roomRateplans = RoomRateplan::where('rateplan_thirdparty', 'onda')->get();
			$service = new OndaRateplanService();

			foreach ($roomRateplans as $roomRateplan) {
				$service->saveRateplanDetails($roomRateplan);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 레이트플랜 상세 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeDetailRateplan()
	{
		try {
			$input = json_decode(file_get_contents("php://input"), true);

			$rateplanIdx = $input['rateplanIdx'];

			$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
				->where('rateplan_onda_idx', $rateplanIdx)
				->first();

			if (empty($roomRateplan)) {
				return ResponseHelper::jsonResponse(['success' => false], 500);
			}

			$service = new OndaRateplanService();

			$service->saveRateplanDetails($roomRateplan);

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 레이트플랜 상세 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}
}
