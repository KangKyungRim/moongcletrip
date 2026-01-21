<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $table = 'review_images';
    protected $primaryKey = 'review_image_idx';
    public $timestamps = true;

    protected $fillable = [
        'review_idx',
        'review_image_origin_path',
        'review_image_small_path',
        'review_image_normal_path',
        'review_image_big_path',
        'review_image_order',
        'review_image_extension'
    ];

    // 리뷰와의 관계 설정
    public function review()
    {
        return $this->belongsTo(Review::class, 'review_idx', 'review_idx');
    }
}
