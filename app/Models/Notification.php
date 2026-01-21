<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // 테이블 이름 설정
    protected $table = 'notifications';

    // 기본 키 설정
    protected $primaryKey = 'notification_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'user_idx',
        'base_idx',
        'target_idx',
        'notification_type',
        'title',
        'message',
        'link',
        'is_read',
        'push_status'
    ];

    // 유저와의 관계 설정 (N:1 관계)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }
}