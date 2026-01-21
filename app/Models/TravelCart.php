<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelCart extends Model
{
    // 테이블 이름 설정
    protected $table = 'travel_carts';

    // 기본 키 설정
    protected $primaryKey = 'travel_cart_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'user_idx',
        'travel_cart_status',
    ];

    // CartItem과의 1:N 관계 설정
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_idx', 'cart_idx');
    }
}