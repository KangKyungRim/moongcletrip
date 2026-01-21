<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentImage extends Model
{
    protected $table = 'content_images';
    protected $primaryKey = 'content_image_idx';
    public $timestamps = true;

    protected $fillable = [
        'entity_id',
        'entity_type',
        'content_image_type',
        'content_image_origin_path',
        'content_image_small_path',
        'content_image_normal_path',
    ];
}