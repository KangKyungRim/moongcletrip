<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/bootstrap.php';

use App\Models\OndaWebhook;
use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomDraft;
use App\Models\Rateplan;
use App\Models\RoomRateplan;
use App\Models\RoomInventory;
use App\Models\RoomPrice;
use App\Models\MoongcleOffer;
use App\Models\MoongcleOfferPrice;

use App\Services\OndaPropertyService;
use App\Services\OndaRoomService;
use App\Services\OndaRateplanService;
use App\Services\OndaRoomInventoryService;

$batchSize = 10000; // 한 번에 처리할 개수
$offset = 1130000; // 시작점

do {
	$ondaWebhooks = OndaWebhook::where('event_type', 'inventory_updated')
		->offset($offset)
		->limit($batchSize)
		->get();

	if ($ondaWebhooks->isEmpty()) {
		break;
	}

	foreach ($ondaWebhooks as $ondaWebhook) {
		$moongcleoffer = null;
		$eventDetail = $ondaWebhook->event_detail;

		try {
			$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
				->where('rateplan_onda_idx', $eventDetail[0]['rateplan_id'])
				->first();

			if (empty($roomRateplan)) {
				continue;
			}

			$moongcleoffer = MoongcleOffer::where('moongcleoffer_category', 'roomRateplan')
				->where('base_product_idx', $roomRateplan->room_rateplan_idx)
				->where('moongcleoffer_status', 'enabled')
				->first();

			foreach ($eventDetail as $data) {
				$eventDate = new DateTime($data['date']);
				$today = new DateTime(); // 오늘 날짜

				// $data['date']가 오늘보다 이전인지 확인
				if ($eventDate < $today) {
					continue; // 이전 날짜라면 반복문 건너뛰기
				}

				$inventory = RoomInventory::firstOrNew([
					'room_rateplan_idx' => $roomRateplan->room_rateplan_idx,
					'inventory_date' => $data['date'],
				]);

				$inventory->fill([
					'room_idx' => $roomRateplan->room_idx,
					'rateplan_idx' => $roomRateplan->rateplan_idx,
					'inventory_quantity' => $data['vacancy'],
				])->save();

				$roomPrice = RoomPrice::firstOrNew([
					'room_rateplan_idx' => $roomRateplan->room_rateplan_idx,
					'room_price_date' => $data['date'],
				]);

				$roomPrice->fill([
					'room_idx' => $roomRateplan->room_idx,
					'rateplan_idx' => $roomRateplan->rateplan_idx,
					'room_price_basic' => $data['basic_price'],
					'room_price_sale' => $data['sale_price'],
					'room_price_promotion_type' => $data['promotion_type'],
					// 'room_price_additional_adult' => $data['extra_adult'],
					// 'room_price_additional_child' => $data['extra_child'],
					// 'room_price_additional_tiny' => $data['extra_infant'],
					// 'room_price_checkin' => $data['checkin'],
					// 'room_price_checkout' => $data['checkout'],
					// 'room_price_stay_min' => $data['length_of_stay']['min'],
					// 'room_price_stay_max' => $data['length_of_stay']['max'],
				])->save();

				if (!empty($moongcleoffer)) {
					$moongcleofferPrice = MoongcleOfferPrice::firstOrNew([
						'moongcleoffer_idx' => $moongcleoffer->moongcleoffer_idx,
						'base_idx' => $roomPrice->room_price_idx,
						'base_type' => 'roomRateplan'
					]);

					$moongcleofferPrice->fill([
						'moongcleoffer_price_date' => $roomPrice->room_price_date,
						'moongcleoffer_price_basic' => $roomPrice->room_price_basic,
						'moongcleoffer_price_sale' => ceil($roomPrice->room_price_sale - ($roomPrice->room_price_sale * ($moongcleoffer->minimum_discount / 100))),
						'moongcleoffer_discount_rate' => $moongcleoffer->minimum_discount,
					])->save();
				}
			}
		} catch (\Exception $e) {
			error_log($e->getMessage());
		}
	}

	$offset += $batchSize;

	echo "IDX : " . $ondaWebhooks[0]->onda_webhook_idx . "\n";
} while (true);


echo "모든 작업 완료\n";
