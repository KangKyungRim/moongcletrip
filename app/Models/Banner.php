<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 배너 엔티티
 */
class Banner extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'banner_idx';  // 기본 키 설정
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'banner_link_type',
        'banner_type',
        'banner_link_url',
        'banner_image_path',
        'is_active',
        'banner_order'
    ];

    // 배너 작업자
    // banners : partner_User
    // 1:1 관계 설정
    public function partnerUser()
    {
        return $this->belongsTo(PartnerUser::class, 'partner_user_idx', 'partner_user_idx');
    }

    
}