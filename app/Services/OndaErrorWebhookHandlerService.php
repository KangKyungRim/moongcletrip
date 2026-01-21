<?php

namespace App\Services;

use App\Models\OndaWebhook;
use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomDraft;
use App\Models\Rateplan;
use App\Models\RoomRateplan;
use App\Models\RoomInventory;
use App\Models\RoomPrice;

use App\Services\OndaPropertyService;
use App\Services\OndaRoomService;
use App\Services\OndaRateplanService;
use App\Services\OndaRoomInventoryService;

use Carbon\Carbon;

class OndaErrorWebhookHandlerService
{
	public function processPendingWebhooks($startIdx = 0, $endIdx = 0)
	{
		// 처리해야 할 웹훅 데이터 가져오기
		$webhooksQuery = OndaWebhook::where('event_progress_status', 'error')->where('event_type', 'inventory_updated');

		// $webhooksQuery->where('event_type', 'status_updated');

		// startIdx 값이 있으면 최소 인덱스 제한 추가
		if ($startIdx > 0) {
			$webhooksQuery->where('onda_webhook_idx', '>=', $startIdx);
		}

		// endIdx 값이 있으면 제한 추가
		if ($endIdx > 0) {
			$webhooksQuery->where('onda_webhook_idx', '<=', $endIdx);
		}

		$perPage = 500; // 한 번에 처리할 레코드 수
		$page = 0;

		$executionLimit = 60;
		$startTime = time();

		do {
			$stop = false;
			$webhooks = $webhooksQuery->orderBy('event_timestamp', 'asc')
				->offset($page * $perPage)
				->limit($perPage)
				->get();

			foreach ($webhooks as $webhook) {
				try {
					$webhook->event_progress_status = 'progress';
					$webhook->save();

					// 웹훅 이벤트 처리 로직
					$this->processWebhook($webhook);

					$webhook->event_progress_status = 'completed';
					$webhook->save();

					if (time() - $startTime >= $executionLimit) {
						$stop = true;
						break;
					}
				} catch (\Exception $e) {
					$webhook->event_progress_status = 'error2';
					$webhook->save();

					error_log($e->getMessage());

					if (time() - $startTime >= $executionLimit) {
						$stop = true;
						break;
					}
				}
			}

			if ($stop) {
				break;
			}

			$page++;
		} while ($webhooks->count() > 0);
	}

	private function processWebhook($webhook)
	{
		$eventType = $webhook->event_type;
		$eventDetail = $webhook->event_detail;

		// 이벤트 타입별 처리 로직
		switch ($eventType) {
			case 'contents_updated':
				$this->handleContentsUpdated($eventDetail);
				break;
			case 'status_updated':
				$this->handleStatusUpdated($eventDetail);
				break;
			case 'inventory_updated':
				$this->handleInventoryUpdated($eventDetail);
				break;
			default:
				throw new \Exception("Unsupported event type: $eventType");
		}
	}

	private function handleContentsUpdated($eventDetail)
	{
		if ($eventDetail['target'] == 'property') {
			$service = new OndaPropertyService();

			$partner = Partner::where('partner_thirdparty', 'onda')
				->where('partner_onda_idx', $eventDetail['property_id'])
				->first();

			$property = $service->fetchPropertyDetails($eventDetail['property_id']);

			if (empty($partner)) {
				$partner = $service->saveOrUpdatePartner($property);
			}

			$service->updatePartnerDetails($partner, $property);

			if (!empty($property['classifications'])) {
				$property['tags']['classifications'] = $property['classifications'];
			}

			$stay = $service->saveOrUpdateStay($partner, $property);
			$service->saveTagConnections($stay->stay_idx, $property['tags']);
			$service->saveImages($stay->stay_idx, $property['images'], 'basic');
			$service->saveCancelRules($partner->partner_idx, $property['property_refunds']);
		} else if ($eventDetail['target'] == 'roomtype') {
			$partner = Partner::where('partner_thirdparty', 'onda')
				->where('partner_onda_idx', $eventDetail['property_id'])
				->first();

			if (empty($partner)) {
				$service = new OndaPropertyService();
				$property = $service->fetchPropertyDetails($eventDetail['property_id']);

				$partner = $service->saveOrUpdatePartner($property);

				$service->updatePartnerDetails($partner, $property);

				if (!empty($property['classifications'])) {
					$property['tags']['classifications'] = $property['classifications'];
				}

				$stay = $service->saveOrUpdateStay($partner, $property);
				$service->saveTagConnections($stay->stay_idx, $property['tags']);
				$service->saveImages($stay->stay_idx, $property['images'], 'basic');
				$service->saveCancelRules($partner->partner_idx, $property['property_refunds']);
			}

			$room = Room::where('room_thirdparty', 'onda')
				->where('room_onda_idx', $eventDetail['roomtype_id'])
				->first();

			$service = new OndaRoomService();

			if (empty($room)) {
				$service->saveRoomtypes($partner);

				$room = Room::where('room_thirdparty', 'onda')
					->where('room_onda_idx', $eventDetail['roomtype_id'])
					->first();
			}

			$service->saveRoomtypeDetails($room);
		} else if ($eventDetail['target'] == 'rateplan') {
			$partner = Partner::where('partner_thirdparty', 'onda')
				->where('partner_onda_idx', $eventDetail['property_id'])
				->first();

			if (empty($partner)) {
				$service = new OndaPropertyService();
				$property = $service->fetchPropertyDetails($eventDetail['property_id']);

				$partner = $service->saveOrUpdatePartner($property);

				$service->updatePartnerDetails($partner, $property);

				if (!empty($property['classifications'])) {
					$property['tags']['classifications'] = $property['classifications'];
				}

				$stay = $service->saveOrUpdateStay($partner, $property);
				$service->saveTagConnections($stay->stay_idx, $property['tags']);
				$service->saveImages($stay->stay_idx, $property['images'], 'basic');
				$service->saveCancelRules($partner->partner_idx, $property['property_refunds']);
			}

			$room = Room::where('room_thirdparty', 'onda')
				->where('room_onda_idx', $eventDetail['roomtype_id'])
				->first();

			$service = new OndaRoomService();

			if (empty($room)) {
				$service->saveRoomtypes($partner);

				$room = Room::where('room_thirdparty', 'onda')
					->where('room_onda_idx', $eventDetail['roomtype_id'])
					->first();

				$service->saveRoomtypeDetails($room);
			}

			$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
				->where('rateplan_onda_idx', $eventDetail['rateplan_id'])
				->first();

			$service = new OndaRateplanService();

			if (empty($roomRateplan)) {
				$service->saveRateplans($room);

				$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
					->where('rateplan_onda_idx', $eventDetail['rateplan_id'])
					->first();
			}

			$service = new OndaRateplanService();

			$service->saveRateplanDetails($roomRateplan);
		}
	}

