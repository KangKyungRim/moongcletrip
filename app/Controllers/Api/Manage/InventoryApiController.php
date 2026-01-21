<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Room;
use App\Models\Rateplan;
use App\Models\RoomRateplan;
use App\Models\MoongcleOffer;
use App\Models\MoongcleOfferPrice;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;
use App\Models\RoomInventory;
use App\Models\RoomPrice;
use Carbon\Carbon;

use Database;

class InventoryApiController
{
	/**
	 * 요금 저장
	 */
	public static function saveRates()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || count($input['rooms']) === 0) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			foreach ($input['rooms'] as $roomIdx => $room) {
				$quantity = $room['quantity'];

				foreach ($quantity as $date => $q) {
					RoomInventory::where('room_idx', $roomIdx)
						->where('inventory_date', $date)
						->update(['inventory_quantity' => $q]);

					$roomInventory = RoomInventory::where('room_idx', $roomIdx)
						->where('rateplan_idx', 0)
						->where('inventory_date', $date)
						->first();

					if (empty($roomInventory)) {
						$roomInventory = new RoomInventory();
						$roomInventory->room_idx = $roomIdx;
						$roomInventory->inventory_date = $date;
						$roomInventory->inventory_quantity = $q;
						$roomInventory->save();
					}

					$roomPrices = RoomPrice::where('room_idx', $roomIdx)
						->where('room_price_date', $date)
						->get();

					foreach ($roomPrices as $roomPrice) {
						$roomInventory = RoomInventory::where('room_idx', $roomIdx)
							->where('rateplan_idx', $roomPrice->rateplan_idx)
							->where('inventory_date', $date)
							->first();

						if (empty($roomInventory)) {
							$roomInventory = new RoomInventory();
							$roomInventory->room_idx = $roomIdx;
							$roomInventory->rateplan_idx = $roomPrice->rateplan_idx;
							$roomInventory->room_rateplan_idx = $roomPrice->room_rateplan_idx;
							$roomInventory->inventory_date = $date;
							$roomInventory->inventory_quantity = $q;
							$roomInventory->save();
						}
					}
				}

				foreach ($room['rateplans'] as $rateplanIdx => $rateplan) {
					
					// 1. 관리자가 수정한 요금제의 room_rateplan 정보 조회
					$roomRateplan = RoomRateplan::where('room_idx', $roomIdx)
						->where('rateplan_idx', $rateplanIdx)
						->first();

					// 2. ★★★ 여기가 문제의 핵심 ★★★
    				// 시스템은 방금 수정한 room_rateplan_idx 기준으로 뭉클오퍼 조회
					//$moongcleoffer = MoongcleOffer::where('moongcleoffer_category', 'roomRateplan')
					//	->where('base_product_idx', $roomRateplan->room_rateplan_idx)
					//	->where('moongcleoffer_status', 'enabled')
					//	->first();

					$moongcleoffers = MoongcleOffer::join('stay_moongcleoffers', 'moongcleoffers.stay_moongcleoffer_idx', '=', 'stay_moongcleoffers.stay_moongcleoffer_idx')
                        ->where('moongcleoffers.base_product_idx', $roomRateplan->room_rateplan_idx)
                        ->where('moongcleoffers.moongcleoffer_status', 'enabled')
                        ->where('stay_moongcleoffers.stay_moongcleoffer_status', 'enabled')
                        ->select('moongcleoffers.*')
                        ->get();

					foreach ($rateplan['dates'] as $date => $data) {
						$roomInventory = RoomInventory::where('room_idx', $roomIdx)
							->where('rateplan_idx', $rateplanIdx)
							->where('inventory_date', $date)
							->first();

						$roomPrice = RoomPrice::where('room_idx', $roomIdx)
							->where('rateplan_idx', $rateplanIdx)
							->where('room_price_date', $date)
							->first();

						if (empty($roomInventory)) {
							$roomInventoryOrigin = RoomInventory::where('room_idx', $roomIdx)
								->where('rateplan_idx', 0)
								->where('inventory_date', $date)
								->first();

							if (!empty($roomInventoryOrigin)) {
								$roomInventory = new RoomInventory();
								$roomInventory->room_idx = $roomIdx;
								$roomInventory->rateplan_idx = $rateplanIdx;
								$roomInventory->room_rateplan_idx = $roomRateplan->room_rateplan_idx;
								$roomInventory->inventory_date = $date;
								$roomInventory->inventory_quantity = $roomInventoryOrigin->inventory_quantity;
								$roomInventory->inventory_sold_quantity = $roomInventoryOrigin->inventory_sold_quantity;
								$roomInventory->save();
							}
						}

						if (!isset($data['priceBasic']) && !isset($data['isClosed'])) {
							continue;
						}

						if (empty($roomPrice)) {
							$roomPrice = new RoomPrice();
							$roomPrice->room_idx = $roomIdx;
							$roomPrice->rateplan_idx = $rateplanIdx;
							$roomPrice->room_rateplan_idx = $roomRateplan->room_rateplan_idx;
							$roomPrice->room_price_date = $date;
						}

						if (isset($data['priceBasic'])) {
							$roomPrice->room_price_basic = $data['priceBasic'];
							$roomPrice->room_price_sale = $data['priceSale'];
							$roomPrice->room_price_sale_percent = $data['salePercent'];
						}

						if (!empty($data['priceCurrency'])) {
							$roomPrice->room_price_currency = $data['priceCurrency'];
						}

						if (isset($data['isClosed'])) {
							$roomPrice->is_closed = $data['isClosed'];
						}

						// 3. 원본 가격(RoomPrice)은 정상적으로 업데이트된다.
						$roomPrice->save();

						// 4. 하지만 $moongcleoffer가 null이기 때문에, 아래 if 문은 false가 되고,
        				//    뭉클딜 가격을 업데이트하는 로직 전체를 그냥 건너뛰어 버린다!
						// ★★★★★ 시작: 수정된 뭉클오퍼 가격 업데이트 로직 ★★★★★
                        //    반복문으로 모든 뭉클딜을 하나씩 처리한다.
						foreach ($moongcleoffers as $moongcleoffer) {
							if (isset($data['priceBasic'])) {
                                MoongcleOfferPrice::updateOrCreate(
                                    [
                                        // 식별 조건
                                        'moongcleoffer_idx' => $moongcleoffer->moongcleoffer_idx,
                                        'moongcleoffer_price_date' => $date,
                                    ],
                                    [
                                        // 업데이트할 내용
                                        'moongcleoffer_price_basic' => $roomPrice->room_price_basic,
                                        'moongcleoffer_price_sale' => ceil($roomPrice->room_price_basic - ($roomPrice->room_price_basic * ($moongcleoffer->minimum_discount / 100))),
                                        'moongcleoffer_discount_rate' => $moongcleoffer->minimum_discount,
                                        // ... 기타 필요한 모든 필드를 채워준다.
                                        'room_idx' => $roomPrice->room_idx,
                                        'rateplan_idx' => $roomPrice->rateplan_idx,
                                        'room_rateplan_idx' => $roomPrice->room_rateplan_idx,
                                        'base_type' => 'roomRateplan',
                                    ]
                                );
                            }
						}
					}
				}
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '요금을 저장했습니다.',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '요금 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function saveRatesRange()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || count($input['rooms']) === 0 || count($input['dates']) === 0 || empty($input['dateType']) || empty($input['dayOfWeek'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			$filteredDates = [];

			if ($input['dateType'] === 'range') {
				$startDate = new \DateTime($input['dates'][0]);
				$endDate = new \DateTime($input['dates'][1]);
				$endDate->modify('+1 day');

				$interval = new \DateInterval('P1D');
				$dateRange = new \DatePeriod($startDate, $interval, $endDate);

				foreach ($dateRange as $date) {
					$dayOfWeek = $date->format('D');

					if (in_array($dayOfWeek, $input['dayOfWeek'])) {
						$filteredDates[] = $date->format('Y-m-d');
					}
				}
			} else {
				$filteredDates = $input['dates'];
			}

			$insertRoomInventories = [];
			$bindingsRoomInventories = [];

			$insertRoomPrices1 = [];
			$insertRoomPrices2 = [];
			$insertRoomPriceInventories = [];
			$bindingsRoomPrices1 = [];
			$bindingsRoomPrices2 = [];
			$bindingsRoomPriceInventories = [];

			$moongcleOfferPriceInserts = [];
			$moongcleOfferBindings = [];

			foreach ($input['rooms'] as $roomIdx => $room) {
				$quantity = $room['quantity'];

				if (isset($quantity)) {
					foreach ($filteredDates as $date) {
						$roomInventoryOrigin = RoomInventory::where('room_idx', $roomIdx)
							->where('rateplan_idx', 0)
							->where('inventory_date', $date)
							->first();

						$inventoryQuantity = $quantity;
						$inventorySoldQuantity = $roomInventoryOrigin ? $roomInventoryOrigin->inventory_sold_quantity : 0;

						$insertRoomInventories[] = "(?, ?, ?, ?, ?, ?, ?)";
						$bindingsRoomInventories[] = $roomIdx;
						$bindingsRoomInventories[] = 0;
						$bindingsRoomInventories[] = null;
						$bindingsRoomInventories[] = $date;
						$bindingsRoomInventories[] = $inventoryQuantity;
						$bindingsRoomInventories[] = $inventorySoldQuantity;
						$bindingsRoomInventories[] = date('Y-m-d H:i:s');
						$bindingsRoomInventories[] = date('Y-m-d H:i:s');
					}

					$roomRateplans = RoomRateplan::where('room_idx', $roomIdx)
						->get();

					foreach ($roomRateplans as $roomRateplan) {
						foreach ($filteredDates as $date) {
							$roomInventoryOrigin = RoomInventory::where('room_idx', $roomIdx)
								->where('rateplan_idx', 0)
								->where('inventory_date', $date)
								->first();

							$inventoryQuantity = $quantity;
							$inventorySoldQuantity = $roomInventoryOrigin ? $roomInventoryOrigin->inventory_sold_quantity : 0;

							$insertRoomInventories[] = "(?, ?, ?, ?, ?, ?, ?, ?)";
							$bindingsRoomInventories[] = $roomIdx;
							$bindingsRoomInventories[] = $roomRateplan->rateplan_idx;
							$bindingsRoomInventories[] = $roomRateplan->room_rateplan_idx;
							$bindingsRoomInventories[] = $date;
							$bindingsRoomInventories[] = $inventoryQuantity;
							$bindingsRoomInventories[] = $inventorySoldQuantity;
							$bindingsRoomInventories[] = date('Y-m-d H:i:s');
							$bindingsRoomInventories[] = date('Y-m-d H:i:s');
						}
					}
				}

				foreach ($room['rateplans'] as $rateplanIdx => $rateplan) {
					$roomRateplan = RoomRateplan::where('room_idx', $roomIdx)
						->where('rateplan_idx', $rateplanIdx)
						->first();

					$moongcleoffer = MoongcleOffer::where('moongcleoffer_category', 'roomRateplan')
						->where('base_product_idx', $roomRateplan->room_rateplan_idx)
						->first();

					foreach ($filteredDates as $date) {
						if (!isset($rateplan['priceBasic']) && !isset($rateplan['isClosed'])) {
							continue;
						}

						if (isset($rateplan['priceBasic'])) {
							$insertRoomPrices1[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?)";

							$bindingsRoomPrices1[] = $roomIdx;
							$bindingsRoomPrices1[] = $rateplanIdx;
							$bindingsRoomPrices1[] = $roomRateplan->room_rateplan_idx;
							$bindingsRoomPrices1[] = $date;
							$bindingsRoomPrices1[] = $rateplan['priceBasic'];
							$bindingsRoomPrices1[] = $rateplan['priceSale'];
							$bindingsRoomPrices1[] = $rateplan['salePercent'];
							$bindingsRoomPrices1[] = date('Y-m-d H:i:s');
							$bindingsRoomPrices1[] = date('Y-m-d H:i:s');

							if ($moongcleoffer && $moongcleoffer->moongcleoffer_status === 'enabled') {
								$offerPriceSale = ceil($rateplan['priceBasic'] * (1 - $moongcleoffer->minimum_discount / 100));
								$moongcleOfferPriceInserts[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

								$moongcleOfferBindings[] = $moongcleoffer->moongcleoffer_idx;
								$moongcleOfferBindings[] = $roomIdx;
								$moongcleOfferBindings[] = $rateplanIdx;
								$moongcleOfferBindings[] = $roomRateplan->room_rateplan_idx;
								$moongcleOfferBindings[] = 'roomRateplan';
								$moongcleOfferBindings[] = $date;
								$moongcleOfferBindings[] = $rateplan['priceBasic'];
								$moongcleOfferBindings[] = $offerPriceSale;
								$moongcleOfferBindings[] = $moongcleoffer->minimum_discount;
								$moongcleOfferBindings[] = date('Y-m-d H:i:s');
								$moongcleOfferBindings[] = date('Y-m-d H:i:s');
							}
						}
						if (isset($rateplan['isClosed'])) {
							// $insertRoomPrices2[] = "SELECT ? AS room_idx, ? AS rateplan_idx, ? AS room_rateplan_idx, ? AS room_price_date, ? AS is_closed";

							// $bindingsRoomPrices2[] = $roomIdx;
							// $bindingsRoomPrices2[] = $rateplanIdx;
							// $bindingsRoomPrices2[] = $roomRateplan->room_rateplan_idx;
							// $bindingsRoomPrices2[] = $date;
							// $bindingsRoomPrices2[] = $rateplan['isClosed'];

							$insertRoomPrices2[] = sprintf(
								"SELECT %d AS room_idx, %d AS rateplan_idx, %d AS room_rateplan_idx, '%s' AS room_price_date, %d AS is_closed",
								$roomIdx,
								$rateplanIdx,
								$roomRateplan->room_rateplan_idx,
								$date,
								$rateplan['isClosed']
							);
						}

						$roomInventoryOrigin = RoomInventory::where('room_idx', $roomIdx)
							->where('rateplan_idx', 0)
							->where('inventory_date', $date)
							->first();

						if (isset($quantity)) {
							$inventoryQuantity = $quantity;
						} else {
							$inventoryQuantity = $roomInventoryOrigin ? $roomInventoryOrigin->inventory_quantity : 0;
						}

						$inventorySoldQuantity = $roomInventoryOrigin ? $roomInventoryOrigin->inventory_sold_quantity : 0;

						$insertRoomPriceInventories[] = "(?, ?, ?, ?, ?, ?, ?, ?)";
						$bindingsRoomPriceInventories[] = $roomIdx;
						$bindingsRoomPriceInventories[] = $rateplanIdx;
						$bindingsRoomPriceInventories[] = $roomRateplan->room_rateplan_idx;
						$bindingsRoomPriceInventories[] = $date;
						$bindingsRoomPriceInventories[] = $inventoryQuantity;
						$bindingsRoomPriceInventories[] = $inventorySoldQuantity;
						$bindingsRoomPriceInventories[] = date('Y-m-d H:i:s');
						$bindingsRoomPriceInventories[] = date('Y-m-d H:i:s');
					}
				}
			}

			if (!empty($insertRoomInventories)) {
				$values = [];
				foreach ($bindingsRoomInventories as $i => $val) {
					if ($i % 8 === 0) {
						$group = [];
					}
	
					if (is_null($val)) {
						$group[] = "NULL";
					} elseif (!is_numeric($val)) {
						$group[] = "'" . $val . "'";
					} else {
						$group[] = $val;
					}
	
					if (($i + 1) % 8 === 0) {
						$values[] = "(" . implode(", ", $group) . ")";
					}
				}

				$sqlInventories = "
					INSERT INTO room_inventories (room_idx, rateplan_idx, room_rateplan_idx, inventory_date, inventory_quantity, inventory_sold_quantity, created_at, updated_at)
					VALUES " . implode(", ", $values) . "
					ON DUPLICATE KEY UPDATE 
						inventory_quantity = VALUES(inventory_quantity),
						inventory_sold_quantity = VALUES(inventory_sold_quantity),
						updated_at = VALUES(updated_at)
				";
				Database::getInstance()->getConnection()->statement($sqlInventories);
			}

			if (!empty($insertRoomPrices1)) {
				$values = [];
				foreach ($bindingsRoomPrices1 as $i => $val) {
					if ($i % 9 === 0) {
						$group = [];
					}
	
					if (is_null($val)) {
						$group[] = "NULL";
					} elseif (!is_numeric($val)) {
						$group[] = "'" . $val . "'";
					} else {
						$group[] = $val;
					}
	
					if (($i + 1) % 9 === 0) {
						$values[] = "(" . implode(", ", $group) . ")";
					}
				}

				$sql = "
					INSERT INTO room_prices (room_idx, rateplan_idx, room_rateplan_idx, room_price_date, 
											room_price_basic, room_price_sale, room_price_sale_percent, created_at, updated_at)
					VALUES " . implode(", ", $values) . "
					ON DUPLICATE KEY UPDATE 
						room_price_basic = IF(VALUES(room_price_basic) IS NOT NULL, VALUES(room_price_basic), room_price_basic), 
						room_price_sale = IF(VALUES(room_price_sale) IS NOT NULL, VALUES(room_price_sale), room_price_sale),
						room_price_sale_percent = IF(VALUES(room_price_sale_percent) IS NOT NULL, VALUES(room_price_sale_percent), room_price_sale_percent),
						updated_at = VALUES(updated_at)
				";

				Database::getInstance()->getConnection()->statement($sql);
			}

			if (!empty($insertRoomPrices2)) {
				// $sql = "
				// 	UPDATE room_prices rp
				// 	JOIN (
				// 		" . implode(" UNION ALL ", $insertRoomPrices2) . "
				// 	) AS new_data
				// 	ON rp.room_idx = new_data.room_idx
				// 	AND rp.rateplan_idx = new_data.rateplan_idx
				// 	AND rp.room_rateplan_idx = new_data.room_rateplan_idx
				// 	AND rp.room_price_date = new_data.room_price_date
				// 	SET rp.is_closed = new_data.is_closed
				// 	WHERE new_data.is_closed IS NOT NULL;
				// ";

				// Database::getInstance()->getConnection()->statement($sql, $bindingsRoomPrices2);
				$sql = "
					UPDATE room_prices rp
					JOIN (
						" . implode(" UNION ALL ", $insertRoomPrices2) . "
					) AS new_data
					ON rp.room_idx = new_data.room_idx
					AND rp.rateplan_idx = new_data.rateplan_idx
					AND rp.room_rateplan_idx = new_data.room_rateplan_idx
					AND rp.room_price_date = new_data.room_price_date
					SET rp.is_closed = new_data.is_closed
					WHERE new_data.is_closed IS NOT NULL
				";

				Database::getInstance()->getConnection()->statement($sql);
			}

			if (!empty($insertRoomPriceInventories)) {
				$values = [];
				foreach ($bindingsRoomPriceInventories as $i => $val) {
					if ($i % 8 === 0) {
						$group = [];
					}
	
					if (is_null($val)) {
						$group[] = "NULL";
					} elseif (!is_numeric($val)) {
						$group[] = "'" . $val . "'";
					} else {
						$group[] = $val;
					}
	
					if (($i + 1) % 8 === 0) {
						$values[] = "(" . implode(", ", $group) . ")";
					}
				}

				$sqlInventories = "
					INSERT INTO room_inventories (room_idx, rateplan_idx, room_rateplan_idx, inventory_date, 
												  inventory_quantity, inventory_sold_quantity, created_at, updated_at)
					VALUES " . implode(", ", $values) . "
					ON DUPLICATE KEY UPDATE 
						inventory_quantity = COALESCE(VALUES(inventory_quantity), inventory_quantity), 
						inventory_sold_quantity = COALESCE(VALUES(inventory_sold_quantity), inventory_sold_quantity),
						updated_at = VALUES(updated_at)
				";
				Database::getInstance()->getConnection()->statement($sqlInventories);
			}

			if (!empty($moongcleOfferPriceInserts)) {
				$values = [];
				foreach ($moongcleOfferBindings as $i => $val) {
					if ($i % 11 === 0) {
						$group = [];
					}
	
					if (is_null($val)) {
						$group[] = "NULL";
					} elseif (!is_numeric($val)) {
						$group[] = "'" . $val . "'";
					} else {
						$group[] = $val;
					}
	
					if (($i + 1) % 11 === 0) {
						$values[] = "(" . implode(", ", $group) . ")";
					}
				}

				$sql = "
					INSERT INTO moongcleoffer_prices (moongcleoffer_idx, room_idx, rateplan_idx, room_rateplan_idx, base_type, moongcleoffer_price_date, moongcleoffer_price_basic, moongcleoffer_price_sale, moongcleoffer_discount_rate, created_at, updated_at) VALUES " . implode(", ", $values) . " 
					ON DUPLICATE KEY UPDATE 
						moongcleoffer_price_basic = VALUES(moongcleoffer_price_basic), 
						moongcleoffer_price_sale = VALUES(moongcleoffer_price_sale), 
						moongcleoffer_discount_rate = VALUES(moongcleoffer_discount_rate),
						updated_at = VALUES(updated_at)
				";
				Database::getInstance()->getConnection()->statement($sql);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '요금을 저장했습니다.',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '요금 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function saveRatesDiscount()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || count($input['rooms']) === 0 || count($input['dates']) === 0 || empty($input['dateType']) || empty($input['dayOfWeek'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			$filteredDates = [];

			if ($input['dateType'] === 'range') {
				$startDate = new \DateTime($input['dates'][0]);
				$endDate = new \DateTime($input['dates'][1]);
				$endDate->modify('+1 day');

				$interval = new \DateInterval('P1D');
				$dateRange = new \DatePeriod($startDate, $interval, $endDate);

				foreach ($dateRange as $date) {
					$dayOfWeek = $date->format('D');

					if (in_array($dayOfWeek, $input['dayOfWeek'])) {
						$filteredDates[] = $date->format('Y-m-d');
					}
				}
			} else {
				$filteredDates = $input['dates'];
			}

			foreach ($input['rooms'] as $roomIdx => $room) {
				foreach ($room['rateplans'] as $rateplanIdx => $rateplan) {
					foreach ($filteredDates as $date) {
						$roomPrice = RoomPrice::where('room_idx', $roomIdx)
							->where('rateplan_idx', $rateplanIdx)
							->where('room_price_date', $date)
							->first();

						if (empty($roomPrice)) {
							continue;
						}

						if (isset($rateplan['salePercent'])) {
							$roomPrice->room_price_sale_percent = $rateplan['salePercent'];
							$roomPrice->room_price_sale = ceil($roomPrice->room_price_basic - ($roomPrice->room_price_basic * ($rateplan['salePercent'] / 100)));
							$roomPrice->save();
						}
					}
				}
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '요금을 저장했습니다.',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '요금 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function saveRoomStatus()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || count($input['dates']) === 0 || empty($input['rooms'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			$filteredDates = $input['dates'];

			foreach ($input['rooms'] as $roomIdx => $status) {
				RoomInventory::where('room_idx', $roomIdx)
					->whereIn('inventory_date', $filteredDates)
					->update(['is_closed' => $status]);

				RoomPrice::where('room_idx', $roomIdx)
					->whereIn('room_price_date', $filteredDates)
					->update(['is_closed' => $status]);
			}

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '마감여부를 변경했습니다.',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '마감여부 변경에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
