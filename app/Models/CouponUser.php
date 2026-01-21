<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    protected $table = 'coupon_user';
    protected $primaryKey = 'coupon_user_idx';
    public $timestamps = true;

    protected $fillable = [
        'coupon_idx',
        'user_idx',
        'coupon_name',
        'coupon_code',
        'coupon_type',
        'discount_amount',
        'minimum_order_price',
        'is_used',
        'is_active',
        'start_date',
        'end_date',
        'used_at',
    ];

    // 쿠폰과의 관계 설정
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_idx', 'coupon_idx');
    }

    // 유저와의 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }
}
