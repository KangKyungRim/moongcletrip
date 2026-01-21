<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stay extends Model
{
    protected $table = 'stays';
    protected $primaryKey = 'stay_idx';
    public $timestamps = false;  // 커스텀 타임스탬프 사용

    protected $fillable = [
        'stay_checkin_rule',
        'stay_checkout_rule',
        'stay_basic_info',
        'stay_important_info',
        'stay_notice_info',
        'stay_amenity_info',
        'stay_breakfast_info',
        'stay_personnel_info',
        'stay_cancel_info',
        'stay_status',
        'stay_created_at',
        'stay_updated_at',
    ];

    const CREATED_AT = 'stay_created_at';
    const UPDATED_AT = 'stay_updated_at';

    protected $dates = [
        'stay_created_at',
        'stay_updated_at',
    ];

    // Partner와의 1:1 관계 설정
    public function partner()
    {
        return $this->hasOne(Partner::class, 'partner_detail_idx', 'stay_idx')
            ->where('partner_category', 'stay');
    }

    // 임시 숙박 정보와의 관계 설정 (Draft 데이터 참조)
    public function draft()
    {
        return $this->hasOne(StayDraft::class, 'stay_idx', 'stay_idx');
    }

    // 태그 연결을 위한 관계 설정
    public function tags()
    {
        return $this->hasMany(TagConnection::class, 'item_idx', 'stay_idx')
            ->where('item_type', 'stay');
    }

    // 스테이에 연결된 승인된 이미지 조회
    public function approvedImages()
    {
        return Image::where('image_entity_id', $this->stay_idx)
            ->where('image_entity_type', 'stay')
            ->get();
    }

    // 스테이에 연결된 임시 이미지 조회
    public function draftImages()
    {
        return ImageDraft::where('image_entity_id', $this->stay_idx)
            ->where('image_entity_type', 'stay')
            ->get();
    }
}
