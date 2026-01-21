<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongcleBundle extends Model
{
    // 테이블 이름 설정
    protected $table = 'moongclebundles';

    // 기본 키 설정
    protected $primaryKey = 'moongclebundle_idx';

    // 자동 타임스탬프 필드 사용
    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'partner_idx',
        'user_idx',
        'moongcle_point_idx',  // Moongcle Points 외래 키
        'moongclebundle_status',
    ];

    // 파트너와의 관계 설정 (N:1 관계)
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
    }

    // 유저와의 관계 설정 (N:1 관계)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }

    // MoongcleOffer와의 다대다 관계 설정
    public function offers()
    {
        return $this->belongsToMany(MoongcleOffer::class, 'moongcleoffer_bundle', 'moongclebundle_idx', 'moongcleoffer_idx')
            ->withTimestamps();
    }

    // MoongcleMatch와의 1:N 관계 설정
    public function matches()
    {
        return $this->hasMany(MoongcleMatch::class, 'product_idx', 'moongclebundle_idx')
            ->where('moongcle_match_category', 'moongclebundle');
    }

    // Moongcle Points와의 1:1 관계
    public function moongclePoint()
    {
        return $this->belongsTo(MoongclePoint::class, 'moongcle_point_idx', 'moongcle_point_idx');
    }
}
