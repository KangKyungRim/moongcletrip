<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanionAnalytics extends Model
{
    protected $table = 'companion_analytics';
    protected $primaryKey = 'analytics_idx';
    public $timestamps = true;

    protected $fillable = [
        'city_tag',
        'personnel',
        'companion_tag',
        'count',
    ];
}
