<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_idx';
    public $timestamps = true;

    protected $fillable = [
        'travel_cart_idx',
        'user_idx',
        'payment_password',
        'payment_unique_code',
        'payment_total_amount',
        'payment_sale_amount',
        'payment_status',
        'payment_method',
        'payment_name',
        'payment_key',
        'payment_type',
        'payment_order_id',
        'payment_amount',
        'payment_error_code',
        'payment_error_message',
        'reservation_name',
        'reservation_phone',
        'reservation_email',
        'visit_name',
        'visit_phone',
        'visit_email',
        'visit_way',
        'coupon_user_idx',
        'coupon_discount_amount',
        'coupon_name',
        'refund_amount',
        'refund_point_amount'
    ];

    // PaymentItem과의 1:N 관계 설정
    public function items()
    {
        return $this->hasMany(PaymentItem::class, 'payment_idx', 'payment_idx');
    }

    // 결제된 장바구니와의 관계 설정
    public function travelCart()
    {
        return $this->belongsTo(TravelCart::class, 'travel_cart_idx', 'travel_cart_idx');
    }

    // 결제한 유저와의 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }

    // 쿠폰과의 관계 설정
    public function couponUser()
    {
        return $this->belongsTo(CouponUser::class, 'coupon_user_idx', 'coupon_user_idx');
    }
}
