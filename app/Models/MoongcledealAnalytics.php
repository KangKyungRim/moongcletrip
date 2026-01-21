<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongcledealAnalytics extends Model
{
    protected $table = 'moongcledeal_analytics';
    protected $primaryKey = 'moongcledeal_analytic_idx';
    public $timestamps = true;

    protected $fillable = [
        'month_key',
        'personnel',
        'companion_tag',
        'pet_size_tag',
        'pet_weight_tag',
        'pet_count_tag',
        'city_tag',
        'taste_tags',
        'search_json',
    ];

    protected $casts = [
        'search_json' => 'array'
    ];
}
