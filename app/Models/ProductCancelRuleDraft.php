<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCancelRuleDraft extends Model
{
    protected $table = 'product_cancel_rules_draft';
    protected $primaryKey = 'product_cancel_rule_draft_idx';
    public $timestamps = true;

    protected $fillable = [
        'product_cancel_rule_idx',
        'new_cancel_rule_order',
        'new_cancel_rule_percent',
        'new_cancel_rule_day',
        'new_cancel_rule_time',
        'is_approved',
    ];

    // 복제된 취소 규정과의 관계
    public function productCancelRule()
    {
        return $this->belongsTo(ProductCancelRule::class, 'product_cancel_rule_idx', 'product_cancel_rule_idx');
    }
}