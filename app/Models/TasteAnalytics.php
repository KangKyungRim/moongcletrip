<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TasteAnalytics extends Model
{
    protected $table = 'taste_analytics';
    protected $primaryKey = 'analytics_idx';
    public $timestamps = true;

    protected $fillable = [
        'city_tag',
        'taste_tag',
        'count',
    ];
}
