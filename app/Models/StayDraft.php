<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StayDraft extends Model
{
    protected $table = 'stays_draft';
    protected $primaryKey = 'stay_draft_idx';
    public $timestamps = true;

    protected $fillable = [
        'stay_idx',
        'stay_checkin_rule',
        'stay_checkout_rule',
        'stay_basic_info',
        'stay_important_info',
        'stay_notice_info',
        'stay_amenity_info',
        'stay_breakfast_info',
        'stay_personnel_info',
        'stay_cancel_info',
    ];

    // 실제 Stay와의 관계
    public function stay()
    {
        return $this->belongsTo(Stay::class, 'stay_idx', 'stay_idx');
    }
}
