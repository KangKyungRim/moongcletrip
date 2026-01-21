<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    // 테이블 이름 명시 (기본값은 모델명 복수형, 'tokens')
    protected $table = 'tokens';

    // 기본 키 설정
    protected $primaryKey = 'token_idx';

    // 자동 타임스탬프 필드를 사용하지 않도록 설정 (커스텀 필드 사용)
    public $timestamps = false;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'token',
        'user_idx',
        'guest_idx',
        'token_device_type',
        'token_is_active',
        'token_created_at',
        'token_last_used_at',
    ];

    // 커스텀 타임스탬프 필드
    const CREATED_AT = 'token_created_at';
    const UPDATED_AT = 'token_last_used_at';

    // 날짜 필드를 사용할 때 변환할 필드 설정
    protected $dates = [
        'token_created_at',
        'token_last_used_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }
}
