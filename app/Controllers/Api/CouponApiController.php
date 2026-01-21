<?php

namespace App\Controllers\Api;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Models\Coupon;
use App\Models\CouponUser;

use App\Helpers\MiddleHelper;
use App\Helpers\ResponseHelper;

use Carbon\Carbon;

class CouponApiController
{
	public static function downloadCoupon($couponIdx)
	{
		$user = MiddleHelper::checkLoginCookie();
		$isGuest = true;

		if ($user) {
			if ($user->user_is_guest == false) {
				$isGuest = false;
			}
		} else {
			header('Location: /mypage');
		}

		// 쿠폰 정보 가져오기
		$coupon = Coupon::find($couponIdx);

		if (!$coupon) {
			return ResponseHelper::jsonResponse(['error' => '해당 쿠폰을 찾을 수 없습니다.'], 404);
		}

		// 중복 다운로드 방지
		$existingCoupon = CouponUser::where('coupon_idx', $coupon->coupon_idx)
			->where('user_idx', $user->user_idx)
			->first();

		if ($existingCoupon) {
			return ResponseHelper::jsonResponse(['error' => '이미 다운로드한 쿠폰입니다.'], 400);
		}

		// 데이터 저장
		$couponUser = CouponUser::create([
			'coupon_idx' => $coupon->coupon_idx,
			'user_idx' => $user->user_idx,
			'coupon_name' => $coupon->coupon_name,
			'coupon_code' => $coupon->coupon_code,
			'coupon_type' => $coupon->coupon_type,
			'discount_amount' => $coupon->discount_amount,
			'minimum_order_price' => $coupon->minimum_order_price,
			'is_active' => true,
			'start_date' => $coupon->start_date,
			'end_date' => $coupon->end_date,
			'is_used' => false,
		]);

		$coupon->download_count = $coupon->download_count + 1;
		if ($coupon->download_count === $coupon->total_issuance) {
			$coupon->is_active = false;
		}
		$coupon->save();

		return ResponseHelper::jsonResponse([
			'message' => '쿠폰이 성공적으로 다운로드되었습니다.',
			'success' => true,
			'coupon' => $couponUser
		], 200);
	}
}
