<?php

namespace App\Controllers\Manage;

use App\Models\Coupon;

use App\Helpers\PartnerHelper;

class RewardViewController
{
    public static function discountCoupon()
    {
        $data = PartnerHelper::adminDefaultProcess();

        $coupons = Coupon::orderBy('coupon_idx', 'desc')->get();

        $data['coupons'] = $coupons;

        self::render('manage/coupons', ['data' => $data]);
    }

    public static function createDiscountCoupon()
    {
        $data = PartnerHelper::adminDefaultProcess();

        self::render('manage/coupon-create', ['data' => $data]);
    }

    public static function editDiscountCoupon()
    {
        $data = PartnerHelper::adminDefaultProcess();

        if(empty($_GET['coupon_idx'])) {
            header('Location: /manage/reward/discount-coupons');
        }

        $coupon = Coupon::find($_GET['coupon_idx']);

        $data['coupon'] = $coupon;

        self::render('manage/coupon-edit', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
