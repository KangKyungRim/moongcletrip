<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongcleDealTag extends Model
{
    // 테이블 이름 설정
    protected $table = 'moongcledeal_tag';

    // 기본 키 설정
    protected $primaryKey = 'moongcledeal_tag_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'moongcledeal_idx',
        'tag_idx',
        'tag_order',
        'status'
    ];
}
