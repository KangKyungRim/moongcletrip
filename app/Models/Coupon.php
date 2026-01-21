<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $primaryKey = 'coupon_idx';
    public $timestamps = true;

    protected $fillable = [
        'coupon_name',
        'coupon_code',
        'coupon_type',
        'discount_amount',
        'minimum_order_price',
        'total_issuance',
        'download_count',
        'used_count',
        'is_active',
        'show_in_coupon_wallet',
        'partner_idx',
        'start_date',
        'end_date',
    ];

    // 쿠폰이 적용되는 파트너와의 관계 설정
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
    }

    // 쿠폰을 보유한 유저와의 관계 설정
    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user', 'coupon_idx', 'user_idx')
                    ->withPivot('is_used', 'used_at');
    }
}