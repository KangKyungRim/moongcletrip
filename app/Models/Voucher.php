<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    // 테이블 이름 설정
    protected $table = 'vouchers';

    // 기본 키 설정
    protected $primaryKey = 'voucher_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'user_idx',
        'item_idx',
        'item_type',
        'item_status',
        'title',
        'message',
        'send_type',
        'status',
    ];

    // 유저와의 관계 설정 (N:1 관계)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }
}