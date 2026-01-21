<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $primaryKey = 'article_idx';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'article_body',
        'article_thumbnail_path',
        'article_button_name',
        'article_button_link',
        'status',
        'published_at',
    ];

    // 아티클과 이미지의 관계 설정
    public function images()
    {
        return $this->hasMany(ContentImage::class, 'entity_id', 'article_idx')
            ->where('entity_type', 'article');
    }
}
