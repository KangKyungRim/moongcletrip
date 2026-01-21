<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewStatistics extends Model
{
    // 테이블 이름 설정
    protected $table = 'review_statistics';

    // 기본 키 설정
    protected $primaryKey = 'stat_id';

    // 자동 타임스탬프 필드 사용
    public $timestamps = false;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'entity_id',
        'entity_type',
        'review_count',
        'average_rating',
        'last_updated'
    ];

    // 날짜 형식으로 변환할 필드
    protected $dates = [
        'last_updated'
    ];

    /**
     * 리뷰 통계를 조회할 엔티티와의 관계 설정
     * entity_type에 따라 다르게 사용할 수 있도록 설정
     */
    public function entity()
    {
        switch ($this->entity_type) {
            case 'stay':
                return Stay::find($this->entity_id);
            // case 'ticket':
            //     return Ticket::find($this->base_product_idx);
            // case 'air':
            //     return Air::find($this->base_product_idx);
            default:
                return null;
        }
    }

    /**
     * 리뷰 개수와 평균 점수를 업데이트하는 메서드 예시
     *
     * @param int $entityId
     * @param string $entityType
     * @param int $reviewCount
     * @param float $averageRating
     */
    public static function updateStatistics($entityId, $entityType, $reviewCount, $averageRating)
    {
        self::updateOrCreate(
            ['entity_id' => $entityId, 'entity_type' => $entityType],
            [
                'review_count' => $reviewCount,
                'average_rating' => $averageRating,
                'last_updated' => now()
            ]
        );
    }
}