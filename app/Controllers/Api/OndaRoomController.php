<?php

namespace App\Controllers\Api;

use App\Services\OndaRoomService;

use App\Models\Partner;
use App\Models\Room;

use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class OndaRoomController
{
	public static function storeRoomtypes()
	{
		try {
			$partners = Partner::where('partner_thirdparty', 'onda')->get();
			$service = new OndaRoomService();

			foreach ($partners as $partner) {
				$service->saveRoomtypes($partner);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 객실 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeDetailRoomtypes()
	{
		try {
			$rooms = Room::where('room_thirdparty', 'onda')->get();
			$service = new OndaRoomService();

			foreach ($rooms as $room) {
				$service->saveRoomtypeDetails($room);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 객실 상세 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeDetailRoomtype()
	{
		try {
			$input = json_decode(file_get_contents("php://input"), true);

			$roomtypeIdx = $input['roomtypeIdx'];

			$room = Room::where('room_thirdparty', 'onda')
				->where('room_onda_idx', $roomtypeIdx)
				->first();

			if (empty($room)) {
				return ResponseHelper::jsonResponse(['success' => false], 500);
			}

			$service = new OndaRoomService();

			$service->saveRoomtypeDetails($room);

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 객실 상세 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeRoomtypesById($partner)
	{
		try {
			if (empty($partner)) {
				return;
			}

			$service = new OndaRoomService();

			$service->saveRoomtypes($partner);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}
}
