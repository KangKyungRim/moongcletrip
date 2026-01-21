<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notices';
    protected $primaryKey = 'notice_idx';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'notice_body',
        'status',
        'published_at',
    ];

	// 공지와 이미지의 관계 설정
    public function images()
    {
        return $this->hasMany(ContentImage::class, 'entity_id', 'notice_idx')
                    ->where('entity_type', 'notice');
    }
}