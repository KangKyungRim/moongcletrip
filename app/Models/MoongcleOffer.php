<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongcleOffer extends Model
{
    // 테이블 이름 설정
    protected $table = 'moongcleoffers';

    // 기본 키 설정
    protected $primaryKey = 'moongcleoffer_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'stay_moongcleoffer_idx',
        'partner_idx',
        'room_idx',
        'rateplan_idx',
        'base_product_idx',
        'moongcleoffer_category',
        'moongcle_point_idx',
        'moongcleoffer_status',
        'minimum_discount',
        'moongcleoffer_attractive'
    ];

    // 상품을 찾는 로직 (예시)
    public function getProduct()
    {
        switch ($this->moongcleoffers_category) {
            case 'rateplan':
                return RatePlan::find($this->base_product_idx);
                // case 'ticket':
                //     return Ticket::find($this->base_product_idx);
                // case 'air':
                //     return Air::find($this->base_product_idx);
            default:
                return null;
        }
    }

    // MoongcleOfferPrice와의 1:N 관계
    public function prices()
    {
        return $this->hasMany(MoongcleOfferPrice::class, 'moongcleoffer_idx', 'moongcleoffer_idx');
    }

    // 태그 연결을 위한 관계 설정
    public function tags()
    {
        return $this->hasMany(TagConnection::class, 'item_idx', 'moongcleoffer_idx')
            ->where('item_type', 'moongcleoffer');
    }

    // 혜택과의 다대다 관계
    public function benefits()
    {
        return $this->belongsToMany(Benefit::class, 'moongcleoffer_benefit', 'moongcleoffer_idx', 'benefit_idx')
            ->withTimestamps();
    }

    // MoongcleBundle와의 다대다 관계 설정
    public function bundles()
    {
        return $this->belongsToMany(MoongcleBundle::class, 'moongcleoffer_bundle', 'moongcleoffer_idx', 'moongclebundle_idx')
            ->withTimestamps();
    }

    // MoongcleMatch와의 1:N 관계 설정
    public function matches()
    {
        return $this->hasMany(MoongcleMatch::class, 'product_idx', 'moongcleoffer_idx')
            ->where('moongcle_match_category', 'moongcleoffer');
    }

    // Moongcle Points와의 1:1 관계
    public function moongclePoint()
    {
        return $this->belongsTo(MoongclePoint::class, 'moongcle_point_idx', 'moongcle_point_idx');
    }
}
