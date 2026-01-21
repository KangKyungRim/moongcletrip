<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';
    protected $primaryKey = 'faq_idx';
    public $timestamps = true;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'status',
    ];

    // FAQ와 이미지의 관계 설정
    public function images()
    {
        return $this->hasMany(ContentImage::class, 'entity_id', 'faq_idx')
                    ->where('entity_type', 'faq');
    }
}