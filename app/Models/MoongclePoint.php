<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongclePoint extends Model
{
    protected $table = 'moongcle_points';
    protected $primaryKey = 'moongcle_point_idx';
    public $timestamps = true;

    protected $fillable = [
        'partner_idx',
        'moongcle_point_introduction',
        'moongcle_point_1_title',
        'moongcle_point_1_description',
        'moongcle_point_1_image',
        'moongcle_point_2_title',
        'moongcle_point_2_description',
        'moongcle_point_2_image',
        'moongcle_point_3_title',
        'moongcle_point_3_description',
        'moongcle_point_3_image',
        'moongcle_point_4_title',
        'moongcle_point_4_description',
        'moongcle_point_4_image',
        'moongcle_point_5_title',
        'moongcle_point_5_description',
        'moongcle_point_5_image',
        'overview_video_url',
        'min_occupancy',
        'max_occupancy',
        'weekday_average_price',
        'weekend_average_price',
        'popular_facilities',
        'nearby_attractions',
    ];
}