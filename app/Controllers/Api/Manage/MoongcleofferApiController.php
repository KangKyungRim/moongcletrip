<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\StayMoongcleOffer;
use App\Models\Benefit;
use App\Models\BenefitItem;
use App\Models\RoomPrice;
use App\Models\MoongcleOffer;
use App\Models\MoongcleOfferPrice;
use App\Models\CuratedTag;
use App\Models\MoongcleTag;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;
use Database;
use RedisManager;

class MoongcleofferApiController
{
	public static function createMoongcleoffer()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['moongcleoffer'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$moongcleoffer = new StayMoongcleOffer();

			$moongcleoffer->partner_idx = $input['partnerIdx'];
			$moongcleoffer->stay_moongcleoffer_title = $input['moongcleoffer']['title'];
			$moongcleoffer->rateplan_idx = $input['moongcleoffer']['rateplanIdx'];
			$moongcleoffer->sale_start_date = $input['moongcleoffer']['saleStartDate'];
			$moongcleoffer->sale_end_date = $input['moongcleoffer']['saleEndDate'];
			$moongcleoffer->stay_start_date = $input['moongcleoffer']['stayStartDate'];
			$moongcleoffer->stay_end_date = $input['moongcleoffer']['stayEndDate'];
			$moongcleoffer->blackout_dates = $input['moongcleoffer']['blackoutDates'];
			$moongcleoffer->discount = $input['moongcleoffer']['discountRate'];
			$moongcleoffer->benefits = $input['moongcleoffer']['benefits'];
			$moongcleoffer->rooms = $input['moongcleoffer']['rooms'];
			$moongcleoffer->tags = $input['moongcleoffer']['tags'];
			$moongcleoffer->audience = $input['moongcleoffer']['audience'];
			$moongcleoffer->curated_tags = $input['moongcleoffer']['curatedTags'] ?? null;
			if (!empty($input['moongcleoffer']['customTitle'])) {
				$moongcleoffer->custom_message = $input['moongcleoffer']['customTitle'] . ':-:' . $input['moongcleoffer']['customMessage'];
			} else {
				$moongcleoffer->custom_message = null;
			}
			$moongcleoffer->attractive = $input['moongcleoffer']['attractive'] ?? MoongcleofferApiController::calculateOfferScore($moongcleoffer);
			$moongcleoffer->stay_moongcleoffer_status = 'enabled';
			$moongcleoffer->save();

			MoongcleofferApiController::saveBenefits($input['moongcleoffer']['rateplanIdx'], $input['moongcleoffer']['benefits']);
			MoongcleofferApiController::saveInventory($moongcleoffer->stay_moongcleoffer_idx, $input['partnerIdx'], $input['moongcleoffer']['rateplanIdx'], $input, $moongcleoffer->stay_moongcleoffer_status, $moongcleoffer->curated_tags, $moongcleoffer->attractive);

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '뭉클오퍼를 저장했습니다.',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '뭉클오퍼 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function editMoongcleoffer()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['moongcleoffer']) || empty($input['moongcleoffer']['stayMoongcleofferIdx'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			//뭉클딜 스테이 저장
			$moongcleoffer = StayMoongcleOffer::find($input['moongcleoffer']['stayMoongcleofferIdx']);
			$moongcleoffer->stay_moongcleoffer_title = $input['moongcleoffer']['title'];
			$moongcleoffer->rateplan_idx = $input['moongcleoffer']['rateplanIdx'];
			$moongcleoffer->sale_start_date = $input['moongcleoffer']['saleStartDate'];
			$moongcleoffer->sale_end_date = $input['moongcleoffer']['saleEndDate'];
			$moongcleoffer->stay_start_date = $input['moongcleoffer']['stayStartDate'];
			$moongcleoffer->stay_end_date = $input['moongcleoffer']['stayEndDate'];
			$moongcleoffer->blackout_dates = $input['moongcleoffer']['blackoutDates'];
			$moongcleoffer->discount = $input['moongcleoffer']['discountRate'];
			$moongcleoffer->benefits = $input['moongcleoffer']['benefits'];
			$moongcleoffer->rooms = $input['moongcleoffer']['rooms'];
			$moongcleoffer->tags = $input['moongcleoffer']['tags'];
			$moongcleoffer->audience = $input['moongcleoffer']['audience'];

			if (!empty($input['operate'])) {
				$moongcleoffer->curated_tags = $input['moongcleoffer']['curatedTags'] ?? null;
				$moongcleoffer->attractive = $input['moongcleoffer']['attractive'] ?? MoongcleofferApiController::calculateOfferScore($moongcleoffer);
			} else {
				$moongcleoffer->attractive = MoongcleofferApiController::calculateOfferScore($moongcleoffer);
			}

			if (!empty($input['moongcleoffer']['customTitle'])) {
				$moongcleoffer->custom_message = $input['moongcleoffer']['customTitle'] . ':-:' . $input['moongcleoffer']['customMessage'];
			} else {
				$moongcleoffer->custom_message = null;
			}
			$moongcleoffer->stay_moongcleoffer_status = 'enabled';
			$moongcleoffer->save();

			MoongcleofferApiController::saveBenefits($input['moongcleoffer']['rateplanIdx'], $input['moongcleoffer']['benefits']);
			MoongcleofferApiController::saveInventory($moongcleoffer->stay_moongcleoffer_idx, $input['partnerIdx'], $input['moongcleoffer']['rateplanIdx'], $input, $moongcleoffer->stay_moongcleoffer_status, $moongcleoffer->curated_tags, $moongcleoffer->attractive);

			Capsule::commit();

			$redis = RedisManager::getInstance();
			$redis->del('moongcledeal:recommend:all');

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '뭉클오퍼를 저장했습니다.',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '뭉클오퍼 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function editMoongcleofferStatus()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['moongcleoffer']) || empty($input['moongcleoffer']['stayMoongcleofferIdx'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$stayMoongcleoffer = StayMoongcleOffer::find($input['moongcleoffer']['stayMoongcleofferIdx']);
			$stayMoongcleoffer->stay_moongcleoffer_status = $input['moongcleoffer']['status'];
			$stayMoongcleoffer->save();

			$moongcleoffers = MoongcleOffer::where('stay_moongcleoffer_idx', $stayMoongcleoffer->stay_moongcleoffer_idx)
				->where('moongcleoffer_status', '!=', 'deleted')
				->get();

			foreach ($moongcleoffers as $moongcleoffer) {
				$moongcleoffer->moongcleoffer_status = $input['moongcleoffer']['status'];

				$moongcleoffer->save();
			}

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '뭉클오퍼를 저장했습니다.',
				'data' => []
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '뭉클오퍼 저장에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * 포함 혜택 저장
	 */
	public static function saveBenefits($rateplanIdx, $benefitNames)
	{
		if (!is_array($benefitNames)) {
			$benefitNames = json_decode($benefitNames, true);
		}

		if (!empty($benefitNames)) {
			$existingBenefits = BenefitItem::where('item_idx', $rateplanIdx)
				->where('item_type', 'rateplan')
				->get();

			foreach ($existingBenefits as $existingBenefit) {
				if (!in_array($existingBenefit->benefit_name, $benefitNames)) {
					$existingBenefit->delete();
				}
			}

			// 새롭게 들어온 베네핏 처리
			foreach ($benefitNames as $benefit) {
				$benefitData = Benefit::where('benefit_name', $benefit)->first();

				if (empty($benefitData)) {
					$benefitData = new Benefit();
					$benefitData->benefit_name = $benefit;
					$benefitData->benefit_created_at = Carbon::now();
					$benefitData->benefit_updated_at = Carbon::now();
					$benefitData->save();
				}

				$existingBenefit = BenefitItem::where('benefit_idx', $benefitData->benefit_idx)
					->where('item_idx', $rateplanIdx)
					->where('item_type', 'rateplan')
					->first();

				if (empty($existingBenefit)) {
					BenefitItem::create([
						'benefit_idx' => $benefitData->benefit_idx,
						'benefit_name' => $benefitData->benefit_name,
						'item_idx' => $rateplanIdx,
						'item_type' => 'rateplan',
					]);
				}
			}
		}
	}

	/**
	 * 뭉클오퍼 - 재고 저장
	 */
	public static function saveInventory($stayMoongcleofferIdx, $partnerIdx, $rateplanIdx, $input, $status, $curatedTags, $attractive)
	{
		$stayStart = new \DateTime($input['moongcleoffer']["stayStartDate"]);
		$stayEnd = new \DateTime($input['moongcleoffer']["stayEndDate"]);
		$blackoutDates = array_flip($input['moongcleoffer']["blackoutDates"]);

		$validStayDates = [];
		$validStayDate = true;

		//1. 실제 사용자가 예약 가능한 날짜 확정
		if (!empty($input['moongcleoffer']["stayStartDate"])) {
			while ($stayStart <= $stayEnd) {
				$dateString = $stayStart->format('Y-m-d');
				if (!isset($blackoutDates[$dateString])) {
					$validStayDates[] = $dateString;
				}
				$stayStart->modify('+1 day');
			}
		} else {
			$validStayDate = false;
		}

		$moongcleoffers = MoongcleOffer::where('partner_idx', $partnerIdx)
			->where('stay_moongcleoffer_idx', $stayMoongcleofferIdx)
			->get();

		//2. 제외된 객실 처리 ( 체크 해제되어 빠진 객실 deleted )
		foreach ($moongcleoffers as $moongcleoffer) {
			if (!in_array($moongcleoffer->room_idx, $input['moongcleoffer']['rooms'])) {
				$moongcleoffer->moongcleoffer_status = 'deleted';
				$moongcleoffer->save();
			}
		}

		//3. 뭉클오퍼 적용 및 가격 생성/수정
		foreach ($input['moongcleoffer']['rooms'] as $roomIdx) {
			$moongcleoffer = null;
			$discount = $input['moongcleoffer']['discountRate'];

			$roomPrices = RoomPrice::where('room_idx', $roomIdx)
				->where('rateplan_idx', $rateplanIdx)
				->get();

			if (empty($roomPrices[0])) {
				continue;
			}

			//3-1. moongcleoffers 정보 생성/업데이트
			$moongcleoffer = MoongcleOffer::where('partner_idx', $partnerIdx)
				->where('stay_moongcleoffer_idx', $stayMoongcleofferIdx)
				->where('base_product_idx', $roomPrices[0]->room_rateplan_idx)
				->where('moongcleoffer_category', 'roomRateplan')
				->first();

			if (empty($moongcleoffer)) {
				$moongcleoffer = new MoongcleOffer();
				$moongcleoffer->stay_moongcleoffer_idx = $stayMoongcleofferIdx;
				$moongcleoffer->partner_idx = $partnerIdx;
				$moongcleoffer->room_idx = $roomIdx;
				$moongcleoffer->rateplan_idx = $rateplanIdx;
				$moongcleoffer->base_product_idx = $roomPrices[0]->room_rateplan_idx;
				$moongcleoffer->moongcleoffer_category = 'roomRateplan';
				$moongcleoffer->moongcleoffer_status = $status;
				$moongcleoffer->minimum_discount = $discount;
				$moongcleoffer->moongcleoffer_attractive = $attractive ?? 0;
				$moongcleoffer->save();
			} else {
				$moongcleoffer->room_idx = $roomIdx;
				$moongcleoffer->rateplan_idx = $rateplanIdx;
				$moongcleoffer->minimum_discount = $discount;
				$moongcleoffer->moongcleoffer_attractive = $attractive ?? 0;
				$moongcleoffer->moongcleoffer_status = 'enabled';
				$moongcleoffer->save();
			}

			//3-2. moongcleoffer_prices 가격 계산 및 일괄 저장
			if (!empty($roomPrices)) {
				$values = [];

				foreach ($roomPrices as $roomPrice) {
					//할인가 계산
					$offerPriceSale = ceil($roomPrice->room_price_basic * ((100 - $discount) / 100));
					//판매 가능 여부 판단
					$isClosed = 0;
					if ($validStayDate) {
						$isClosed = in_array($roomPrice->room_price_date->format('Y-m-d'), $validStayDates) ? 0 : 1;
					}

					$row = [
						$moongcleoffer->moongcleoffer_idx,
						$roomIdx,
						$rateplanIdx,
						$roomPrice->room_rateplan_idx,
						'roomRateplan',
						$roomPrice->room_price_date->format('Y-m-d'),
						$roomPrice->room_price_basic,
						$offerPriceSale,
						$discount,
						$isClosed,
						date('Y-m-d H:i:s'),
						date('Y-m-d H:i:s')
					];

					$escapedRow = array_map(function ($val) {
						if (is_null($val)) return "NULL";
						if (!is_numeric($val)) return "'" . addslashes($val) . "'";
						return $val;
					}, $row);

					$values[] = "(" . implode(", ", $escapedRow) . ")";
				}

				$sql = "
					INSERT INTO moongcleoffer_prices 
					(moongcleoffer_idx, room_idx, rateplan_idx, room_rateplan_idx, base_type, moongcleoffer_price_date, moongcleoffer_price_basic, moongcleoffer_price_sale, moongcleoffer_discount_rate, is_closed, created_at, updated_at) 
					VALUES " . implode(", ", $values) . " 
					ON DUPLICATE KEY UPDATE 
						moongcleoffer_price_basic = VALUES(moongcleoffer_price_basic), 
						moongcleoffer_price_sale = VALUES(moongcleoffer_price_sale), 
						moongcleoffer_discount_rate = VALUES(moongcleoffer_discount_rate),
						is_closed = VALUES(is_closed),
						updated_at = VALUES(updated_at)
				";

				Database::getInstance()->getConnection()->statement($sql);
			}

			//4. 큐레이션 태그 정리
			if (!empty($curatedTags) && !empty($moongcleoffer)) {
				// 현재 연결된 태그를 가져옴
				$existingCuratedTags = CuratedTag::where('item_idx', $moongcleoffer->moongcleoffer_idx)
					->where('item_type', 'moongcleoffer')
					->get();

				// 삭제 로직: 새로 넣는 데이터에 없는 태그를 삭제
				foreach ($existingCuratedTags as $existingTag) {
					if (!in_array($existingTag->tag_machine_name, $curatedTags)) {
						$existingTag->delete();
					}
				}

				// 새 태그를 추가하는 로직
				foreach ($curatedTags as $tag) {
					$tagExist = MoongcleTag::where('tag_machine_name', $tag)->first();

					if ($tagExist) {
						$curatedTag = CuratedTag::where('tag_idx', $tagExist->tag_idx)
							->where('item_idx', $moongcleoffer->moongcleoffer_idx)
							->where('item_type', 'moongcleoffer')
							->first();

						if (!empty($curatedTag)) {
							continue;
						} else {
							$curatedTag = new CuratedTag();
							$curatedTag->tag_idx = $tagExist->tag_idx;
							$curatedTag->tag_name = $tagExist->tag_name;
							$curatedTag->tag_machine_name = $tagExist->tag_machine_name;
							$curatedTag->item_idx = $moongcleoffer->moongcleoffer_idx;
							$curatedTag->item_type = 'moongcleoffer';
							$curatedTag->save();
						}
					}
				}
			}
		}
	}

	/**
	 * 매력도 계산
	 */
	public static function calculateOfferScore($moongcleoffer)
	{
		$discountScore = min($moongcleoffer->discount / 100, 1.0) * 30;

		if (is_array($moongcleoffer->benefits)) {
			$benefitsArray = $moongcleoffer->benefits;
		} else {
			$benefitsArray = json_decode($moongcleoffer->benefits, true);
		}

		$benefitsCount = is_array($benefitsArray) ? count($benefitsArray) : 0;
		$benefitsScore = $benefitsCount * 10;

		if (is_array($moongcleoffer->tags)) {
			$tagsArray = $moongcleoffer->tags;
		} else {
			$tagsArray = json_decode($moongcleoffer->tags, true);
		}

		$tagsCount = is_array($tagsArray) ? count($tagsArray) : 0;
		$tagScore = $tagsCount * 5;

		$saleStartDate = new \DateTime($moongcleoffer->sale_start_date);
		$saleEndDate = new \DateTime($moongcleoffer->sale_end_date);
		$saleDuration = $saleStartDate->diff($saleEndDate)->days;
		$urgencyScore = $saleDuration * 2;

		$conversionScore = 10;

		$totalScore = $discountScore + $benefitsScore + $tagScore + $urgencyScore + $conversionScore;

		return $totalScore;
	}
}
