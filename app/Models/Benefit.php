<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    // 테이블 이름 명시 (기본적으로 모델명 복수형, 'benefits')
    protected $table = 'benefits';

    // 기본 키 설정
    protected $primaryKey = 'benefit_idx';

    // 자동 타임스탬프 필드를 사용하지 않도록 설정 (커스텀 타임스탬프 사용)
    public $timestamps = false;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'benefit_name',
        'benefit_is_upcharge',
        'benefit_upcharge',
        'benefit_recommend',
        'benefit_category',
        'benefit_created_at',
        'benefit_updated_at',
    ];

    // 커스텀 타임스탬프 필드 설정
    const CREATED_AT = 'benefit_created_at';
    const UPDATED_AT = 'benefit_updated_at';

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'benefit_created_at',
        'benefit_updated_at',
    ];

    // 태그 연결을 위한 관계 설정
    public function tags()
    {
        return $this->hasMany(TagConnection::class, 'item_idx', 'benefit_idx')
            ->where('item_type', 'benefit');
    }

    // Moongcleoffer와의 다대다 관계
    public function moongcleOffers()
    {
        return $this->belongsToMany(MoongcleOffer::class, 'moongcleoffer_benefit', 'benefit_idx', 'moongcleoffer_idx')
            ->withTimestamps();
    }
}
