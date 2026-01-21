<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Coupon;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class RewardApiController
{
	public static function storeDiscountCoupon()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if (!$checkUser) {
			header('Location: /manage/login');
			exit;
		} else {
			if ($checkUser->partner_user_level < 7) {
				header('Location: /dashboard');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		// POST 데이터를 가져오기
		$couponName = isset($input['couponName']) ? trim($input['couponName']) : null;
		$discountAmount = isset($input['discountAmount']) ? $input['discountAmount'] : null;
		$minimumOrderPrice = isset($input['minimumOrderPrice']) ? $input['minimumOrderPrice'] : null;
		$totalIssuance = isset($input['totalIssuance']) ? (int) $input['totalIssuance'] : null;
		$isActive = isset($input['isActive']) ? (bool) $input['isActive'] : false;
		$showInCouponWallet = isset($input['showInCouponWallet']) ? (bool) $input['showInCouponWallet'] : false;
		$couponStartDate = isset($input['couponStartDate']) ? trim($input['couponStartDate']) : null;
		$couponEndDate = isset($input['couponEndDate']) ? trim($input['couponEndDate']) : null;

		// 필수값 유효성 검사
		if (!$couponName || !$discountAmount || !$minimumOrderPrice || !$totalIssuance) {
			return ResponseHelper::jsonResponse(['success' => false, 'message' => '필수 값이 누락되었습니다.'], 400);
		}

		if ($couponStartDate && $couponEndDate && strtotime($couponEndDate) < strtotime($couponStartDate)) {
			return ResponseHelper::jsonResponse(['success' => false, 'message' => '종료일은 시작일 이후여야 합니다.'], 400);
		}

		// 쿠폰 저장
		$coupon = new Coupon();
		$coupon->coupon_name = $couponName;
		$coupon->coupon_code = generateRandomCode();
		$coupon->coupon_type = 'discount';
		$coupon->discount_amount = $discountAmount;
		$coupon->minimum_order_price = $minimumOrderPrice;
		$coupon->total_issuance = $totalIssuance;
		$coupon->is_active = $isActive;
		$coupon->show_in_coupon_wallet = $showInCouponWallet;
		$coupon->start_date = $couponStartDate;
		$coupon->end_date = $couponEndDate;
		$coupon->save();

		return ResponseHelper::jsonResponse([
			'message' => '신규 쿠폰을 저장했습니다.',
			'success' => true,
			'couponId' => $coupon->id
		], 200);
	}

	public static function editDiscountCoupon()
	{
		$checkUser = MiddleHelper::checkPartnerLoginCookie();

		if (!$checkUser) {
			header('Location: /manage/login');
			exit;
		} else {
			if ($checkUser->partner_user_level < 7) {
				header('Location: /dashboard');
				exit;
			}
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if(empty($input['couponIdx'])) {
			return ResponseHelper::jsonResponse(['success' => false, 'message' => '필수 값이 누락되었습니다.'], 400);
		}

		$coupon = Coupon::find($input['couponIdx']);

		// POST 데이터를 가져오기
		$couponName = isset($input['couponName']) ? trim($input['couponName']) : null;
		$discountAmount = isset($input['discountAmount']) ? $input['discountAmount'] : null;
		$minimumOrderPrice = isset($input['minimumOrderPrice']) ? $input['minimumOrderPrice'] : null;
		$totalIssuance = isset($input['totalIssuance']) ? (int) $input['totalIssuance'] : null;
		$isActive = isset($input['isActive']) ? (bool) $input['isActive'] : false;
		$showInCouponWallet = isset($input['showInCouponWallet']) ? (bool) $input['showInCouponWallet'] : false;
		$couponStartDate = isset($input['couponStartDate']) ? trim($input['couponStartDate']) : null;
		$couponEndDate = isset($input['couponEndDate']) ? trim($input['couponEndDate']) : null;

		// 필수값 유효성 검사
		if (!$couponName || !$discountAmount || !$minimumOrderPrice || !$totalIssuance) {
			return ResponseHelper::jsonResponse(['success' => false, 'message' => '필수 값이 누락되었습니다.'], 400);
		}

		if ($couponStartDate && $couponEndDate && strtotime($couponEndDate) < strtotime($couponStartDate)) {
			return ResponseHelper::jsonResponse(['success' => false, 'message' => '종료일은 시작일 이후여야 합니다.'], 400);
		}

		// 쿠폰 저장
		$coupon->coupon_name = $couponName;
		$coupon->coupon_type = 'discount';
		$coupon->discount_amount = $discountAmount;
		$coupon->minimum_order_price = $minimumOrderPrice;
		$coupon->total_issuance = $totalIssuance;
		$coupon->is_active = $isActive;
		$coupon->show_in_coupon_wallet = $showInCouponWallet;
		$coupon->start_date = $couponStartDate;
		$coupon->end_date = $couponEndDate;
		$coupon->save();

		return ResponseHelper::jsonResponse([
			'message' => '쿠폰을 수정했습니다.',
			'success' => true,
			'couponId' => $coupon->id
		], 200);
	}
}
