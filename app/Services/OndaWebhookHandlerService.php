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
use App\Models\MoongcleOffer;
use App\Models\MoongcleOfferPrice;

use App\Services\OndaPropertyService;
use App\Services\OndaRoomService;
use App\Services\OndaRateplanService;
use App\Services\OndaRoomInventoryService;

use Carbon\Carbon;

class OndaWebhookHandlerService
{
	public function processPendingWebhooks()
	{
		$propertyIds = [
			10657,
			316,
			10397,
			10302,
			10584,
			13909,
			10544,
			4187,
			9971,
			389,
			10594,
			10204,
			12489,
			10729,
			10519,
			3893,
			13390,
			13392,
			10579,
			9940,
			9981,
			9352,
			9583,
			12491,
			11981,
			10773,
			13402,
			3867,
			9367,
			941,
			1046,
			353,
			10370,
			13296,
			9397,
			10420,
			9743,
			14095,
			10229,
			13511,
			1065,
			3834,
			10601,
			11221,
			12132,
			4074,
			9350,
			1021,
			398,
			380,
			499,
			9955,
			9746,
			3745,
			9325,
			9751,
			10781,
			13640,
			4314,
			995,
			11996,
			11591,
			12223,
			10069,
			354,
			355,
			357,
			359,
			360,
			380,
			381,
			932,
			934,
			938,
			941,
			942,
			943,
			944,
			946,
			948,
			954,
			956,
			958,
			960,
			961,
			970,
			971,
			972,
			973,
			974,
			977,
			979,
			983,
			984,
			985,
			986,
			987,
			988,
			989,
			991,
			992,
			994,
			995,
			996,
			998,
			1001,
			1002,
			1004,
			1007,
			1009,
			1011,
			1012,
			1013,
			1014,
			1018,
			1021,
			1022,
			1024,
			1031,
			1033,
			1069,
			1075,
			1083,
			3823,
			5958,
			9571,
			9594,
			9688,
			9711,
			9715,
			10424,
			10497,
			11182,
			11471
		];

		// 처리해야 할 웹훅 데이터 가져오기
		$webhooksQuery = OndaWebhook::where('event_progress_status', 'pending');

		$perPage = 500; // 한 번에 처리할 레코드 수
		$executionLimit = 60; // 실행 시간 제한 (초)
		$startTime = time(); // 시작 시간

		while (true) {
			// 남은 시간이 없는 경우 루프 종료
			if (time() - $startTime >= $executionLimit) {
				break;
			}

			// 조건에 맞는 데이터 가져오기
			$webhooks = $webhooksQuery->orderBy('event_timestamp', 'asc')
				->limit($perPage)
				->get();

			if ($webhooks->isEmpty()) {
				// 웹훅이 비어 있으면 5초 대기
				sleep(5);

				continue;
			}

			foreach ($webhooks as $webhook) {
				try {
					if (!empty($webhook->event_detail[0])) {
						if ($webhook->event_detail[0]['property_id'] == 162383) {
							$webhook->event_progress_status = 'skip';
							$webhook->save();

							continue;
						}

						if (in_array($webhook->event_detail[0]['property_id'], $propertyIds)) {
							$webhook->event_progress_status = 'skip';
							$webhook->save();

							continue;
						}
					} else {
						if ($webhook->event_detail['property_id'] == 162383) {
							$webhook->event_progress_status = 'skip';
							$webhook->save();

							continue;
						}

						if (in_array($webhook->event_detail['property_id'], $propertyIds)) {
							$webhook->event_progress_status = 'skip';
							$webhook->save();

							continue;
						}
					}

					// 상태를 진행 중으로 변경
					$webhook->event_progress_status = 'progress';
					$webhook->save();

					// 웹훅 이벤트 처리 로직
					$this->processWebhook($webhook);

					// 처리 완료 상태로 업데이트
					$webhook->event_progress_status = 'completed';
					$webhook->save();

					// 남은 시간이 초과되면 루프 종료
					if (time() - $startTime >= $executionLimit) {
						break 2; // 이중 루프 종료
					}
				} catch (\Exception $e) {
					// 오류 발생 시 상태 업데이트 및 에러 로깅
					$webhook->event_progress_status = 'error';
					$webhook->save();

					error_log($e->getMessage());

					// 남은 시간이 초과되면 루프 종료
					if (time() - $startTime >= $executionLimit) {
						break 2; // 이중 루프 종료
					}
				}
			}
		}
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
			} else {
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

				$service = new OndaRoomService();

				$service->saveRoomtypes($partner);

				$room = Room::where('room_thirdparty', 'onda')
					->where('room_onda_idx', $eventDetail['roomtype_id'])
					->first();

				if (empty($room)) {
					$roomtype = $service->fetchRoomtypeDetails($partner->partner_onda_idx, $eventDetail['roomtype_id']);

					$room = Room::firstOrNew([
						'room_onda_idx' => intval($roomtype['id']),
						'room_thirdparty' => 'onda',
					]);

					$room->fill([
						'partner_idx' => $partner->partner_idx,
						'room_name' => $roomtype['name'],
						'room_onda_idx' => intval($roomtype['id']),
						'room_thirdparty' => 'onda',
						'room_status' => $roomtype['status'],
						'room_updated_at' => Carbon::now(),
					])->save();

					$roomDraft = $room->draft ?: new RoomDraft();
					$roomDraft->fill([
						'room_idx' => $room->room_idx,
						'room_name' => $room->room_name,
						'room_onda_idx' => $room->room_onda_idx,
						'room_thirdparty' => $room->room_thirdparty,
						'room_is_approved' => true,
						'updated_at' => Carbon::now(),
					])->save();
				}

				$service->saveRoomtypeDetails($room);
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

				$moongcleoffer = MoongcleOffer::where('moongcleoffer_category', 'roomRateplan')
					->where('base_product_idx', $roomRateplan->room_rateplan_idx)
					->first();

				if (!empty($moongcleoffer)) {
					$moongcleoffer->moongcleoffer_status = $rateplan->rateplan_status;
					$moongcleoffer->save();
				}
			} else {
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

					if (empty($room)) {
						$roomtype = $service->fetchRoomtypeDetails($partner->partner_onda_idx, $eventDetail['roomtype_id']);

						$room = Room::firstOrNew([
							'room_onda_idx' => intval($roomtype['id']),
							'room_thirdparty' => 'onda',
						]);

						$room->fill([
							'partner_idx' => $partner->partner_idx,
							'room_name' => $roomtype['name'],
							'room_onda_idx' => intval($roomtype['id']),
							'room_thirdparty' => 'onda',
							'room_status' => $roomtype['status'],
							'room_updated_at' => Carbon::now(),
						])->save();

						$roomDraft = $room->draft ?: new RoomDraft();
						$roomDraft->fill([
							'room_idx' => $room->room_idx,
							'room_name' => $room->room_name,
							'room_onda_idx' => $room->room_onda_idx,
							'room_thirdparty' => $room->room_thirdparty,
							'room_is_approved' => true,
							'updated_at' => Carbon::now(),
						])->save();
					}

					$service->saveRoomtypeDetails($room);

					$room = Room::where('room_thirdparty', 'onda')
						->where('room_onda_idx', $eventDetail['roomtype_id'])
						->first();
				}

