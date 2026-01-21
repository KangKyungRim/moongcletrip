<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerDraft extends Model
{
    protected $table = 'partners_draft';
    protected $primaryKey = 'partner_draft_idx';
    public $timestamps = true;

    protected $fillable = [
        'partner_idx',  // 실제 파트너 테이블의 ID
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
        'partner_search_badge',
        'is_approved',
    ];

    protected $casts = [
        'partner_search_badge' => 'array',
    ];

    // 실제 파트너와의 관계
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
    }
}
