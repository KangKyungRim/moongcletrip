<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    // 테이블 이름 설정
    protected $table = 'room_prices';

    // 기본 키 설정
    protected $primaryKey = 'room_price_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'room_idx',
        'rateplan_idx',
        'room_rateplan_idx',
        'room_price_date',
        'room_price_basic',
        'room_price_sale',
        'room_price_sale_percent',
        'room_price_currency',
        'room_price_promotion_type',
        'room_price_additional_adult',
        'room_price_additional_child',
        'room_price_additional_tiny',
        'room_price_checkin',
        'room_price_checkout',
        'room_price_stay_min',
        'room_price_stay_max',
        'is_closed'
    ];

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'room_price_date',
    ];

    // 객실(Room)과의 관계 설정 (N:1 관계)
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_idx', 'room_idx');
    }

    // 요금제(Rateplan)과의 관계 설정 (N:1 관계)
    public function rateplan()
    {
        return $this->belongsTo(Rateplan::class, 'rate_plan_idx', 'rateplan_idx');
    }
}
