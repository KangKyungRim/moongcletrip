<?php

namespace App\Controllers\Api\Manage;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Partner;
use App\Models\PartnerDraft;
use App\Models\Room;
use App\Models\Rateplan;
use App\Models\RoomRateplan;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;

class PartnerConfigApiController
{
	public static function changeThirdparty()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['thirdparty'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		$partner = Partner::find($input['partnerIdx']);
		$partner->partner_thirdparty = $input['thirdparty'];
		$partner->save();

		$partnerDraft = $partner->draft;
		$partnerDraft->partner_thirdparty = $input['thirdparty'];
		$partnerDraft->save();

		Room::where('partner_idx', $partner->partner_idx)
			->update([
				'room_thirdparty' => $input['thirdparty']
			]);

		Rateplan::where('partner_idx', $partner->partner_idx)
			->update([
				'rateplan_thirdparty' => $input['thirdparty']
			]);

		RoomRateplan::where('partner_idx', $partner->partner_idx)
			->update([
				'rateplan_thirdparty' => $input['thirdparty']
			]);

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '서드파티 연동 시스템을 변경했습니다.',
			'data' => [
				'partnerIdx' => $partner->partner_idx
			]
		], 200);
	}

	public static function changeCalculationType()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || empty($input['calculationType'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		$partner = Partner::find($input['partnerIdx']);
		$partner->partner_calculation_type = $input['calculationType'];
		$partner->save();

		$partnerDraft = $partner->draft;
		$partnerDraft->partner_calculation_type = $input['calculationType'];
		$partnerDraft->save();

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '서드파티 연동 시스템을 변경했습니다.',
			'data' => [
				'partnerIdx' => $partner->partner_idx
			]
		], 200);
	}

	public static function changeSafeCancel()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if ($checkUser) {
			if ($checkUser->partner_user_level < 4) {
				header('Location: /manage/login');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if (empty($input['partnerIdx']) || !isset($input['safeCancel'])) {
			return ResponseHelper::jsonResponse([
				'success' => false,
				'message' => '필수 필드가 존재하지 않습니다.',
				'error' => ''
			], 400);
		}

		$partner = Partner::find($input['partnerIdx']);
		$partner->partner_safe_cancel = $input['safeCancel'];
		$partner->save();

		return ResponseHelper::jsonResponse([
			'success' => true,
			'message' => '안심 취소 숙소 설정을 변경했습니다.',
			'data' => [
				'partnerIdx' => $partner->partner_idx
			]
		], 200);
	}
}
