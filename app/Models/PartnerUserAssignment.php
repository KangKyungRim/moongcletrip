<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerUserAssignment extends Model
{
    protected $table = 'partner_user_assignment';
    public $timestamps = false;

    protected $fillable = [
        'partner_idx',
        'partner_user_idx',
        'is_manager'
    ];
}