<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OndaWebhook extends Model
{
    // 테이블 이름
    protected $table = 'onda_webhook';

    // 기본 키
    protected $primaryKey = 'onda_webhook_idx';

    // 타임스탬프 사용 여부
    public $timestamps = true;

    // 대량 할당 가능 속성
    protected $fillable = [
        'event_type',
        'event_detail',
        'event_timestamp',
        'event_progress_status',
    ];

    // JSON 캐스팅 (event_detail 필드가 JSON 형태임을 명시)
    protected $casts = [
        'event_detail' => 'array',
    ];

    // 타임스탬프 캐스팅
    protected $dates = [
        'event_timestamp',
        'created_at',
        'updated_at',
    ];

    /**
     * 진행 상태 스코프: 특정 상태로 필터링
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('event_progress_status', $status);
    }

    /**
     * JSON 데이터에서 특정 키의 값을 가져오는 메서드
     *
     * @param string $key
     * @return mixed
     */
    public function getEventDetail($key)
    {
        return $this->event_detail[$key] ?? null;
    }
}