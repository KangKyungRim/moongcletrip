<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\Rateplan;
use App\Models\RoomRateplan;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class RateplanApiController
{
	public static function createRateplan()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['rateplanName'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$partner = Partner::find($input['partnerIdx']);

			$rateplan = new Rateplan();
			$rateplan->partner_idx = $input['partnerIdx'];
			$rateplan->rateplan_thirdparty = $partner->partner_thirdparty;
			$rateplan->rateplan_name = $input['rateplanName'];
			$rateplan->rateplan_type = $input['rateplanType'];
			$rateplan->rateplan_description = $input['rateplanDescription'];
			$rateplan->rateplan_stay_min = $input['rateplanStayMin'];
			$rateplan->rateplan_stay_max = $input['rateplanStayMax'];
			$rateplan->rateplan_sales_from = !empty($input['rateplanSalesFrom']) ? $input['rateplanSalesFrom'] : null;
			$rateplan->rateplan_sales_to = !empty($input['rateplanSalesTo']) ? $input['rateplanSalesTo'] : null;
			$rateplan->rateplan_cutoff_days = $input['rateplanCutoffDays'];
			$rateplan->rateplan_is_refundable = $input['rateplanRefundable'];
			$rateplan->rateplan_has_breakfast = $input['rateplanHasBreakfast'];
			$rateplan->rateplan_has_lunch = $input['rateplanHasLunch'];
			$rateplan->rateplan_has_dinner = $input['rateplanHasDinner'];
			$rateplan->rateplan_meal_count = 0;
			$rateplan->rateplan_status = 'enabled';
			$rateplan->rateplan_created_at = Carbon::now();
			$rateplan->rateplan_updated_at = Carbon::now();
			$rateplan->save();

			foreach ($input['rooms'] as $roomIdx => $status) {
				$roomRateplan = new RoomRateplan();
				$roomRateplan->partner_idx = $rateplan->partner_idx;
				$roomRateplan->room_idx = $roomIdx;
				$roomRateplan->rateplan_idx = $rateplan->rateplan_idx;
				$roomRateplan->rateplan_thirdparty = $rateplan->rateplan_thirdparty;
				$roomRateplan->room_rateplan_status = $status;
				$roomRateplan->save();
			}

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '신규 요금제를 생성했습니다.',
				'data' => [
					'partnerIdx' => $partner->partner_idx,
					'roomIdx' => $room->room_idx,
					'rateplanIdx' => $rateplan->rateplan_idx
				]
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '신규 요금제 생성에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * 요금제 수정
	 */
	public static function editRateplan()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['rateplanIdx']) || empty($input['rateplanName'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$rateplan = Rateplan::find($input['rateplanIdx']);
			$rateplan->rateplan_name = $input['rateplanName'];
			$rateplan->rateplan_type = $input['rateplanType'];
			$rateplan->rateplan_description = $input['rateplanDescription'];
			$rateplan->rateplan_stay_min = $input['rateplanStayMin'];
			$rateplan->rateplan_stay_max = $input['rateplanStayMax'];
			$rateplan->rateplan_sales_from = !empty($input['rateplanSalesFrom']) ? $input['rateplanSalesFrom'] : null;
			$rateplan->rateplan_sales_to = !empty($input['rateplanSalesTo']) ? $input['rateplanSalesTo'] : null;
			$rateplan->rateplan_cutoff_days = $input['rateplanCutoffDays'];
			$rateplan->rateplan_is_refundable = $input['rateplanRefundable'];
			$rateplan->rateplan_has_breakfast = $input['rateplanHasBreakfast'];
			$rateplan->rateplan_has_lunch = $input['rateplanHasLunch'];
			$rateplan->rateplan_has_dinner = $input['rateplanHasDinner'];
			$rateplan->rateplan_meal_count = 0;
			$rateplan->rateplan_updated_at = Carbon::now();
			$rateplan->save();

			foreach ($input['rooms'] as $roomIdx => $status) {
				$roomRateplan = RoomRateplan::where('room_idx', $roomIdx)
					->where('rateplan_idx', $rateplan->rateplan_idx)
					->first();

				if (empty($roomRateplan)) {
					$roomRateplan = new RoomRateplan();
					$roomRateplan->partner_idx = $rateplan->partner_idx;
					$roomRateplan->room_idx = $roomIdx;
					$roomRateplan->rateplan_idx = $rateplan->rateplan_idx;
					$roomRateplan->rateplan_thirdparty = $rateplan->rateplan_thirdparty;
				}

				$roomRateplan->room_rateplan_status = $status;
				$roomRateplan->save();
			}

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '요금제를 수정했습니다.',
				'data' => [
					'rateplanIdx' => $rateplan->rateplan_idx
				]
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '요금제 수정에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function rateplanStatusToggle()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['rateplanIdx']) || empty($input['rateplanStatus'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$rateplan = Rateplan::find($input['rateplanIdx']);

			if (empty($rateplan)) {
				return ResponseHelper::jsonResponse([
					'success' => false,
					'message' => '필수 필드가 존재하지 않습니다.',
					'error' => ''
				], 400);
			}

			$rateplan->rateplan_status = $input['rateplanStatus'];
			$rateplan->save();

			RoomRateplan::where('rateplan_idx', $input['rateplanIdx'])
				->update(['room_rateplan_status' => $input['rateplanStatus']]);

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '레이트플랜 상태를 변경했습니다.',
				'data' => [
					'rateplanIdx' => $roomRateplan->rateplan_idx
				]
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '레이트플랜 상태 변경에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public static function roomRateplanStatusToggle()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['rateplanIdx']) || empty($input['roomIdx']) || empty($input['roomRateplanStatus'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		try {
			Capsule::beginTransaction();

			$rateplan = Rateplan::find($input['rateplanIdx']);

			$roomRateplan = RoomRateplan::where('room_idx', $input['roomIdx'])
				->where('rateplan_idx', $input['rateplanIdx'])
				->first();

			if (empty($roomRateplan)) {
				$roomRateplan = new RoomRateplan();
				$roomRateplan->partner_idx = $input['partnerIdx'];
				$roomRateplan->room_idx = $input['roomIdx'];
				$roomRateplan->rateplan_idx = $input['rateplanIdx'];
				$roomRateplan->rateplan_thirdparty = $rateplan->rateplan_thirdparty;
			}

			$roomRateplan->room_rateplan_status = $input['roomRateplanStatus'];
			$roomRateplan->save();

			Capsule::commit();

			return ResponseHelper::jsonResponse([
				'success' => true,
				'message' => '룸-레이트플랜 상태를 변경했습니다.',
				'data' => [
					'rateplanIdx' => $roomRateplan->rateplan_idx
				]
			], 200);
		} catch (\Exception $e) {
			Capsule::rollBack();
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '룸-레이트플랜 상태 변경에 실패했습니다.',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
