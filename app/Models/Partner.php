<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    // 테이블 이름 설정 (기본값은 모델명 복수형, 'partners')
    protected $table = 'partners';

    // 기본 키 설정
    protected $primaryKey = 'partner_idx';

    // 자동 타임스탬프 필드 사용하지 않음 (커스텀 필드 사용)
    public $timestamps = false;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'partner_detail_idx',
        'partner_sanha_idx',
        'partner_tl_idx',
        'partner_onda_idx',
        'partner_waug_idx',
        'partner_thirdparty',
        'partner_name',
        'partner_category',
        'partner_type',
        'partner_grade',
        'partner_charge',
        'partner_country',
        'partner_zip',
        'partner_origin_address1',
        'partner_origin_address2',
        'partner_origin_address3',
        'partner_address1',
        'partner_address2',
        'partner_address3',
        'partner_city',
        'partner_region',
        'partner_region_detail',
        'partner_latitude',
        'partner_longitude',
        'partner_phonenumber',
        'partner_email',
        'partner_reservation_phonenumber',
        'partner_reservation_email',
        'partner_manager_phonenumber',
        'partner_manager_email',
        'partner_calculation_type',
        'partner_status',
        'partner_search_badge',
        'partner_safe_cancel',
        'average_discount',
        'search_index',
        'image_curated',
        'partner_created_at',
        'partner_updated_at',
    ];

    protected $casts = [
        'partner_search_badge' => 'array',
    ];

    // 커스텀 타임스탬프 필드 설정
    const CREATED_AT = 'partner_created_at';
    const UPDATED_AT = 'partner_updated_at';

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'partner_created_at',
        'partner_updated_at',
    ];

    // Partner와 PartnerUser의 다대다 관계 설정
    public function partnerUsers()
    {
        return $this->belongsToMany(PartnerUser::class, 'partner_user_assignment', 'partner_idx', 'partner_user_idx')
            ->withPivot('is_manager');
    }

    // 한 명의 매니저를 가져오는 관계 (1:1 관계)
    public function manager()
    {
        return $this->partnerUsers()->wherePivot('is_manager', true)->first();
    }

    // 태그 연결을 위한 관계 설정
    public function tags()
    {
        return $this->hasMany(TagConnection::class, 'item_idx', 'partner_idx')
            ->where('item_type', 'partner');
    }

    // 각 partner_category에 따른 로직으로 연결된 데이터 가져오기
    public function partnerDetail()
    {
        switch ($this->partner_category) {
            case 'stay':
                return Stay::find($this->partner_detail_idx);
                // case 'ticket':
                //     return Ticket::find($this->partner_detail_idx);
                // case 'air':
                //     return Air::find($this->partner_detail_idx);
            default:
                return null;
        }
    }

    // CancelRules와의 1:N 관계 설정 (파트너는 여러 취소 규칙을 가질 수 있음)
    public function cancelRules()
    {
        return $this->hasMany(CancelRule::class, 'partner_idx', 'partner_idx');
    }

    // 임시 파트너 정보와의 관계 설정 (Draft 데이터 참조)
    public function draft()
    {
        return $this->hasOne(PartnerDraft::class, 'partner_idx', 'partner_idx');
    }

    // 취소 규칙 Draft와의 관계
    public function cancelRulesDrafts()
    {
        return $this->hasMany(CancelRuleDraft::class, 'partner_idx', 'partner_idx')
            ->where('is_approved', false);
    }

    // 파트너에 연결된 승인된 이미지 조회
    public function approvedImages()
    {
        return Image::where('image_entity_id', $this->partner_idx)
            ->where('image_entity_type', 'partner')
            ->get();
    }

    // 파트너에 연결된 임시 이미지 조회
    public function draftImages()
    {
        return ImageDraft::where('image_entity_id', $this->partner_idx)
            ->where('image_entity_type', 'partner')
            ->get();
    }

    // 파트너를 관심 대상으로 지정한 유저들과의 관계 설정
    public function favorites()
    {
        return $this->hasMany(PartnerFavorite::class, 'partner_idx', 'partner_idx');
    }

    // Stay
    public function rooms()
    {
        return $this->hasMany(Room::class, 'partner_idx', 'partner_idx');
    }
}
