<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewTag extends Model
{
    protected $table = 'review_tags';
    protected $primaryKey = 'review_tag_idx';  // 기본 키 설정
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'tag_idx',
        'tag_name',
        'tag_machine_name',
        'review_idx',
        'tag_order',
    ];

    // 태그와의 관계 설정
    public function tag()
    {
        return $this->belongsTo(MoongcleTag::class, 'tag_idx', 'tag_idx');
    }

    // 연결된 모델의 데이터에 접근 (로직으로 처리)
    public function getItem()
    {
        switch ($this->item_type) {
            case 'moongcleoffer':
                return MoongcleOffer::find($this->item_idx);
            case 'moongclebundle':
                return MoongcleBundle::find($this->item_idx);
            default:
                return null;
        }
    }
}
