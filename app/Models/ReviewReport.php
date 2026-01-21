<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReport extends Model
{
    protected $table = 'review_reports';
    protected $primaryKey = 'report_idx';
    public $timestamps = true;

    protected $fillable = [
        'review_idx',
        'partner_idx',
        'report_reason',
        'status',
    ];

    // 리뷰와의 관계
    public function review()
    {
        return $this->belongsTo(Review::class, 'review_idx', 'review_idx');
    }

    // 파트너와의 관계
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
    }
}