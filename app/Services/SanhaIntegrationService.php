<?php

namespace App\Services;

use App\Models\MoongcleOffer;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Room;
use App\Models\Rateplan;
use App\Models\RoomRateplan;
use App\Models\SanhaLog;
use App\Models\SanhaReservationResend;
use App\Models\RoomInventory;
use App\Models\RoomPrice;

use App\Services\SanhaResponseService;

use Carbon\Carbon;
use Database;

class SanhaIntegrationService
{
	public static function availabilityAndRates($rawPostData)
	{
		if ($rawPostData === false || empty($rawPostData)) {
			SanhaResponseService::noData();
			exit;
		}

		// $parsedData = '';
		// parse_str($rawPostData, $parsedData);

		$xml = simplexml_load_string($rawPostData);

		if ($xml === false) {
			SanhaResponseService::warningW101(false);
		}

		$request = [
			$xml->getName() => xmlToArray($xml)
		];

		if (empty($request)) {
			SanhaResponseService::warningW101(false);
		}

		if ($request['OTAAvailabilityAndRates_RQ']['@attributes']['ChannelID'] != $_ENV['SANHA_CHANNEL_ID']) {
			SanhaResponseService::errorE102(false);
		}

		$partnerIdx = $request['OTAAvailabilityAndRates_RQ']['@attributes']['OTAHotelID'];
		$partner = Partner::find($partnerIdx);

		if ($partner->partner_thirdparty !== 'sanha') {
			SanhaResponseService::errorE102(false);
		}

		$roomInventories = [];
		$roomPrices = [];
		$moongcleOfferPriceInserts = [];
		$moongcleOfferBindings = [];

		if (empty($request['OTAAvailabilityAndRates_RQ']['AvailRateInformationData'][0])) {
			$request['OTAAvailabilityAndRates_RQ']['AvailRateInformationData'] = [
				$request['OTAAvailabilityAndRates_RQ']['AvailRateInformationData']
			];
		}

		foreach ($request['OTAAvailabilityAndRates_RQ']['AvailRateInformationData'] as $rateInformation) {
			$roomIdx = $rateInformation['AvailRateInformation']['@attributes']['RoomTypeCode'];
			$room = Room::find($roomIdx);
			if (empty($room)) SanhaResponseService::errorE301(false);

			if (empty($rateInformation['DateRange'][0])) {
				$rateInformation['DateRange'] = [$rateInformation['DateRange']];
			}

			$dateList = [];
			foreach ($rateInformation['DateRange'] as $dateInformation) {
				$start = new \DateTime($dateInformation['@attributes']['from']);
				$end = new \DateTime($dateInformation['@attributes']['to']);
				$end->modify('+1 day');
				$interval = new \DateInterval('P1D');
				$period = new \DatePeriod($start, $interval, $end);
				foreach ($period as $date) {
					$dateList[] = $date->format('Y-m-d');
				}
			}

			$allotment = $rateInformation['AvailRateInformation']['Availability']['@attributes']['Allotment'];
			$reservationCount = $rateInformation['AvailRateInformation']['Availability']['@attributes']['ReservationCount'];
			$isClosed = $rateInformation['AvailRateInformation']['Availability']['@attributes']['Closed'] == 'true' ? 1 : 0;

			if (empty($rateInformation['AvailRateInformation']['RateTypes'][0])) {
				$rateInformation['AvailRateInformation']['RateTypes'] = [$rateInformation['AvailRateInformation']['RateTypes']];
			}

			foreach ($dateList as $date) {
				$roomInventories[] = [
					'room_idx' => $room->room_idx,
					'rateplan_idx' => 0,
					'room_rateplan_idx' => null,
					'inventory_date' => $date,
					'inventory_quantity' => $allotment - $reservationCount,
					'inventory_sold_quantity' => $reservationCount,
					'is_closed' => $isClosed,
					date('Y-m-d H:i:s'),
					date('Y-m-d H:i:s')
				];

				foreach ($rateInformation['AvailRateInformation']['RateTypes'] as $rateType) {
					$rateplanIdx = $rateType['@attributes']['RateTypeCode'];

					$roomRateplan = RoomRateplan::firstOrCreate([
						'room_idx' => $room->room_idx,
						'rateplan_idx' => $rateplanIdx
					], [
						'partner_idx' => $partner->partner_idx,
						'rateplan_thirdparty' => 'sanha',
						'room_rateplan_status' => 'enabled'
					]);

					$roomInventories[] = [
						'room_idx' => $room->room_idx,
						'rateplan_idx' => $rateplanIdx,
						'room_rateplan_idx' => $roomRateplan->room_rateplan_idx,
						'inventory_date' => $date,
						'inventory_quantity' => $allotment - $reservationCount,
						'inventory_sold_quantity' => $reservationCount,
						'is_closed' => $isClosed,
						date('Y-m-d H:i:s'),
						date('Y-m-d H:i:s')
					];
				}
			}

			foreach ($rateInformation['AvailRateInformation']['RateTypes'] as $rateType) {
				$rateplan = Rateplan::find($rateType['@attributes']['RateTypeCode']);
				if (empty($rateplan)) continue;

				// if (!empty($rateInformation['AvailRateInformation']['Availability']['Restrictions'])) {
				// 	$minLos = $rateInformation['AvailRateInformation']['Availability']['Restrictions']['@attributes']['MinLos'];
				// 	$maxLos = $rateInformation['AvailRateInformation']['Availability']['Restrictions']['@attributes']['MaxLos'];
				// 	$cutOffDays = $rateInformation['AvailRateInformation']['Availability']['Restrictions']['@attributes']['CutOffDays'];

				// 	$rateplan->rateplan_stay_min = $minLos;
				// 	$rateplan->rateplan_stay_max = $maxLos;
				// 	$rateplan->rateplan_cutoff_days = $cutOffDays;
				// 	$rateplan->save();
				// }

				$roomRateplan = RoomRateplan::firstOrCreate([
					'room_idx' => $room->room_idx,
					'rateplan_idx' => $rateplan->rateplan_idx
				], [
					'partner_idx' => $partner->partner_idx,
					'rateplan_thirdparty' => 'sanha',
					'room_rateplan_status' => 'enabled'
				]);

				$price = $rateType['PerRate']['@attributes']['RateAmount'];
				$closeFlag = ($isClosed || $price == 0 || $price == '0') ? 1 : 0;

				$existingSalePercents = RoomPrice::where('room_rateplan_idx', $roomRateplan->room_rateplan_idx)
					->whereIn('room_price_date', $dateList)
					->get()
					->keyBy(function ($item) {
						return $item->room_rateplan_idx . '|' . explode(' ', $item->room_price_date)[0];
					});

				foreach ($dateList as $date) {
					$key = $roomRateplan->room_rateplan_idx . '|' . $date;

					$salePercent = 0;

					if (isset($existingSalePercents[$key]) && !is_null($existingSalePercents[$key]->room_price_sale_percent)) {
						$salePercent = $existingSalePercents[$key]->room_price_sale_percent;
					}

					$room_price_sale = $price * (1 - $salePercent / 100);

					$roomPrices[] = [
						'room_idx' => $roomRateplan->room_idx,
						'rateplan_idx' => $roomRateplan->rateplan_idx,
						'room_rateplan_idx' => $roomRateplan->room_rateplan_idx,
						'room_price_date' => $date,
						'room_price_basic' => $price,
						'room_price_sale' => $room_price_sale,
						'room_price_sale_percent' => $salePercent,
						'room_price_currency' => 'KRW',
						'is_closed' => $closeFlag,
						date('Y-m-d H:i:s'),
						date('Y-m-d H:i:s')
					];

					$moongcleoffer = MoongcleOffer::where('moongcleoffer_category', 'roomRateplan')
						->where('base_product_idx', $roomRateplan->room_rateplan_idx)
						->first();

					if ($moongcleoffer && $moongcleoffer->moongcleoffer_status === 'enabled') {
						$offerPriceSale = ceil($price * (1 - $moongcleoffer->minimum_discount / 100));
						$moongcleOfferPriceInserts[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

						$moongcleOfferBindings[] = $moongcleoffer->moongcleoffer_idx;
						$moongcleOfferBindings[] = $room->room_idx;
						$moongcleOfferBindings[] = $rateplan->rateplan_idx;
						$moongcleOfferBindings[] = $roomRateplan->room_rateplan_idx;
						$moongcleOfferBindings[] = 'roomRateplan';
						$moongcleOfferBindings[] = $date;
						$moongcleOfferBindings[] = $price;
						$moongcleOfferBindings[] = $offerPriceSale;
						$moongcleOfferBindings[] = $moongcleoffer->minimum_discount;
						$moongcleOfferBindings[] = date('Y-m-d H:i:s');
						$moongcleOfferBindings[] = date('Y-m-d H:i:s');
					}
				}
			}
		}

		if (!empty($roomInventories)) {
			$values = [];
			foreach ($roomInventories as $inv) {
				$values[] = "(" . implode(", ", array_map(function ($value) {
					if (is_null($value)) {
						return "NULL";
					}
					if (!is_numeric($value)) {
						return "'" . $value . "'";
					}
					return $value;
				}, array_values($inv))) . ")";
			}

			$sqlInv = "INSERT INTO room_inventories 
				(room_idx, rateplan_idx, room_rateplan_idx, inventory_date, inventory_quantity, inventory_sold_quantity, is_closed, created_at, updated_at)
				VALUES " . implode(", ", $values) . "
				ON DUPLICATE KEY UPDATE 
					inventory_quantity = VALUES(inventory_quantity),
					inventory_sold_quantity = VALUES(inventory_sold_quantity),
					is_closed = VALUES(is_closed),
					updated_at = VALUES(updated_at)";

			Database::getInstance()->getConnection()->statement($sqlInv);
		}

		if (!empty($roomPrices)) {
			$values = [];
			foreach ($roomPrices as $price) {
				$values[] = "(" . implode(", ", array_map(function ($value) {
					if (is_null($value)) {
						return "NULL";
					}
					if (!is_numeric($value)) {
						return "'" . $value . "'";
					}
					return $value;
				}, array_values($price))) . ")";
			}

			$sqlPrice = "INSERT INTO room_prices (room_idx, rateplan_idx, room_rateplan_idx, room_price_date, room_price_basic, room_price_sale, room_price_sale_percent, room_price_currency, is_closed, created_at, updated_at)
				VALUES " . implode(", ", $values) . "
				ON DUPLICATE KEY UPDATE 
					room_price_basic = VALUES(room_price_basic),
					room_price_sale = VALUES(room_price_sale),
					room_price_sale_percent = VALUES(room_price_sale_percent),
					room_price_currency = VALUES(room_price_currency),
					is_closed = VALUES(is_closed),
					updated_at = VALUES(updated_at)";

			Database::getInstance()->getConnection()->statement($sqlPrice);
		}

		if (!empty($moongcleOfferPriceInserts)) {
			$values = [];

			foreach ($moongcleOfferBindings as $i => $val) {
				// 12개씩 묶어서 처리 (VALUES 절마다 12개 항목이 있어야 함)
				if ($i % 11 === 0) {
					$group = [];
				}

				// 값 가공
				if (is_null($val)) {
					$group[] = "NULL";
				} elseif (!is_numeric($val)) {
					$group[] = "'" . $val . "'";
				} else {
					$group[] = $val;
				}

				// 하나의 row 완성
				if (($i + 1) % 11 === 0) {
					$values[] = "(" . implode(", ", $group) . ")";
				}
			}

			$sql = "
				INSERT INTO moongcleoffer_prices 
					(moongcleoffer_idx, room_idx, rateplan_idx, room_rateplan_idx, base_type, moongcleoffer_price_date, moongcleoffer_price_basic, moongcleoffer_price_sale, moongcleoffer_discount_rate, created_at, updated_at)
				VALUES " . implode(", ", $values) . "
				ON DUPLICATE KEY UPDATE 
					moongcleoffer_price_basic = VALUES(moongcleoffer_price_basic), 
					moongcleoffer_price_sale = VALUES(moongcleoffer_price_sale), 
					moongcleoffer_discount_rate = VALUES(moongcleoffer_discount_rate),
					updated_at = VALUES(updated_at)
			";

			Database::getInstance()->getConnection()->statement($sql);
		}

		SanhaResponseService::AvailabilityAndRatesSuccess(false);
	}

	public static function processCall($rawPostData)
	{
		if ($rawPostData === false || empty($rawPostData)) {
			SanhaResponseService::noData();
			exit;
		}

		// $parsedData = '';
		// parse_str($rawPostData, $parsedData);

		$xml = simplexml_load_string($rawPostData);

		if ($xml === false) {
			SanhaResponseService::noData();
			exit;
		}

		$request = [
			$xml->getName() => xmlToArray($xml)
		];

		if (empty($request)) {
			SanhaResponseService::warningW101(true);
		}

		if ($request['OTAProcessCall_RQ']['@attributes']['ChannelID'] != $_ENV['SANHA_CHANNEL_ID']) {
			SanhaResponseService::errorE102(true);
		}

		$partnerIdx = $request['OTAProcessCall_RQ']['@attributes']['OTAHotelID'];
		$partner = Partner::find($partnerIdx);

		if ($partner->partner_thirdparty !== 'sanha') {
			SanhaResponseService::errorE102(true);
		}

		if (array_key_exists('RoomTypeList', $request['OTAProcessCall_RQ'])) {
			$rooms = Room::where('partner_idx', $partner->partner_idx)->get();

			$dom = new \DOMDocument('1.0', 'UTF-8');
			$dom->formatOutput = true;

			$root = $dom->createElement('OTAProcessCall_RS');
			$root = $dom->appendChild($root);

			$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

			$success = $dom->createElement('Success');

			$roomsDom = $dom->createElement('RoomTypeList');

			foreach ($rooms as $room) {
				$roomDom = $dom->createElement('RoomType');
				$roomDom->setAttribute('RoomTypeCode', $room->room_idx);
				$roomDom->setAttribute('RoomTypeName', $room->room_name);
				$roomDom->setAttribute('MinPersons', 1);
				$roomDom->setAttribute('MaxPersons', $room->room_max_person);

				$active = 'Active';
				if ($room->room_status == 'enabled') {
					$active = 'Active';
				} else {
					$active = 'Inactive';
				}

				$roomDom->setAttribute('ActiveFlag', $active);

				$roomsDom->appendChild($roomDom);
			}

			$success->appendChild($roomsDom);
			$root->appendChild($success);

			$xmlString = $dom->saveXML();

			header('Content-Type: application/xml; charset=UTF-8');
			echo $xmlString;
		} else if (array_key_exists('ServiceList', $request['OTAProcessCall_RQ'])) {
			$dom = new \DOMDocument('1.0', 'UTF-8');
			$dom->formatOutput = true;

			$root = $dom->createElement('OTAProcessCall_RS');
			$root = $dom->appendChild($root);

			$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

			$success = $dom->createElement('Success');
			$services = $dom->createElement('ServiceList');
			$success->appendChild($services);

			// $warning = $dom->createElement('Warning');
			// $warning->setAttribute('Code', 'W101');

			// $message = $dom->createElement('Message');
			// $messageText = $dom->createTextNode('처리할 데이터가 없습니다.');
			// $message->appendChild($messageText);

			// $warning->appendChild($message);
			// $root->appendChild($warning);

			$root->appendChild($success);

			$xmlString = $dom->saveXML();

			header('Content-Type: application/xml; charset=UTF-8');
			echo $xmlString;
		} else if (array_key_exists('RateTypeAndPromotionList', $request['OTAProcessCall_RQ'])) {
			$rateplans = Rateplan::where('partner_idx', $partner->partner_idx)->get();

			$dom = new \DOMDocument('1.0', 'UTF-8');
			$dom->formatOutput = true;

			$root = $dom->createElement('OTAProcessCall_RS');
			$root = $dom->appendChild($root);

			$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

			$success = $dom->createElement('Success');
			$rates = $dom->createElement('RateTypeAndPromotionList');

			foreach ($rateplans as $rateplan) {
				$rateDom = $dom->createElement('RateType');
				$rateDom->setAttribute('RateTypeCode', $rateplan->rateplan_idx);
				$rateDom->setAttribute('RateTypeName', $rateplan->rateplan_name);

				if (!empty($rateplan->rateplan_sales_from)) {
					$dateFrom = new \DateTime($rateplan->rateplan_sales_from);
					$rateDom->setAttribute('DateFrom', $dateFrom->format('Y-m-d'));
				} else {
					$rateDom->setAttribute('DateFrom', '1900-01-01');
				}

				if (!empty($rateplan->rateplan_sales_to)) {
					$dateTo = new \DateTime($rateplan->rateplan_sales_to);
					$rateDom->setAttribute('DateTo', $dateTo->format('Y-m-d'));
				} else {
					$rateDom->setAttribute('DateTo', '2099-12-31');
				}

				$active = 'Active';

				$roomTypeMappingsDom = $dom->createElement('RoomTypeMapList');

				if ($rateplan->rateplan_status == 'disabled') {
					$active = 'Inactive';
				}

				$roomRateplans = RoomRateplan::where('rateplan_idx', $rateplan->rateplan_idx)->where('room_rateplan_status', 'enabled')->get();

				foreach ($roomRateplans as $roomRateplan) {
					$roomTypeMappingDom = $dom->createElement('RoomTypeMap');
					$roomTypeMappingDom->setAttribute('RoomTypeCode', $roomRateplan->room_idx);
					$roomTypeMappingsDom->appendChild($roomTypeMappingDom);
				}

				$rateDom->setAttribute('ActiveFlag', $active);

				$rateDom->appendChild($roomTypeMappingsDom);
				$rates->appendChild($rateDom);
			}

			$success->appendChild($rates);

			$root->appendChild($success);

			$xmlString = $dom->saveXML();

			header('Content-Type: application/xml; charset=UTF-8');
			echo $xmlString;
		} else if (array_key_exists('ReservationReSend', $request['OTAProcessCall_RQ'])) {
			if (!empty($request['OTAProcessCall_RQ']['ReservationReSend']['OTAReservationID'])) {
				$paymentItem = PaymentItem::where('reservation_pending_code', $request['OTAProcessCall_RQ']['ReservationReSend']['OTAReservationID'])->first();

				if (!empty($paymentItem)) {
					$sanhaReservationResend = new SanhaReservationResend();
					$sanhaReservationResend->payment_item_idx = $paymentItem->payment_item_idx;
					$sanhaReservationResend->security_key = $request['OTAProcessCall_RQ']['ReservationReSend']['ProcessCallData']['@attributes']['SecurityKey'];
					$sanhaReservationResend->separated_value = $request['OTAProcessCall_RQ']['ReservationReSend']['ProcessCallData']['@attributes']['SeparatedValue'];
					$sanhaReservationResend->result = 'pending';
					$sanhaReservationResend->save();

					$dom = new \DOMDocument('1.0', 'UTF-8');
					$dom->preserveWhiteSpace = false;
					$dom->formatOutput = true;

					$root = $dom->createElement('OTAProcessCall_RS');
					$root = $dom->appendChild($root);

					$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

					$success = $dom->createElement('Success');

					$root->appendChild($success);

					$xmlString = $dom->saveXML();
				} else {
					$dom = new \DOMDocument('1.0', 'UTF-8');

					$root = $dom->createElement('OTAProcessCall_RS');
					$root = $dom->appendChild($root);

					$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

					$warning = $dom->createElement('Warning');
					$warning->setAttribute('Code', 'W101');

					$message = $dom->createElement('Message');
					$messageText = $dom->createTextNode('처리할 데이터가 없습니다.');
					$message->appendChild($messageText);

					$warning->appendChild($message);

					$root->appendChild($warning);

					$xmlString = $dom->saveXML();
				}
			} else if (!empty($request['OTAProcessCall_RQ']['ReservationReSend']['ArrivalDatePeriod'])) {
				$fromDate = $request['OTAProcessCall_RQ']['ReservationReSend']['ArrivalDatePeriod']['@attributes']['ArrivalFrom'];
				$toDate = $request['OTAProcessCall_RQ']['ReservationReSend']['ArrivalDatePeriod']['@attributes']['ArrivalTo'];

				$paymentItems = PaymentItem::where('start_date', $fromDate, '>=')
					->where('end_date', $toDate, '<=')
					->get();

				if (!empty($paymentItems)) {
					$bulkInsert = [];
					$securityKey = $request['OTAProcessCall_RQ']['ReservationReSend']['ProcessCallData']['@attributes']['SecurityKey'];
					$separatedValue = $request['OTAProcessCall_RQ']['ReservationReSend']['ProcessCallData']['@attributes']['SeparatedValue'];

					foreach ($paymentItems as $paymentItem) {
						$bulkInsert[] = [
							'payment_item_idx' => $paymentItem->payment_item_idx,
							'security_key' => $securityKey,
							'separated_value' => $separatedValue,
							'result' => 'pending',
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s')
						];
					}

					if (!empty($bulkInsert)) {
						SanhaReservationResend::insert($bulkInsert);
					}

					$dom = new \DOMDocument('1.0', 'UTF-8');
					$dom->preserveWhiteSpace = false;
					$dom->formatOutput = true;

					$root = $dom->createElement('OTAProcessCall_RS');
					$root = $dom->appendChild($root);

					$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

					$success = $dom->createElement('Success');

					$root->appendChild($success);

					$xmlString = $dom->saveXML();
				} else {
					$dom = new \DOMDocument('1.0', 'UTF-8');

					$root = $dom->createElement('OTAProcessCall_RS');
					$root = $dom->appendChild($root);

					$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

					$warning = $dom->createElement('Warning');
					$warning->setAttribute('Code', 'W101');

					$message = $dom->createElement('Message');
					$messageText = $dom->createTextNode('처리할 데이터가 없습니다.');
					$message->appendChild($messageText);

					$warning->appendChild($message);

					$root->appendChild($warning);

					$xmlString = $dom->saveXML();
				}
			} else if (!empty($request['OTAProcessCall_RQ']['ReservationReSend']['BookLastChangeDateTimePeriod'])) {
				$fromDate = $request['OTAProcessCall_RQ']['ReservationReSend']['BookLastChangeDateTimePeriod']['@attributes']['LstChangeFrom'];
				$toDate = $request['OTAProcessCall_RQ']['ReservationReSend']['BookLastChangeDateTimePeriod']['@attributes']['LstChangeTo'];

				$paymentItems = PaymentItem::where('updated_at', $fromDate, '>=')
					->where('updated_at', $toDate, '<=')
					->get();

				if (!empty($paymentItems)) {
					$bulkInsert = [];
					$securityKey = $request['OTAProcessCall_RQ']['ReservationReSend']['ProcessCallData']['@attributes']['SecurityKey'];
					$separatedValue = $request['OTAProcessCall_RQ']['ReservationReSend']['ProcessCallData']['@attributes']['SeparatedValue'];

					foreach ($paymentItems as $paymentItem) {
						$bulkInsert[] = [
							'payment_item_idx' => $paymentItem->payment_item_idx,
							'security_key' => $securityKey,
							'separated_value' => $separatedValue,
							'result' => 'pending',
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s')
						];
					}

					if (!empty($bulkInsert)) {
						SanhaReservationResend::insert($bulkInsert);
					}

					$dom = new \DOMDocument('1.0', 'UTF-8');
					$dom->preserveWhiteSpace = false;
					$dom->formatOutput = true;

					$root = $dom->createElement('OTAProcessCall_RS');
					$root = $dom->appendChild($root);

					$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

					$success = $dom->createElement('Success');

					$root->appendChild($success);

					$xmlString = $dom->saveXML();
				} else {
					$dom = new \DOMDocument('1.0', 'UTF-8');

					$root = $dom->createElement('OTAProcessCall_RS');
					$root = $dom->appendChild($root);

					$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

					$warning = $dom->createElement('Warning');
					$warning->setAttribute('Code', 'W101');

					$message = $dom->createElement('Message');
					$messageText = $dom->createTextNode('처리할 데이터가 없습니다.');
					$message->appendChild($messageText);

					$warning->appendChild($message);

					$root->appendChild($warning);

					$xmlString = $dom->saveXML();
				}
			}

			header('Content-Type: application/xml; charset=UTF-8');
			echo $xmlString;
		} else if ($request['OTAProcessCall_RQ']['AvailabilityAndRatesList']['@attributes']['AvailRateRequestType'] == 'AvailabilityAndRateType') {
			$dom = new \DOMDocument('1.0', 'UTF-8');
			$dom->formatOutput = true;

			$root = $dom->createElement('OTAProcessCall_RS');
			$root = $dom->appendChild($root);

			$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

			$success = $dom->createElement('Success');
			$informationList = $dom->createElement('AvailRateInformationDataList');

			$rooms = Room::where('partner_idx', $partner->partner_idx)->get();

			foreach ($rooms as $room) {
				$availRateInformationData = $dom->createElement('AvailRateInformationData');

				$sql = "SELECT 
						ri.room_idx,
						ri.inventory_quantity,
						ri.inventory_sold_quantity,
						rp.rateplan_idx,
						rp.room_price_basic,
						rp.is_closed,
						rr.rateplan_stay_min,
						rr.rateplan_stay_max,
						rr.rateplan_cutoff_days,
						rp.room_price_date AS inventory_date
					FROM moongcletrip.room_inventories ri
					JOIN moongcletrip.room_prices rp 
						ON ri.room_idx = rp.room_idx AND ri.inventory_date = rp.room_price_date
					JOIN moongcletrip.rateplans rr 
						ON rp.rateplan_idx = rr.rateplan_idx
					WHERE ri.room_idx = :roomIdx
					AND ri.inventory_date BETWEEN :startDate AND :endDate
					ORDER BY rp.room_price_date
				";

				$bindings = [
					'roomIdx' => $room->room_idx,
					'startDate' => $request['OTAProcessCall_RQ']['AvailabilityAndRatesList']['@attributes']['DateFrom'],
					'endDate' => $request['OTAProcessCall_RQ']['AvailabilityAndRatesList']['@attributes']['DateTo']
				];

				$rawQueryData = Database::getInstance()->getConnection()->select($sql, $bindings);

				$groupedByKey = [];

				foreach ($rawQueryData as $row) {
					$key = implode('|', [
						$row->room_idx,
						$row->rateplan_idx,
						$row->room_price_basic,
						$row->is_closed,
						$row->inventory_quantity,
						$row->inventory_sold_quantity,
						$row->rateplan_stay_min,
						$row->rateplan_stay_max,
						$row->rateplan_cutoff_days
					]);

					$groupedByKey[$key][] = $row;
				}

				foreach ($groupedByKey as $key => $rows) {
					usort($rows, fn ($a, $b) => strcmp($a->inventory_date, $b->inventory_date));
					$ranges = [];

					$currentStart = $rows[0]->inventory_date;
					$currentEnd = $rows[0]->inventory_date;

					for ($i = 1; $i < count($rows); $i++) {
						$prev = new \DateTime($currentEnd);
						$curr = new \DateTime($rows[$i]->inventory_date);

						$diff = $curr->diff($prev)->days;
						if ($diff === 1) {
							$currentEnd = $rows[$i]->inventory_date;
						} else {
							$ranges[] = ['from' => $currentStart, 'to' => $currentEnd];
							$currentStart = $currentEnd = $rows[$i]->inventory_date;
						}
					}
					$ranges[] = ['from' => $currentStart, 'to' => $currentEnd];

					foreach ($ranges as $range) {
						$dateRange = $dom->createElement('DateRange');
						$dateRange->setAttribute('from', $range['from']);
						$dateRange->setAttribute('to', $range['to']);
						$availRateInformationData->appendChild($dateRange);
					}

					$first = $rows[0];

					$rateInformation = $dom->createElement('AvailRateInformation');
					$rateInformation->setAttribute('RoomTypeCode', $first->room_idx);

					$availability = $dom->createElement('Availability');
					$availability->setAttribute('Allotment', $first->inventory_quantity);
					$availability->setAttribute('Closed', $first->Closed ? 'true' : 'false');
					$availability->setAttribute('ReservationCount', $first->inventory_sold_quantity);

					// $restrictions = $dom->createElement('Restrictions');
					// $restrictions->setAttribute('MinLos', $first->rateplan_stay_min);
					// $restrictions->setAttribute('MaxLos', $first->rateplan_stay_max);
					// $restrictions->setAttribute('CutOffDays', $first->rateplan_cutoff_days);

					// $availability->appendChild($restrictions);
					$rateInformation->appendChild($availability);

					$rateTypes = $dom->createElement('RateTypes');
					$rateTypes->setAttribute('RateTypeCode', $first->rateplan_idx);

					$perRate = $dom->createElement('PerRate');
					$perRate->setAttribute('RateAmount', $first->room_price_basic);

					$rateTypes->appendChild($perRate);
					$rateInformation->appendChild($rateTypes);

					$availRateInformationData->appendChild($rateInformation);
				}

				if (!empty($rawQueryData)) {
					$informationList->appendChild($availRateInformationData);
				}
			}

			$success->appendChild($informationList);
			$root->appendChild($success);

			$xmlString = $dom->saveXML();

			header('Content-Type: application/xml; charset=UTF-8');
			echo $xmlString;
		}
	}

	public static function postReservation($item, $bookingType)
	{
		if ($bookingType == 'Book') {
			$result = [
				'status' => 1,
				'text' => '예약에 성공했습니다.'
			];
		} else {
			$result = [
				'status' => 1,
				'text' => '취소에 성공했습니다.'
			];
		}

		$payment = Payment::find($item->payment_idx);

		$dom = new \DOMDocument('1.0', 'UTF-8');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;

		$root = $dom->createElement('CMSReservation_RQ');
		$root = $dom->appendChild($root);

		$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

		$versionInformation = $dom->createElement('VersionInformation');
		$versionInformation->setAttribute('Version', '2.0');

		$date = new \DateTime('now', new \DateTimeZone('Asia/Seoul'));
		$formattedDate = $date->format('Y-m-d\TH:i:sP');
		$versionInformation->setAttribute('TimeStamp', $formattedDate);

		$root->appendChild($versionInformation);

		$requestValue = $dom->createElement('RequestValue');
		$requestValue->setAttribute('ChannelID', $_ENV['SANHA_CHANNEL_ID']);
		$requestValue->setAttribute('OTAHotelID', $item->partner_idx);

		$root->appendChild($requestValue);

		$requestRequirement = $dom->createElement('RequestRequirement');
		$requestRequirement->setAttribute('OTAReservationID', $item->reservation_pending_code);

		$root->appendChild($requestRequirement);

		$resCallerGuestInfo = $dom->createElement('ResCallerGuestInfo');
		// $resCallerGuestInfo->setAttribute('CallerTitle', 'OTAReservationID1');
		$resCallerGuestInfo->setAttribute('CallerLastName', $payment->reservation_name);
		// $resCallerGuestInfo->setAttribute('CallerFirstName', 'OTASourceCode1');
		// $resCallerGuestInfo->setAttribute('CallerSex', 'OTASourceCode1');
		// $resCallerGuestInfo->setAttribute('CallerTelNo', 'OTASourceCode1');
		$resCallerGuestInfo->setAttribute('CallerMobileNo', $payment->reservation_phone);
		$resCallerGuestInfo->setAttribute('CallerEMail', $payment->reservation_email);

		$requestRequirement->appendChild($resCallerGuestInfo);

		$partner = Partner::find($item->partner_idx);

		if ($partner->partner_calculation_type == 'sellingPrice') {
			// 판매가 sellingPrice
			$totalRoomRate = $item->item_sale_price;
		} else {
			// 입금가 payoutPrice
			//$totalRoomRate = round($item->item_sale_price * ((100 - $partner->partner_charge) / 100));
			
			$totalRoomRate = 0;
			foreach ($item->datewise_product_data as $datewiseData) {
				if (!empty($datewiseData['moongcledeal_price'])) {
					$totalRoomRate += round($datewiseData['moongcledeal_price'] * ((100 - $partner->partner_charge) / 100));
				} else {
					$totalRoomRate += round($datewiseData['sale_price'] * ((100 - $partner->partner_charge) / 100));
				}
			}
			error_log('산하 BEFORE TotalRoomRate:'.round($item->item_sale_price * ((100 - $partner->partner_charge) / 100)));
			error_log('산하 AFTER  TotalRoomRate:'.$totalRoomRate);
		}

		$adultCount = 0;
		$childCount = 0;
		$infantCount = 0;

		if (!empty($item->reservation_personnel['adult'])) {
			$adultCount = $item->reservation_personnel['adult'];
		}
		if (!empty($item->reservation_personnel['child'])) {
			$childCount = $item->reservation_personnel['child'];
		}
		if (!empty($item->reservation_personnel['infant'])) {
			$infantCount = $item->reservation_personnel['infant'];
		}

		if ($item->product_type == 'moongcledeal') {
			$product = MoongcleOffer::find($item->product_idx);
		} else {
			$product = RoomRateplan::find($item->product_idx);
		}

		$startDate = new \DateTime($item->start_date);
		$formattedStartDate = $startDate->format('Y-m-d');

		$endDate = new \DateTime($item->end_date);
		$formattedEndDate = $endDate->format('Y-m-d');

		$stayInformation = $dom->createElement('StayInformation');
		$stayInformation->setAttribute('OTAReservationRoomKeyID', $item->payment_item_idx);
		$stayInformation->setAttribute('BookingType', $bookingType);
		$stayInformation->setAttribute('ArrivalDate', $formattedStartDate);
		$stayInformation->setAttribute('DepartureDate', $formattedEndDate);
		$stayInformation->setAttribute('AdultCount', $adultCount);
		$stayInformation->setAttribute('ChildCount', $childCount);
		$stayInformation->setAttribute('BabyCount', $infantCount);
		$stayInformation->setAttribute('RoomTypeCode', $product->room_idx);
		$stayInformation->setAttribute('RoomCount', $item->quantity);
		$stayInformation->setAttribute('CurrencyCode', 'KRW');
        $stayInformation->setAttribute('TotalRoomRate', number_format($totalRoomRate, 0, '.', ''));
		

		if ($partner->partner_calculation_type == 'sellingPrice') {
			$totalPrice = round($item->item_sale_price * ((100 - $partner->partner_charge) / 100));
			$priceText = '[총입금가: ' . number_format($totalPrice, 0, '.', '') . '원]';

			foreach ($item->datewise_product_data as $datewiseData) {
				$dailyCharge = $dom->createElement('DailyCharge');

				if (!empty($datewiseData['moongcledeal_price'])) {
					$payoutDailyPrice = round($datewiseData['moongcledeal_price'] * ((100 - $partner->partner_charge) / 100));
				} else {
					$payoutDailyPrice = round($datewiseData['sale_price'] * ((100 - $partner->partner_charge) / 100));
				}

				$date = new \DateTime($datewiseData['date']);
				$formattedWiseDate = $date->format('Y-m-d');
				$dailyCharge->setAttribute('UseDate', $formattedWiseDate);

				if (!empty($datewiseData['moongcledeal_price'])) {
					$dailyCharge->setAttribute('RoomRate', number_format($datewiseData['moongcledeal_price'] / $item->quantity, 0, '.', ''));
				} else {
					$dailyCharge->setAttribute('RoomRate', number_format($datewiseData['sale_price'] / $item->quantity, 0, '.', ''));
				}

				$priceText .= ' [' . $datewiseData['date'] . ' 입금가: ' . $payoutDailyPrice . '원]';

				$stayInformation->appendChild($dailyCharge);
			}

			if (!empty($item->product_benefits)) {
				$benefitNames = array_column($item->product_benefits, 'benefit_name');
				$priceText .= ' / 특전 : ' . implode(', ', $benefitNames);
			}
		} else {
			$priceText = '[총판매가: ' . number_format($item->item_sale_price, 0, '.', '') . '원]';

			foreach ($item->datewise_product_data as $datewiseData) {
				$dailyCharge = $dom->createElement('DailyCharge');

				if (!empty($datewiseData['moongcledeal_price'])) {
					$payoutDailyPrice = $datewiseData['moongcledeal_price'] * ((100 - $partner->partner_charge) / 100);
				} else {
					$payoutDailyPrice = $datewiseData['sale_price'] * ((100 - $partner->partner_charge) / 100);
				}

				$payoutDailyPrice = $payoutDailyPrice / $item->quantity;

				$date = new \DateTime($datewiseData['date']);
				$formattedWiseDate = $date->format('Y-m-d');
				$dailyCharge->setAttribute('UseDate', $formattedWiseDate);
				$dailyCharge->setAttribute('RoomRate', number_format($payoutDailyPrice, 0, '.', ''));

				if (!empty($datewiseData['moongcledeal_price'])) {
					$priceText .= ' [' . $datewiseData['date'] . ' 판매가: ' . $datewiseData['moongcledeal_price'] . '원]';
				} else {
					$priceText .= ' [' . $datewiseData['date'] . ' 판매가: ' . $datewiseData['sale_price'] . '원]';
				}

				$stayInformation->appendChild($dailyCharge);
			}

			if (!empty($item->product_benefits)) {
				$benefitNames = array_column($item->product_benefits, 'benefit_name');
				$priceText .= ' / 특전 : ' . implode(', ', $benefitNames);
			}
		}

		$inhouseGuestInfo = $dom->createElement('InhouseGuestInfo');
		$inhouseGuestInfo->setAttribute('InhouseLastName', $payment->reservation_name);
		$inhouseGuestInfo->setAttribute('InhouseMobileNo', $payment->reservation_phone);
		$inhouseGuestInfo->setAttribute('InhouseEMail', $payment->reservation_email);
		$inhouseGuestInfo->setAttribute('NationCode', 'KOR');

		$userRequirements = $dom->createElement('UserRequirements');
		$priceTextNode = $dom->createTextNode($priceText);
		$userRequirements->appendChild($priceTextNode);

		$inhouseGuestInfo->appendChild($userRequirements);

		$stayInformation->appendChild($inhouseGuestInfo);

		if (!empty($item->product_benefits)) {
			$additionalInfo = $dom->createElement('AdditionalInfo');
			$specialServiceComments = $dom->createElement('SpecialServiceComments');

			$benefitNames = array_column($item->product_benefits, 'benefit_name');
			$benefitText = '특전 : ' . implode(', ', $benefitNames);

			$benefitTextNode = $dom->createTextNode($benefitText);
			$specialServiceComments->appendChild($benefitTextNode);

			$additionalInfo->appendChild($specialServiceComments);

			$stayInformation->appendChild($additionalInfo);
		}

		$stayInformation->setAttribute('RateTypeCode', $product->rateplan_idx);

		$requestRequirement->appendChild($stayInformation);
		$xmlString = $dom->saveXML();

		$postData = [
			'UserID' => $_ENV['SANHA_LIVE_USERID'],
			'Password' => $_ENV['SANHA_LIVE_PASSWORD'],
			'requestXml' => $xmlString
		];

		$url = $_ENV['SANHA_LIVE_URL'];

		if ($_ENV['SANHA_ENV'] != 'production') {
			$postData = [
				'UserID' => $_ENV['SANHA_TEST_USERID'],
				'Password' => $_ENV['SANHA_TEST_PASSWORD'],
				'requestXml' => $xmlString
			];

			$url = $_ENV['SANHA_TEST_URL'];
		}

		$ch = curl_init();

		$postFields = http_build_query($postData);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($postFields)
		]);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			$result['status'] = -1;
			$result['text'] = 'CMS 연결에 문제가 발생했습니다.';

