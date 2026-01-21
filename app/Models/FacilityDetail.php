<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityDetail extends Model
{
    protected $table = 'facility_detail';
    protected $primaryKey = 'facility_detail_idx';
    public $timestamps = true;

    protected $fillable = [
        'partner_idx',
        'facility_name',
        'facility_sub',
        'facility_description',
        'facility_order',
    ];
}
