<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyIntegration extends Model
{
    // 테이블 이름 설정 (기본값은 모델명 복수형)
    protected $table = 'thirdparty_integrations';
    protected $primaryKey = 'thirdparty_integration_idx';
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'user_idx',
        'token_idx',
        'system',
        'confirm_code',
        'response_result',
        'response_code',
        'response_body',
    ];

    // User와의 관계 설정 (외래 키: user_idx)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }

    // Token과의 관계 설정 (외래 키: token_idx)
    public function token()
    {
        return $this->belongsTo(Token::class, 'token_idx', 'token_idx');
    }
}