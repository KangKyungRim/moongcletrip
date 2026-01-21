<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    protected $table = 'review_comments';
    protected $primaryKey = 'comment_idx';
    public $timestamps = true;

    protected $fillable = [
        'review_idx',
        'user_idx',
        'comment_body',
        'parent_comment_idx',
        'status',
    ];

    // 리뷰와의 관계 설정
    public function review()
    {
        return $this->belongsTo(Review::class, 'review_idx', 'review_idx');
    }

    // 유저와의 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }

    // 부모 댓글과의 관계 설정
    public function parentComment()
    {
        return $this->belongsTo(ReviewComment::class, 'parent_comment_idx', 'comment_idx');
    }

    // 대댓글 관계 설정
    public function replies()
    {
        return $this->hasMany(ReviewComment::class, 'parent_comment_idx', 'comment_idx');
    }
}