<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelRuleDraft extends Model
{
    protected $table = 'cancel_rules_draft';
    protected $primaryKey = 'cancel_rules_draft_idx';
    public $timestamps = true;

    protected $fillable = [
        'cancel_rule_idx',
        'partner_idx',
        'cancel_rules_order',
        'cancel_rules_percent',
        'cancel_rules_day',
        'cancel_rules_time',
        'is_approved',
    ];

    // 파트너와의 관계
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
    }

    // 실제 취소 규칙과의 관계
    public function cancelRule()
    {
        return $this->belongsTo(CancelRule::class, 'cancel_rule_idx', 'cancel_rule_idx');
    }
}
