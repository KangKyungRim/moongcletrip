<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelRule extends Model
{
    protected $table = 'cancel_rules';
    protected $primaryKey = 'cancel_rule_idx';
    public $timestamps = false;  // 커스텀 타임스탬프 사용

    protected $fillable = [
        'partner_idx',
        'cancel_rules_order',
        'cancel_rules_percent',
        'cancel_rules_day',
        'cancel_rules_time',
        'cancel_rules_created_at',
        'cancel_rules_updated_at',
    ];

    const CREATED_AT = 'cancel_rules_created_at';
    const UPDATED_AT = 'cancel_rules_updated_at';

    protected $dates = [
        'cancel_rules_created_at',
        'cancel_rules_updated_at',
    ];

    // Partner와의 1:N 관계 설정 (취소 규칙은 하나의 파트너에 속함)
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
    }

    // Draft 취소 규칙과의 관계
    public function draft()
    {
        return $this->hasOne(CancelRuleDraft::class, 'cancel_rule_idx', 'cancel_rule_idx');
    }
}