			$log = SanhaLog::where('payment_item_idx', $item->payment_item_idx)->first();

			if (empty($log)) {
				$log = new SanhaLog();
				$log->payment_item_idx = $item->payment_item_idx;
				$log->action_type = $bookingType;
				$log->pms_code = null;
				$log->response_code = null;
				$log->response_result = 'pending';
				$log->save();
			} else {
				$log->response_result = 'pending';
				$log->save();
			}

			return $result;
		} else {
			$xml = simplexml_load_string($response);

			if ($xml === false) {
				$result['status'] = -2;
				$result['text'] = 'XML 파싱에 실패했습니다.';

				$log = SanhaLog::where('payment_item_idx', $item->payment_item_idx)->first();

				if (empty($log)) {
					$log = new SanhaLog();
					$log->payment_item_idx = $item->payment_item_idx;
					$log->action_type = $bookingType;
					$log->pms_code = null;
					$log->response_code = null;
					$log->response_result = 'pending';
					$log->save();
				} else {
					$log->response_result = 'pending';
					$log->save();
				}

				return $result;
			}

			$request = [
				$xml->getName() => xmlToArray($xml)
			];

			$pmsCode = '';
			$responseCode = '';
			$cmsResult = 'completed';

			if ($bookingType == 'Book') {
				if (!empty($request['CMSReservation_RS']['ReservationResponse'])) {
					if (array_key_exists('Warning', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$responseCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Warning']['@attributes']['Code'];
						$cmsResult = 'fail';

						$result['status'] = $responseCode;
						$result['text'] = '예약에 문제가 발생했습니다.';
					} else if (array_key_exists('Errors', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$responseCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Errors']['@attributes']['Code'];
						$cmsResult = 'fail';

						$result['status'] = $responseCode;
						$result['text'] = '예약에 문제가 발생했습니다.';
					} else if (array_key_exists('Success', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$pmsCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Success']['@attributes']['PMSReservationID'];

						$item->reservation_confirmed_code = $pmsCode;
						$item->save();
					}
				} else {
					$responseCode = null;
					$cmsResult = 'fail';

					$result['status'] = -3;
					$result['text'] = '예약에 문제가 발생했습니다.';
				}
			} else {
				if (!empty($request['CMSReservation_RS']['ReservationResponse'])) {
					if (array_key_exists('Warning', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$responseCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Warning']['@attributes']['Code'];
						$cmsResult = 'fail';

						$result['status'] = $responseCode;
						$result['text'] = '취소에 문제가 발생했습니다.';
					} else if (array_key_exists('Errors', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$responseCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Errors']['@attributes']['Code'];
						$cmsResult = 'fail';

						$result['status'] = $responseCode;
						$result['text'] = '취소에 문제가 발생했습니다.';
					} else if (array_key_exists('Success', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$pmsCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Success']['@attributes']['PMSReservationID'];

						$item->reservation_confirmed_code = $pmsCode;
						$item->save();
					}
				} else {
					$responseCode = null;
					$cmsResult = 'fail';

					$result['status'] = -3;
					$result['text'] = '취소에 문제가 발생했습니다.';
				}
			}

			if (!empty($request['CMSReservation_RS']['Errors'])) {
				$log = new SanhaLog();
				$log->payment_item_idx = $item->payment_item_idx;
				$log->action_type = $bookingType;
				$log->pms_code = $pmsCode;
				$log->response_code = $request['CMSReservation_RS']['Errors']['@attributes']['Code'];
				$log->response_result = $request['CMSReservation_RS']['Errors']['Message'];
				$log->save();
			} else {
				$log = SanhaLog::where('payment_item_idx', $item->payment_item_idx)->first();

				if (empty($log)) {
					$log = new SanhaLog();
					$log->payment_item_idx = $item->payment_item_idx;
					$log->action_type = $bookingType;
					$log->pms_code = $pmsCode;
					$log->response_code = $responseCode;
					$log->response_result = $cmsResult;
					$log->save();
				} else {
					$log->action_type = $bookingType;
					$log->pms_code = $pmsCode;
					$log->response_code = $responseCode;
					$log->response_result = $cmsResult;
					$log->save();
				}
			}
		}

		curl_close($ch);

		return $result;
	}

	public static function resendReservation()
	{
		$resendList = SanhaReservationResend::where('result', 'pending')->get();

		foreach ($resendList as $resend) {
			$item = PaymentItem::find($resend->payment_item_idx);
			$payment = Payment::find($item->payment_idx);

			$dom = new \DOMDocument('1.0', 'UTF-8');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;

			$root = $dom->createElement('CMSReservation_RQ');
			$root = $dom->appendChild($root);

			$root->setAttribute('xmlns', 'http://www.WingsCMS.net');

			$versionInformation = $dom->createElement('VersionInformation');
			$versionInformation->setAttribute('Version', '2.0');

			$date = new \DateTime('now', new \DateTimeZone('Asia/Seoul'));
			$formattedDate = $date->format('Y-m-d\TH:i:sP');
			$versionInformation->setAttribute('TimeStamp', $formattedDate);

			$root->appendChild($versionInformation);

			$requestValue = $dom->createElement('RequestValue');
			$requestValue->setAttribute('ChannelID', $_ENV['SANHA_CHANNEL_ID']);
			$requestValue->setAttribute('OTAHotelID', $item->partner_idx);

			$processCallData = $dom->createElement('ProcessCallData');
			$processCallData->setAttribute('SecurityKey', $resend->security_key);
			$processCallData->setAttribute('SeparatedValue', $resend->separated_value);

			$requestValue->appendChild($processCallData);

			$root->appendChild($requestValue);

			$requestRequirement = $dom->createElement('RequestRequirement');
			$requestRequirement->setAttribute('OTAReservationID', $item->reservation_pending_code);

			$root->appendChild($requestRequirement);

			$resCallerGuestInfo = $dom->createElement('ResCallerGuestInfo');
			$resCallerGuestInfo->setAttribute('CallerLastName', $payment->reservation_name);
			$resCallerGuestInfo->setAttribute('CallerMobileNo', $payment->reservation_phone);
			$resCallerGuestInfo->setAttribute('CallerEMail', $payment->reservation_email);

			$requestRequirement->appendChild($resCallerGuestInfo);

			$partner = Partner::find($item->partner_idx);

			if ($partner->partner_calculation_type == 'sellingPrice') {
				// 판매가 sellingPrice
				$totalRoomRate = $item->item_sale_price;
			} else {
				// 입금가 payoutPrice
				//$totalRoomRate = round($item->item_sale_price * ((100 - $partner->partner_charge) / 100));

				$totalRoomRate = 0;
				foreach ($item->datewise_product_data as $datewiseData) {
					if (!empty($datewiseData['moongcledeal_price'])) {
						$totalRoomRate += round($datewiseData['moongcledeal_price'] * ((100 - $partner->partner_charge) / 100));
					} else {
						$totalRoomRate += round($datewiseData['sale_price'] * ((100 - $partner->partner_charge) / 100));
					}
				}
				error_log('산하 BEFORE TotalRoomRate:'.round($item->item_sale_price * ((100 - $partner->partner_charge) / 100)));
				error_log('산하 AFTER  TotalRoomRate:'.$totalRoomRate);
			}

			$adultCount = 0;
			$childCount = 0;
			$infantCount = 0;

			if (!empty($item->reservation_personnel['adult'])) {
				$adultCount = $item->reservation_personnel['adult'];
			}
			if (!empty($item->reservation_personnel['child'])) {
				$childCount = $item->reservation_personnel['child'];
			}
			if (!empty($item->reservation_personnel['infant'])) {
				$infantCount = $item->reservation_personnel['infant'];
			}

			if ($item->product_type == 'moongcledeal') {
				$product = MoongcleOffer::find($item->product_idx);
			} else {
				$product = RoomRateplan::find($item->product_idx);
			}

			$startDate = new \DateTime($item->start_date);
			$formattedStartDate = $startDate->format('Y-m-d');

			$endDate = new \DateTime($item->end_date);
			$formattedEndDate = $endDate->format('Y-m-d');

			$bookingType = 'Book';

			if ($item->reservation_status != 'confirmed') {
				$bookingType = 'Cancel';
			}

			$stayInformation = $dom->createElement('StayInformation');
			$stayInformation->setAttribute('OTAReservationRoomKeyID', $item->payment_item_idx);
			$stayInformation->setAttribute('BookingType', $bookingType);
			$stayInformation->setAttribute('ArrivalDate', $formattedStartDate);
			$stayInformation->setAttribute('DepartureDate', $formattedEndDate);
			$stayInformation->setAttribute('AdultCount', $adultCount);
			$stayInformation->setAttribute('ChildCount', $childCount);
			$stayInformation->setAttribute('BabyCount', $infantCount);
			$stayInformation->setAttribute('RoomTypeCode', $product->room_idx);
			$stayInformation->setAttribute('RoomCount', $item->quantity);
			$stayInformation->setAttribute('CurrencyCode', 'KRW');
            $stayInformation->setAttribute('TotalRoomRate', number_format($totalRoomRate, 0, '.', ''));
            

			if ($partner->partner_calculation_type == 'sellingPrice') {
				$totalPrice = round($item->item_sale_price * ((100 - $partner->partner_charge) / 100));
				$priceText = '[총입금가: ' . number_format($totalPrice, 0, '.', '') . '원]';

				foreach ($item->datewise_product_data as $datewiseData) {
					$dailyCharge = $dom->createElement('DailyCharge');

					if (!empty($datewiseData['moongcledeal_price'])) {
						$payoutDailyPrice = round($datewiseData['moongcledeal_price'] * ((100 - $partner->partner_charge) / 100));
					} else {
						$payoutDailyPrice = round($datewiseData['sale_price'] * ((100 - $partner->partner_charge) / 100));
					}

					$date = new \DateTime($datewiseData['date']);
					$formattedWiseDate = $date->format('Y-m-d');
					$dailyCharge->setAttribute('UseDate', $formattedWiseDate);

					if (!empty($datewiseData['moongcledeal_price'])) {
						$dailyCharge->setAttribute('RoomRate', number_format($datewiseData['moongcledeal_price'], 0, '.', ''));
					} else {
						$dailyCharge->setAttribute('RoomRate', number_format($datewiseData['sale_price'], 0, '.', ''));
					}

					$priceText .= ' [' . $datewiseData['date'] . ' 입금가: ' . $payoutDailyPrice . '원]';

					$stayInformation->appendChild($dailyCharge);
				}

				if (!empty($item->product_benefits)) {
					$benefitNames = array_column($item->product_benefits, 'benefit_name');
					$priceText .= ' / 특전 : ' . implode(', ', $benefitNames);
				}
			} else {
				$priceText = '[총판매가: ' . number_format($item->item_sale_price, 0, '.', '') . '원]';

				foreach ($item->datewise_product_data as $datewiseData) {
					$dailyCharge = $dom->createElement('DailyCharge');

					if (!empty($datewiseData['moongcledeal_price'])) {
						$payoutDailyPrice = $datewiseData['moongcledeal_price'] * ((100 - $partner->partner_charge) / 100);
					} else {
						$payoutDailyPrice = $datewiseData['sale_price'] * ((100 - $partner->partner_charge) / 100);
					}

					$date = new \DateTime($datewiseData['date']);
					$formattedWiseDate = $date->format('Y-m-d');
					$dailyCharge->setAttribute('UseDate', $formattedWiseDate);
					$dailyCharge->setAttribute('RoomRate', number_format($payoutDailyPrice, 0, '.', ''));

					if (!empty($datewiseData['moongcledeal_price'])) {
						$priceText .= ' [' . $datewiseData['date'] . ' 판매가: ' . $datewiseData['moongcledeal_price'] . '원]';
					} else {
						$priceText .= ' [' . $datewiseData['date'] . ' 판매가: ' . $datewiseData['sale_price'] . '원]';
					}

					$stayInformation->appendChild($dailyCharge);
				}

				if (!empty($item->product_benefits)) {
					$benefitNames = array_column($item->product_benefits, 'benefit_name');
					$priceText .= ' / 특전 : ' . implode(', ', $benefitNames);
				}
			}

			$inhouseGuestInfo = $dom->createElement('InhouseGuestInfo');
			$inhouseGuestInfo->setAttribute('InhouseLastName', $payment->reservation_name);
			$inhouseGuestInfo->setAttribute('InhouseMobileNo', $payment->reservation_phone);
			$inhouseGuestInfo->setAttribute('InhouseEMail', $payment->reservation_email);
			$inhouseGuestInfo->setAttribute('NationCode', 'KOR');

			$userRequirements = $dom->createElement('UserRequirements');
			$priceTextNode = $dom->createTextNode($priceText);
			$userRequirements->appendChild($priceTextNode);

			$inhouseGuestInfo->appendChild($userRequirements);

			$stayInformation->appendChild($inhouseGuestInfo);

			if (!empty($item->product_benefits)) {
				$additionalInfo = $dom->createElement('AdditionalInfo');
				$specialServiceComments = $dom->createElement('SpecialServiceComments');

				$benefitNames = array_column($item->product_benefits, 'benefit_name');
				$benefitText = '특전 : ' . implode(', ', $benefitNames);

				$benefitTextNode = $dom->createTextNode($benefitText);
				$specialServiceComments->appendChild($benefitTextNode);

				$additionalInfo->appendChild($specialServiceComments);

				$stayInformation->appendChild($additionalInfo);
			}

			$stayInformation->setAttribute('RateTypeCode', $product->rateplan_idx);

			$requestRequirement->appendChild($stayInformation);
			$xmlString = $dom->saveXML();

			$postData = [
				'UserID' => $_ENV['SANHA_LIVE_USERID'],
				'Password' => $_ENV['SANHA_LIVE_PASSWORD'],
				'requestXml' => $xmlString
			];

			$url = $_ENV['SANHA_LIVE_URL'];

			if ($_ENV['SANHA_ENV'] != 'production') {
				$postData = [
					'UserID' => $_ENV['SANHA_TEST_USERID'],
					'Password' => $_ENV['SANHA_TEST_PASSWORD'],
					'requestXml' => $xmlString
				];

				$url = $_ENV['SANHA_TEST_URL'];
			}

			$ch = curl_init();

			$postFields = http_build_query($postData);

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 300);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/x-www-form-urlencoded',
				'Content-Length: ' . strlen($postFields)
			]);

			$response = curl_exec($ch);

			if (curl_errno($ch)) {
				$resend->result = 'culrFail';
				$resend->save();

				continue;
			} else {
				$xml = simplexml_load_string($response);

				if ($xml === false) {
					$resend->result = 'xmlFalse';
					$resend->save();

					continue;
				}

				$request = [
					$xml->getName() => xmlToArray($xml)
				];

				$pmsCode = '';
				$responseCode = '';
				$cmsResult = 'completed';

				if (!empty($request['CMSReservation_RS']['Errors'])) {
					$log = new SanhaLog();
					$log->payment_item_idx = $item->payment_item_idx;
					$log->action_type = $bookingType;
					$log->pms_code = '';
					$log->response_code = $request['CMSReservation_RS']['Errors']['@attributes']['Code'];
					$log->response_result = $request['CMSReservation_RS']['Errors']['Message'];
					$log->save();

					$resend->result = 'error';
					$resend->result_code = $request['CMSReservation_RS']['Errors']['@attributes']['Code'];
					$resend->save();
				} else {
					if (array_key_exists('Warning', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$responseCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Warning']['@attributes']['Code'];
						$cmsResult = 'fail';
					} else if (array_key_exists('Errors', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$responseCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Errors']['@attributes']['Code'];
						$cmsResult = 'fail';
					} else if (array_key_exists('Success', $request['CMSReservation_RS']['ReservationResponse']['ReservationResult'])) {
						$pmsCode = $request['CMSReservation_RS']['ReservationResponse']['ReservationResult']['Success']['@attributes']['PMSReservationID'];

						if (!empty($pmsCode)) {
							$item->reservation_confirmed_code = $pmsCode;
							$item->save();
						}
					}

					$resend->result = $cmsResult;
					$resend->result_code = $responseCode;
					$resend->save();
				}
			}

			curl_close($ch);
		}
	}
}
