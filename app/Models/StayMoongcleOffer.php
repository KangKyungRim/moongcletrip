<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StayMoongcleOffer extends Model
{
    // 테이블 이름 설정
    protected $table = 'stay_moongcleoffers';

    // 기본 키 설정
    protected $primaryKey = 'stay_moongcleoffer_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'partner_idx',
        'rateplan_idx',
        'stay_moongcleoffer_title',
        'sale_start_date',
        'sale_end_date',
        'stay_start_date',
        'stay_end_date',
        'blackout_dates',
        'benefits',
        'rooms',
        'tags',
        'curated_tags',
        'audience',
        'custom_message',
        'attractive',
        'stay_moongcleoffer_status',
    ];

    protected $casts = [
        'blackout_dates' => 'array',
        'benefits' => 'array',
        'rooms' => 'array',
        'tags' => 'array',
        'curated_tags' => 'array',
    ];
}