				$service = new OndaRateplanService();

				$service->saveRateplans($room);

				$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
					->where('rateplan_onda_idx', $eventDetail['rateplan_id'])
					->first();

				$service->saveRateplanDetails($roomRateplan);
			}
		}
	}

	private function handleInventoryUpdated($eventDetail)
	{
		$inventoryService = new OndaRoomInventoryService();

		$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
			->where('rateplan_onda_idx', $eventDetail[0]['rateplan_id'])
			->first();

		if (empty($roomRateplan)) {
			$partner = Partner::where('partner_thirdparty', 'onda')
				->where('partner_onda_idx', $eventDetail[0]['property_id'])
				->first();

			if (empty($partner)) {
				$service = new OndaPropertyService();
				$property = $service->fetchPropertyDetails($eventDetail[0]['property_id']);

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

			if (!empty($partner)) {
				$room = Room::where('room_thirdparty', 'onda')
					->where('room_onda_idx', $eventDetail[0]['roomtype_id'])
					->first();

				if (empty($room)) {
					$service = new OndaRoomService();

					$service->saveRoomtypes($partner);

					$room = Room::where('room_thirdparty', 'onda')
						->where('room_onda_idx', $eventDetail[0]['roomtype_id'])
						->first();

					if (empty($room)) {
						$roomtype = $service->fetchRoomtypeDetails($partner->partner_onda_idx, $eventDetail[0]['roomtype_id']);

						$room = Room::firstOrNew([
							'room_onda_idx' => intval($roomtype['id']),
							'room_thirdparty' => 'onda',
						]);

						$room->fill([
							'partner_idx' => $partner->partner_idx,
							'room_name' => $roomtype['name'],
							'room_onda_idx' => intval($roomtype['id']),
							'room_thirdparty' => 'onda',
							'room_status' => $roomtype['status'],
							'room_updated_at' => Carbon::now(),
						])->save();

						$roomDraft = $room->draft ?: new RoomDraft();
						$roomDraft->fill([
							'room_idx' => $room->room_idx,
							'room_name' => $room->room_name,
							'room_onda_idx' => $room->room_onda_idx,
							'room_thirdparty' => $room->room_thirdparty,
							'room_is_approved' => true,
							'updated_at' => Carbon::now(),
						])->save();
					}

					$service->saveRoomtypeDetails($room);

					$room = Room::where('room_thirdparty', 'onda')
						->where('room_onda_idx', $eventDetail[0]['roomtype_id'])
						->first();
				}

				if (!empty($room)) {
					$service = new OndaRateplanService();
					$service->saveRateplans($room);

					$roomRateplan = RoomRateplan::where('rateplan_thirdparty', 'onda')
						->where('rateplan_onda_idx', $eventDetail[0]['rateplan_id'])
						->first();

					$service->saveRateplanDetails($roomRateplan);
				}
			}
		}

		$moongcleoffer = null;

		$currentDate = new \DateTime($eventDetail[0]['date']);
		$endDate = new \DateTime($eventDetail[count($eventDetail) - 1]['date']);

		$i = 0;
		$allInventories = [];

		// if(count($eventDetail) == 1) {
		// 	$allInventories = $inventoryService->fetchInventories(
		// 		$eventDetail[0]['rateplan_id'],
		// 		$currentDate->format('Y-m-d'),
		// 		$currentDate->format('Y-m-d')
		// 	);
		// }

		// while ($currentDate < $endDate && $i < 8) {
		// 	// 89일 이후로 설정
		// 	$currentEndDate = (clone $currentDate)->add(new \DateInterval('P89D'));

		// 	// 최대 종료 날짜를 초과하지 않도록 조정
		// 	if ($currentEndDate > $endDate) {
		// 		$currentEndDate = $endDate;
		// 	}

		// 	// API 호출 로직
		// 	$inventories = $inventoryService->fetchInventories(
		// 		$eventDetail[0]['rateplan_id'],
		// 		$currentDate->format('Y-m-d'),
		// 		$currentEndDate->format('Y-m-d')
		// 	);

		// 	// 결과 병합
		// 	$allInventories = array_merge($allInventories, $inventories);

		// 	// 다음 시작 날짜로 갱신 (1일 후로 설정)
		// 	$currentDate = $currentEndDate->add(new \DateInterval('P1D'));

		// 	$i++;
		// }

		if (!empty($roomRateplan)) {
			$moongcleoffer = MoongcleOffer::where('moongcleoffer_category', 'roomRateplan')
				->where('base_product_idx', $roomRateplan->room_rateplan_idx)
				->where('moongcleoffer_status', 'enabled')
				->first();
		}

		// 병합된 데이터를 기반으로 RoomPrice 업데이트
		foreach ($eventDetail as $data) {
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

			$roomPrice->room_idx = $roomRateplan->room_idx;
			$roomPrice->rateplan_idx = $roomRateplan->rateplan_idx;
			$roomPrice->room_price_basic = $data['basic_price'];
			$roomPrice->room_price_sale = $data['sale_price'];
			$roomPrice->room_price_promotion_type = $data['promotion_type'];

			if (!empty($data['extra_adult'])) {
				$roomPrice->room_price_additional_adult = $data['extra_adult'];
			}
			if (!empty($data['extra_child'])) {
				$roomPrice->room_price_additional_child = $data['extra_child'];
			}
			if (!empty($data['extra_infant'])) {
				$roomPrice->room_price_additional_tiny = $data['extra_infant'];
			}

			$roomPrice->save();

			if (!empty($moongcleoffer)) {
				$moongcleofferPrice = MoongcleOfferPrice::firstOrNew([
					'moongcleoffer_idx' => $moongcleoffer->moongcleoffer_idx,
					'base_idx' => $roomPrice->room_price_idx,
					'base_type' => 'roomRateplan'
				]);

				$moongcleofferPrice->fill([
					'room_idx' => $roomPrice->room_idx,
					'rateplan_idx' => $roomPrice->rateplan_idx,
					'room_rateplan_idx' => $roomPrice->room_rateplan_idx,
					'moongcleoffer_price_date' => $roomPrice->room_price_date,
					'moongcleoffer_price_basic' => $roomPrice->room_price_basic,
					'moongcleoffer_price_sale' => ceil($roomPrice->room_price_sale - ($roomPrice->room_price_sale * ($moongcleoffer->minimum_discount / 100))),
					'moongcleoffer_discount_rate' => $moongcleoffer->minimum_discount,
				])->save();
			}
		}
	}
}