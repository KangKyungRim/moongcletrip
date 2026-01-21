<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    // 테이블 이름 설정
    protected $table = 'access_logs';

    // 기본 키 설정
    protected $primaryKey = 'access_id';

    // 자동 타임스탬프 필드를 사용하지 않도록 설정
    public $timestamps = false;

    // 대량 할당이 가능한 필드 설정
    protected $fillable = [
        'user_idx',
        'token_idx',
        'access_ip',
        'access_device_type',
        'access_path',
        'access_full_path',
        'access_at',
    ];

    const CREATED_AT = 'access_at';

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'access_at',
    ];

    /**
     * 유저와의 관계 설정 (access_logs -> users)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx');
    }

    /**
     * 토큰과의 관계 설정 (access_logs -> tokens)
     */
    public function token()
    {
        return $this->belongsTo(Token::class, 'token_idx');
    }
}
