<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagConnection extends Model
{
    protected $table = 'tag_connections';
    protected $primaryKey = 'tag_connection_idx';  // 기본 키 설정
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'tag_idx',
        'tag_name',
        'tag_machine_name',
        'item_idx',
        'item_type',
        'connection_type',
        'connection_subtype',
        'editor',
        'is_curated'
    ];
    
    // 태그와의 관계 설정
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_idx', 'tag_idx');
    }

    // 연결된 모델의 데이터에 접근 (로직으로 처리)
    public function getItem()
    {
        switch ($this->item_type) {
            case 'benefit':
                return Benefit::find($this->item_idx);
            case 'rateplan':
                return RatePlan::find($this->item_idx);
            case 'room':
                return Room::find($this->item_idx);
            case 'stay':
                return Stay::find($this->item_idx);
            default:
                return null;
        }
    }
}
