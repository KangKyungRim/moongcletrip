<?php

namespace App\Controllers\Api;

use App\Models\OndaWebhook;
use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomDraft;
use App\Models\Rateplan;
use App\Models\RoomRateplan;
use App\Models\RoomInventory;
use App\Models\RoomPrice;

use App\Services\WebhookHandlerService;
use App\Helpers\ResponseHelper;

class OndaWebhookController
{
	public static function handleWebhook()
	{
		try {
			$headers = getallheaders();
			$authHeader = $headers['Authorization'] ?? null;

			if (!$authHeader || $authHeader !== $_ENV['ONDA_WEBHOOK_KEY']) {
				return ResponseHelper::jsonResponse(['error' => 'Unauthorized'], 401);
			}

			// 1. Webhook 데이터 수신
			$input = json_decode(file_get_contents("php://input"), true);

			// 2. 필수 데이터 검증
			if (empty($input['event_type']) || empty($input['timestamp'])) {
				return ResponseHelper::jsonResponse(['error' => 'Invalid data'], 400);
			}

			$webhook = new OndaWebhook();
			$webhook->event_type = $input['event_type'];
			$webhook->event_timestamp = $input['timestamp'];
			$webhook->event_progress_status = 'pending';

			if ($input['event_type'] == 'inventory_updated') {
				if (empty($input['event_details'])) {
					return ResponseHelper::jsonResponse(['error' => 'Invalid data'], 400);
				}

				$webhook->event_detail = $input['event_details'];
			} else {
				if (empty($input['event_detail'])) {
					return ResponseHelper::jsonResponse(['error' => 'Invalid data'], 400);
				}

				$webhook->event_detail = $input['event_detail'];
			}

			$webhook->save();

			// if ($input['event_type'] == 'inventory_updated') {
			// 	$webhook->event_progress_status = 'progress';
			// 	$webhook->save();

			// 	// 웹훅 이벤트 처리 로직
			// 	$result = self::handleInventoryUpdated($webhook->event_detail);

			// 	if ($result === false) {
			// 		$webhook->event_progress_status = 'error';
			// 		$webhook->save();

			// 		return ResponseHelper::jsonResponse([
			// 			'success' => false,
			// 			'message' => 'Rateplan not exist.',
			// 			'error' => $e->getMessage()
			// 		], 400);
			// 	}

			// 	// 상태 업데이트
			// 	$webhook->event_progress_status = '1st_completed';
			// 	$webhook->save();
			// }

			return ResponseHelper::jsonResponse(['success' => true], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	private static function handleInventoryUpdated($eventDetail)
	{
		$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
			->where('rateplan_onda_idx', $eventDetail[0]['rateplan_id'])
			->first();

		if (empty($roomRateplan->room_rateplan_idx)) {
			return false;
		}

		foreach ($eventDetail as $detail) {
			$inventory = RoomInventory::firstOrNew([
				'room_rateplan_idx' => $roomRateplan->room_rateplan_idx,
				'inventory_date' => $detail['date'],
			]);

			$inventory->fill([
				'room_idx' => $roomRateplan->room_idx,
				'rateplan_idx' => $roomRateplan->rateplan_idx,
				'inventory_quantity' => $detail['vacancy'],
			])->save();

			$roomPrice = RoomPrice::firstOrNew([
				'room_rateplan_idx' => $roomRateplan->room_rateplan_idx,
				'room_price_date' => $detail['date'],
			]);

			$roomPrice->fill([
				'room_idx' => $roomRateplan->room_idx,
				'rateplan_idx' => $roomRateplan->rateplan_idx,
				'room_price_basic' => $detail['basic_price'],
				'room_price_sale' => $detail['sale_price'],
				'room_price_promotion_type' => $detail['promotion_type'],
			])->save();
		}

		return true;
	}
}
