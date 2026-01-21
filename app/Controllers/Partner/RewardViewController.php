<?php

namespace App\Controllers\Partner;

use App\Models\Coupon;

use App\Helpers\PartnerHelper;

class RewardViewController
{
    public static function discountCoupon()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        $coupons = Coupon::orderBy('coupon_idx', 'desc')->get();

        $data['coupons'] = $coupons;

        self::render('partner/coupons', ['data' => $data]);
    }

    public static function createDiscountCoupon()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        self::render('partner/coupon-create', ['data' => $data]);
    }

    public static function editDiscountCoupon()
    {
        $data = PartnerHelper::partnerDefaultProcess();

        if(empty($_GET['coupon_idx'])) {
            header('Location: /partner/reward/discount-coupons');
        }

        $coupon = Coupon::find($_GET['coupon_idx']);

        $data['coupon'] = $coupon;

        self::render('partner/coupon-edit', ['data' => $data]);
    }

    private static function render($view, $data = [])
    {
        extract($data);
        require "../app/Views/{$view}.php";
    }
}
