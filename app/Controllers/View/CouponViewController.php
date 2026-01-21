<?php

namespace App\Controllers\View;

use App\Models\Coupon;
use App\Models\CouponUser;

use App\Helpers\MiddleHelper;

use Database;

use Carbon\Carbon;

class CouponViewController
{
    public static function list()
    {
        $deviceType = getDeviceType();

        $user = MiddleHelper::checkLoginCookie();
        $isGuest = true;

        if ($user) {
            if ($user->user_is_guest == false) {
                $isGuest = false;
            }
        }

        $showCouponExist = true;

        $coupons = Coupon::where('is_active', true)
            ->where('show_in_coupon_wallet', true)
            ->get();

        if ($coupons->count() === 0) {
            $coupons = [];
            $showCouponExist = false;
        }

        $myCoupons = [];

        if ($user) {
            $myCouponIdx = CouponUser::where('user_idx', $user->user_idx)->pluck('coupon_idx')->toArray();

            $now = Carbon::now()->toDateTimeString();

            $sql = "
				SELECT *
				FROM coupon_user
				WHERE user_idx = :userIdx
				AND is_active = 1
				AND is_used = 0
				AND (start_date IS NULL OR start_date <= :now1)
				AND (end_date IS NULL OR end_date >= :now2)
			";

            $bindings = [
                'userIdx' => $user->user_idx,
                'now1' => $now,
                'now2' => $now,
            ];

            $myCoupons = Database::getInstance()->getConnection()->select($sql, $bindings);
        }

        // 다운로드 여부 추가
        if (!empty($myCouponIdx) && $showCouponExist) {
            $coupons->transform(function ($coupon) use ($myCouponIdx) {
                $coupon->is_downloaded = in_array($coupon->coupon_idx, $myCouponIdx);
                return $coupon;
            });
        }

        $data = array(
            'deviceType' => $deviceType,
            'user' => $user,
            'isGuest' => $isGuest,
            'coupons' => $coupons,
            'myCoupons' => $myCoupons,
        );

        self::render('coupons', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/app/{$view}.php";
    }
}
