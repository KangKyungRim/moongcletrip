<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityImage extends Model
{
    protected $table = 'facility_images';
    protected $primaryKey = 'facility_image_idx';
    public $timestamps = true;

    protected $fillable = [
        'facility_detail_idx',
        'image_origin_name',
        'image_origin_path',
        'image_small_path',
        'image_normal_path',
        'image_big_path',
        'image_origin_size',
        'image_order',
        'image_extension',
    ];
}
