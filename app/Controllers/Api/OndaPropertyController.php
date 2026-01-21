<?php

namespace App\Controllers\Api;

use App\Services\OndaPropertyService;
use App\Services\OndaRoomService;
use App\Services\OndaRateplanService;
use App\Services\OndaRoomInventoryService;

use App\Models\Partner;
use App\Models\Room;
use App\Models\RoomRateplan;

use App\Helpers\ResponseHelper;

class OndaPropertyController
{
	public static function storeProperties()
	{
		try {
			$service = new OndaPropertyService();
			$properties = $service->fetchAllProperties();

			foreach ($properties as $property) {
				$service->saveOrUpdatePartner($property);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 숙소 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeDetailProperties()
	{
		try {
			$service = new OndaPropertyService();
			$partners = Partner::where('partner_thirdparty', 'onda')->get();

			foreach ($partners as $partner) {
				if (empty($partner->partner_onda_idx)) continue;

				$property = $service->fetchPropertyDetails($partner->partner_onda_idx);
				$service->updatePartnerDetails($partner, $property);

				if(!empty($property['classifications'])) {
					$property['tags']['classifications'] = $property['classifications'];
				}

				$stay = $service->saveOrUpdateStay($partner, $property);
				$service->saveTagConnections($stay->stay_idx, $property['tags']);
				$service->saveImages($stay->stay_idx, $property['images'], 'basic');
				$service->saveCancelRules($partner->partner_idx, $property['property_refunds']);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 숙소 상세 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeDetailProperty()
	{
		try {
			$input = json_decode(file_get_contents("php://input"), true);

			$propertyIdx = $input['propertyIdx'];

			$partner = Partner::where('partner_thirdparty', 'onda')
				->where('partner_onda_idx', $propertyIdx)
				->first();

			if (empty($partner)) {
				return ResponseHelper::jsonResponse(['success' => false], 500);
			}

			$service = new OndaPropertyService();

			$property = $service->fetchPropertyDetails($partner->partner_onda_idx);
			$service->updatePartnerDetails($partner, $property);

			if(!empty($property['classifications'])) {
				$property['tags']['classifications'] = $property['classifications'];
			}

			$stay = $service->saveOrUpdateStay($partner, $property);
			$service->saveTagConnections($stay->stay_idx, $property['tags']);
			$service->saveImages($stay->stay_idx, $property['images'], 'basic');
			$service->saveCancelRules($partner->partner_idx, $property['property_refunds']);

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 숙소 상세 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeDetailPropertyAll()
	{
		try {
			$input = json_decode(file_get_contents("php://input"), true);

			$propertyIdx = $input['propertyIdx'];

			$service = new OndaPropertyService();

			$property = $service->fetchPropertyDetails($propertyIdx);
			$partner = $service->saveOrUpdatePartner($property);

			$service->updatePartnerDetails($partner, $property);

			if (!empty($partner)) {
				if(!empty($property['classifications'])) {
					$property['tags']['classifications'] = $property['classifications'];
				}

				$stay = $service->saveOrUpdateStay($partner, $property);
				$service->saveTagConnections($stay->stay_idx, $property['tags']);
				$service->saveImages($stay->stay_idx, $property['images'], 'basic');
				$service->saveCancelRules($partner->partner_idx, $property['property_refunds']);

				OndaRoomController::storeRoomtypesById($partner);

				$rooms = Room::where('partner_idx', $partner->partner_idx)->get();
				$roomService = new OndaRoomService();
				$RateplanService = new OndaRateplanService();
				$inventoryService = new OndaRoomInventoryService();

				foreach ($rooms as $room) {
					$roomService->saveRoomtypeDetails($room);

					$RateplanService->saveRateplans($room);

					$roomRateplans = RoomRateplan::where('room_idx', $room->room_idx)->get();

					foreach ($roomRateplans as $roomRateplan) {
						$RateplanService->saveRateplanDetails($roomRateplan);

						if ($roomRateplan->room_rateplan_status == 'disabled') {
							continue;
						}

						$fromDate = date('Y-m-d');
						$toDate = date('Y-m-d', strtotime('+90 days'));

						$inventories = $inventoryService->fetchInventories($roomRateplan->rateplan_onda_idx, $fromDate, $toDate);
						$inventoryService->saveInventories($inventories, $roomRateplan);
					}
				}
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 숙소 상세 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}
}
