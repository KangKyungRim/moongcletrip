<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerFaq extends Model
{
    protected $table = 'partner_faq';
    protected $primaryKey = 'faq_idx';
    public $timestamps = true;

    protected $fillable = [
        'partner_idx',
        'question',
        'answer',
        'faq_order'
    ];
}
