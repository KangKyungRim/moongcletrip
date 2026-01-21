<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanhaReservationResend extends Model
{
    // 테이블 이름 설정
    protected $table = 'sanha_reservation_resend';

    // 기본 키 설정
    protected $primaryKey = 'resend_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'payment_idx',
        'payment_item_idx',
        'security_key',
        'separated_value',
        'result',
        'result_code'
    ];
}
