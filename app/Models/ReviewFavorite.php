<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewFavorite extends Model
{
    protected $table = 'review_favorites';
    protected $primaryKey = 'favorite_idx';
    public $timestamps = true;

    protected $fillable = [
        'user_idx',
        'review_idx',
    ];

    // 유저와의 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }

    // 리뷰와의 관계 설정
    public function review()
    {
        return $this->belongsTo(Review::class, 'review_idx', 'review_idx');
    }
}