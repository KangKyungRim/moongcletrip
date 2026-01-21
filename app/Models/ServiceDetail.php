<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    protected $table = 'service_detail';
    protected $primaryKey = 'service_detail_idx';
    public $timestamps = true;

    protected $fillable = [
        'partner_idx',
        'service_name',
        'service_description',
        'service_order',
    ];
}
