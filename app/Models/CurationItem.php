<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 큐레이션 아이템(파트너:숙소) 엔티티
 */
class CurationItem extends Model
{
    protected $table = 'curation_items';
    protected $primaryKey = 'curation_item_idx';  // 기본 키 설정
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'curation_idx',
        'target_type',
        'target_idx',
        'target_description',
        'target_thumbnail_path',
        'target_tags',
        'curation_item_order',
        'is_active',
        'partner_user_idx',
    ];

    // JSON 캐스팅 (MariaDB는 LONGTEXT지만 사용엔 문제 없음)
    protected $casts = [
        'target_tags' => 'array',
    ];

    //큐레이션 조회
    // curations : curation_items 
    // 1:N 관계설정
    public function curation() {
        //return CurationItem::find($this->curation_idx);
        return $this->belongsTo(CurationItem::class, 'curation_idx', 'curation_idx');
    }

    // 큐레이션 작업자
    // curation_items : partner_User
    // 1:1 관계 설정
    public function partnerUser()
    {
        return $this->belongsTo(PartnerUser::class, 'partner_user_idx', 'partner_user_idx');
    }
    
}