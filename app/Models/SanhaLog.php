<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanhaLog extends Model
{
    // 테이블 이름 설정
    protected $table = 'sanha_logs';

    // 기본 키 설정
    protected $primaryKey = 'sanha_log_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'payment_idx',
        'payment_item_idx',
        'action_type',
        'pms_code',
        'response_code',
        'response_result',
    ];
}
