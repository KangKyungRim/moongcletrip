<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 큐레이션 엔티티
 */
class Curation extends Model
{
    protected $table = 'curations';
    protected $primaryKey = 'curation_idx';  // 기본 키 설정
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'curation_title',
        'curation_description',
        'curation_visible_from',
        'curation_visible_to',
        'is_active',
        'curation_order',
        'partner_user_idx',
    ];

    //큐레이션 아이템(파트너:숙소) 조회
    // curations : curation_items 
    // 1:N 관계설정
    public function curationItems()
    {
        return $this->hasMany(CurationItem::class, 'curation_idx', 'curation_idx')
            ->orderBy('curation_item_order', 'asc')   // 정렬
        ;
    }

    // 큐레이션 작업자
    // curations : partner_User
    // 1:1 관계 설정
    public function partnerUser()
    {
        return $this->belongsTo(PartnerUser::class, 'partner_user_idx', 'partner_user_idx');
    }

    
}