	private function handleStatusUpdated($eventDetail)
	{
		if ($eventDetail['target'] == 'property') {
			$partner = Partner::where('partner_thirdparty', 'onda')
				->where('partner_onda_idx', $eventDetail['property_id'])
				->first();

			if (!empty($partner)) {
				$partner->partner_status = $eventDetail['status'];
				$partner->partner_updated_at = Carbon::now();
				$partner->save();
			} else {
				$service = new OndaPropertyService();
				$property = $service->fetchPropertyDetails($eventDetail['property_id']);
				$partner = $service->saveOrUpdatePartner($property);
				$service->updatePartnerDetails($partner, $property);

				if (!empty($property['classifications'])) {
					$property['tags']['classifications'] = $property['classifications'];
				}

				$stay = $service->saveOrUpdateStay($partner, $property);
				$service->saveTagConnections($stay->stay_idx, $property['tags']);
				$service->saveImages($stay->stay_idx, $property['images'], 'basic');
				$service->saveCancelRules($partner->partner_idx, $property['property_refunds']);
			}
		} else if ($eventDetail['target'] == 'roomtype') {
			$room = Room::where('room_thirdparty', 'onda')
				->where('room_onda_idx', $eventDetail['roomtype_id'])
				->first();

			if (!empty($room)) {
				$room->room_status = $eventDetail['status'];
				$room->room_updated_at = Carbon::now();
				$room->save();
			}
		} else if ($eventDetail['target'] == 'rateplan') {
			$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
				->where('rateplan_onda_idx', $eventDetail['rateplan_id'])
				->first();

			$rateplan = Rateplan::where('rateplan_thirdparty', 'onda')
				->where('rateplan_onda_idx', $eventDetail['rateplan_id'])
				->first();

			if (!empty($roomRateplan)) {
				$roomRateplan->room_rateplan_status = $eventDetail['status'];
				$roomRateplan->updated_at = Carbon::now();
				$roomRateplan->save();

				$rateplan->rateplan_status = $eventDetail['status'];
				$rateplan->rateplan_updated_at = Carbon::now();
				$rateplan->save();
			}
		}
	}

	private function handleInventoryUpdated($eventDetail)
	{
		$inventoryService = new OndaRoomInventoryService();

		$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
			->where('rateplan_onda_idx', $eventDetail[0]['rateplan_id'])
			->first();

		$currentDate = new \DateTime($eventDetail[0]['date']);
		$endDate = new \DateTime($eventDetail[count($eventDetail) - 1]['date']);

		$i = 0;
		$allInventories = [];

		while ($currentDate < $endDate && $i < 8) {
			// 89일 이후로 설정
			$currentEndDate = (clone $currentDate)->add(new \DateInterval('P89D'));

			// 최대 종료 날짜를 초과하지 않도록 조정
			if ($currentEndDate > $endDate) {
				$currentEndDate = $endDate;
			}

			// API 호출 로직
			$inventories = $inventoryService->fetchInventories(
				$eventDetail[0]['rateplan_id'],
				$currentDate->format('Y-m-d'),
				$currentEndDate->format('Y-m-d')
			);

			// 결과 병합
			$allInventories = array_merge($allInventories, $inventories);

			// 다음 시작 날짜로 갱신 (1일 후로 설정)
			$currentDate = $currentEndDate->add(new \DateInterval('P1D'));

			$i++;
		}

		// 병합된 데이터를 기반으로 RoomPrice 업데이트
		foreach ($allInventories as $data) {
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
				'room_price_additional_adult' => $data['extra_adult'],
				'room_price_additional_child' => $data['extra_child'],
				'room_price_additional_tiny' => $data['extra_infant'],
				'room_price_checkin' => $data['checkin'],
				'room_price_checkout' => $data['checkout'],
				'room_price_stay_min' => $data['length_of_stay']['min'],
				'room_price_stay_max' => $data['length_of_stay']['max'],
			])->save();
		}
	}
}
