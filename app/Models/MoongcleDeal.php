<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongcleDeal extends Model
{
    // 테이블 이름 설정
    protected $table = 'moongcledeals';

    // 기본 키 설정
    protected $primaryKey = 'moongcledeal_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'user_idx',
        'priority',
        'selected',
        'status',
        'status_view',
        'moongcledeal_create_complete',
        'represent',
        'analytics'
    ];

    // 날짜 선택 필드의 JSON 타입 처리
    protected $casts = [
        'priority' => 'array',
        'selected' => 'array',
    ];

    // 유저와의 관계 설정 (N:1 관계)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }

    // MoongcleMatch와의 1:N 관계 설정
    public function matches()
    {
        return $this->hasMany(MoongcleMatch::class, 'moongcledeal_idx', 'moongcledeal_idx');
    }
}
