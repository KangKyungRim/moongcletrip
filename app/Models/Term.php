<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'terms';
    protected $primaryKey = 'term_idx';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'term_body',
        'category',
        'status',
        'published_at',
    ];

    // 약관과 이미지의 관계 설정
    public function images()
    {
        return $this->hasMany(ContentImage::class, 'entity_id', 'term_idx')
                    ->where('entity_type', 'term');
    }
}