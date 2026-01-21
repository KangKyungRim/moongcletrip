<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongclePointImage extends Model
{
    protected $table = 'moongcle_point_images';
    protected $primaryKey = 'moongcle_point_image_idx';
    public $timestamps = true;

    protected $fillable = [
        'moongcle_point_idx',
        'image_origin_name',
        'image_origin_path',
        'image_small_path',
        'image_normal_path',
        'image_big_path',
        'image_origin_size',
        'image_order',
        'image_extension'
    ];

    public function moongclePoint()
    {
        return $this->belongsTo(MoongclePoint::class, 'moongcle_point_idx', 'moongcle_point_idx');
    }
}
