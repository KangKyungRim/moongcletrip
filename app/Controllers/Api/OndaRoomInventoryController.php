<?php

namespace App\Controllers\Api;

use App\Services\OndaRoomInventoryService;
use App\Models\Partner;
use App\Models\Room;
use App\Models\Rateplan;
use App\Models\RoomRateplan;
use App\Helpers\ResponseHelper;

class OndaRoomInventoryController
{
	public static function storeInventories()
	{
		try {
			$fromDate = date('Y-m-d');
			$toDate = date('Y-m-d', strtotime('+90 days'));

			$data = json_decode(file_get_contents("php://input"), true);

			if (!empty($data['fromDate'])) {
				$fromDate = $data['fromDate'];
			}
			if (!empty($data['toDate'])) {
				$toDate = $data['toDate'];
			}

			$rateplans = Rateplan::where('rateplan_thirdparty', 'onda')
				->where('rateplan_status', 'enabled')
				->get();

			$inventoryService = new OndaRoomInventoryService();

			foreach ($rateplans as $rateplan) {
				if (empty($rateplan->rateplan_onda_idx)) {
					continue;
				}

				$inventories = $inventoryService->fetchInventories($rateplan->rateplan_onda_idx, $fromDate, $toDate);

				$roomRateplan = RoomRateplan::where('rateplan_onda_idx', $rateplan->rateplan_onda_idx)->first();
				if ($roomRateplan) {
					$inventoryService->saveInventories($inventories, $roomRateplan);
				}
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 레이트플랜 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	public static function storeInventoriesCLI($offset = 0, $limit = 500)
	{
		try {
			$fromDate = date('Y-m-d');
			$toDate = date('Y-m-d', strtotime('+90 days'));

			// 입력 데이터 처리 (CLI를 통해 값이 들어오지 않을 경우 기본값 사용)
			$inventoryService = new OndaRoomInventoryService();

			// 지정된 범위에 따라 레이트플랜 가져오기
			$rateplans = Rateplan::where('rateplan_thirdparty', 'onda')
				->where('rateplan_status', 'enabled')
				->where('rateplan_idx', '>=', $offset) // 지정된 시작 인덱스
				->orderBy('rateplan_idx', 'asc')      // 아이디 순서대로 정렬
				->limit($limit)                      // 지정된 개수만큼만 가져오기
				->get();

			// rateplans가 비어 있으면 종료
			if ($rateplans->isEmpty()) {
				echo "No rateplans to process for offset: {$offset} and limit: {$limit}\n";
				return;
			}

			foreach ($rateplans as $rateplan) {
				try {
					$partner = Partner::find($rateplan->partner_idx);

					if ($partner->partner_status != 'enabled') {
						continue;
					}

					// 재고 데이터 가져오기
					$inventories = $inventoryService->fetchInventories($rateplan->rateplan_onda_idx, $fromDate, $toDate);

					// 해당 room_rateplan 찾기
					$roomRateplan = RoomRateplan::where('rateplan_onda_idx', $rateplan->rateplan_onda_idx)->first();

					if (!empty($roomRateplan) && !empty($inventories)) {
						// 재고 저장
						$inventoryService->saveInventories($inventories, $roomRateplan);
					} else {
						error_log("RoomRateplan not found for rateplan: {$rateplan->rateplan_onda_idx}");
					}
				} catch (\Exception $e) {
					// 각 rateplan 처리 중 발생한 에러 로깅
					error_log("Error processing rateplan {$rateplan->rateplan_onda_idx}: " . $e->getMessage());
					continue;
				}
			}

			echo "Processed rateplans from offset {$offset} with limit {$limit}\n";
		} catch (\Exception $e) {
			// 최상위 레벨에서 발생한 에러 처리
			error_log("Error in storeInventoriesCLI: " . $e->getMessage());
		}
	}

	public static function storeInventoriesAfter3Month()
	{
		try {
			$fromDate = date('Y-m-d', strtotime('+91 days'));
			$toDate = date('Y-m-d', strtotime('+180 days'));

			$inventoryService = new OndaRoomInventoryService();

			// 지정된 범위에 따라 레이트플랜 가져오기
			$rateplans = Rateplan::where('rateplan_thirdparty', 'onda')
				->where('rateplan_status', 'enabled')
				->orderBy('rateplan_idx', 'asc')
				->select('partner_idx', 'rateplan_onda_idx') // 특정 컬럼만 선택
				->get();

			// rateplans가 비어 있으면 종료
			if ($rateplans->isEmpty()) {
				return;
			}

			foreach ($rateplans as $rateplan) {
				try {
					$partner = Partner::find($rateplan->partner_idx);

					if ($partner->partner_status != 'enabled') {
						continue;
					}

					// 재고 데이터 가져오기
					$inventories = $inventoryService->fetchInventories($rateplan->rateplan_onda_idx, $fromDate, $toDate);

					// 해당 room_rateplan 찾기
					$roomRateplan = RoomRateplan::where('rateplan_onda_idx', $rateplan->rateplan_onda_idx)->first();

					if (!empty($roomRateplan) && !empty($inventories)) {
						// 재고 저장
						$inventoryService->saveInventories($inventories, $roomRateplan);
					} else {
						error_log("RoomRateplan not found for rateplan: {$rateplan->rateplan_onda_idx}");
					}
				} catch (\Exception $e) {
					// 각 rateplan 처리 중 발생한 에러 로깅
					error_log("Error processing rateplan {$rateplan->rateplan_onda_idx}: " . $e->getMessage());
					continue;
				}
			}

			echo "Processed rateplans from offset {$offset} with limit {$limit}\n";
		} catch (\Exception $e) {
			// 최상위 레벨에서 발생한 에러 처리
			error_log("Error in storeInventoriesCLI: " . $e->getMessage());
		}
	}

	public static function storeInventory()
	{
		try {
			$fromDate = date('Y-m-d');
			$toDate = date('Y-m-d', strtotime('+90 days'));

			$data = json_decode(file_get_contents("php://input"), true);

			if (!empty($data['fromDate'])) {
				$fromDate = $data['fromDate'];
			}
			if (!empty($data['toDate'])) {
				$toDate = $data['toDate'];
			}

			$rateplan = Rateplan::where('rateplan_onda_idx', $data['rateplanIdx'])
				->where('rateplan_status', 'enabled')
				->first();

			if (empty($rateplan->rateplan_onda_idx)) {
				return ResponseHelper::jsonResponse(['success' => false], 500);
			}

			$inventoryService = new OndaRoomInventoryService();

			$inventories = $inventoryService->fetchInventories($rateplan->rateplan_onda_idx, $fromDate, $toDate);

			$roomRateplan = RoomRateplan::where('rateplan_onda_idx', $rateplan->rateplan_onda_idx)->first();
			if ($roomRateplan) {
				$inventoryService->saveInventories($inventories, $roomRateplan);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '온다 레이트플랜 정보가 성공적으로 저장되었습니다.',
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
		}
	}
}
