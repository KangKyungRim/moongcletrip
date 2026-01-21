<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagImage extends Model
{
    // 테이블 이름 명시 (기본값은 모델명 복수형이므로 'tag_images')
    protected $table = 'tag_images';

    // 기본 키 설정
    protected $primaryKey = 'tag_image_idx';

    // 자동 타임스탬프 필드 사용하지 않음 (커스텀 필드 사용)
    public $timestamps = false;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'tag_idx',
        'tag_image_origin_path',
        'tag_image_small_path',
        'tag_image_normal_path',
        'tag_image_origin_name',
        'tag_image_created_at',
        'tag_image_updated_at',
    ];

    // 커스텀 타임스탬프 필드
    const CREATED_AT = 'tag_image_created_at';
    const UPDATED_AT = 'tag_image_updated_at';

    // 날짜 필드를 사용할 때 변환할 필드 설정
    protected $dates = [
        'tag_image_created_at',
        'tag_image_updated_at',
    ];

    // Tag와의 관계 설정 (N:1 관계)
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_idx', 'tag_idx');
    }
}
