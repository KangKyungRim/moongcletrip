<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityDateAnalytics extends Model
{
    protected $table = 'city_date_analytics';
    protected $primaryKey = 'analytics_idx';
    public $timestamps = true;

    protected $fillable = [
        'month_key',
        'city_tag',
        'count',
    ];
}
