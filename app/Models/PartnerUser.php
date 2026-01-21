<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerUser extends Model
{
    // 테이블 이름 설정 (기본값은 모델명 복수형, 'partner_users')
    protected $table = 'partner_users';

    // 기본 키 설정
    protected $primaryKey = 'partner_user_idx';

    // 자동 타임스탬프 필드 사용하지 않음 (커스텀 필드 사용)
    public $timestamps = false;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'partner_user_level',
        'partner_user_nickname',
        'partner_user_password',
        'partner_user_name',
        'partner_user_email',
        'partner_user_phone_number',
        'partner_user_login_type',
        'partner_user_status',
        'partner_user_image',
        'partner_user_referral_code',
        'partner_user_created_at',
        'partner_user_updated_at',
        'partner_user_last_login_at',
    ];

    // 커스텀 타임스탬프 필드 설정
    const CREATED_AT = 'partner_user_created_at';
    const UPDATED_AT = 'partner_user_updated_at';

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'partner_user_created_at',
        'partner_user_updated_at',
        'partner_user_last_login_at',
    ];

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'partner_user_assignment', 'partner_user_idx', 'partner_idx')
                    ->withPivot('is_manager')
                    ->with('draft');
    }

    // PartnerUser가 매니저로 있는 파트너들
    public function managedPartners()
    {
        return $this->partners()->wherePivot('is_manager', true);
    }
}