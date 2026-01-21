<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewCommentReport extends Model
{
    protected $table = 'review_comment_reports';
    protected $primaryKey = 'comment_report_idx';
    public $timestamps = true;

    protected $fillable = [
        'comment_idx',
        'user_idx',
        'report_reason',
        'status',
    ];

    // 리뷰 댓글과의 관계
    public function comment()
    {
        return $this->belongsTo(ReviewComment::class, 'comment_idx', 'comment_idx');
    }

    // 유저와의 관계
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }
}