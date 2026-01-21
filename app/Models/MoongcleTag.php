<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongcleTag extends Model
{
    // 테이블 이름 명시 (기본적으로 모델명 복수형, 'tags')
    protected $table = 'moongcle_tags';

    // 기본 키 설정
    protected $primaryKey = 'tag_idx';

    // 자동 타임스탬프 필드를 사용하지 않도록 설정 (커스텀 타임스탬프 사용)
    public $timestamps = false;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'tag_name',
        'tag_machine_name',
        'tag_created_at',
        'tag_updated_at',
    ];

    // 커스텀 타임스탬프 필드 설정
    const CREATED_AT = 'tag_created_at';
    const UPDATED_AT = 'tag_updated_at';

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'tag_created_at',
        'tag_updated_at',
    ];

    // TagConnection과의 1:N 관계
    public function connections()
    {
        return $this->hasMany(TagConnection::class, 'tag_idx', 'tag_idx');
    }

    // 태그와 이미지의 1:1 관계 설정
    public function image()
    {
        return $this->hasOne(TagImage::class, 'tag_idx', 'tag_idx');
    }
}
