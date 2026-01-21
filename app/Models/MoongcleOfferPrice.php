<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongcleOfferPrice extends Model
{
    // 테이블 이름 설정
    protected $table = 'moongcleoffer_prices';

    // 기본 키 설정
    protected $primaryKey = 'moongcleoffer_price_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'moongcleoffer_idx',
        'room_idx',
        'rateplan_idx',
        'room_rateplan_idx',
        'base_idx',
        'base_type',
        'moongcleoffer_price_date',
        'moongcleoffer_price_basic',
        'moongcleoffer_price_sale',
        'moongcleoffer_discount_rate',
        'is_closed'
    ];

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'moongcleoffer_price_date',
    ];

    // MoongcleOffer와의 N:1 관계 설정
    public function moongcleOffer()
    {
        return $this->belongsTo(MoongcleOffer::class, 'moongcleoffer_idx', 'moongcleoffer_idx');
    }
}
