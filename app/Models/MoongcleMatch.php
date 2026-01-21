<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoongcleMatch extends Model
{
	// 테이블 이름 설정
	protected $table = 'moongcle_match';

	// 기본 키 설정
	protected $primaryKey = 'moongcle_match_idx';

	// 자동 타임스탬프 필드 사용
	public $timestamps = true;

	// 대량 할당이 가능한 필드
	protected $fillable = [
		'moongcledeal_idx',
		'product_idx',
		'moongcle_match_category',
		'moongcle_match_is_read',
		'moongcle_match_is_detailed_view',
		'moongcle_match_status',
		'notification_status',
		'notification_time',
		'match_score',
		'processing_at'
	];

	// MoongcleDeal과의 관계 설정 (N:1 관계)
	public function deal()
	{
		return $this->belongsTo(MoongcleDeal::class, 'moongcledeal_idx', 'moongcledeal_idx');
	}

	// 제품과의 관계 설정 (N:1 관계, 카테고리 필드를 사용해 다양한 제품과 연결)
	public function getProduct()
	{
		switch ($this->moongcle_match_category) {
			case 'moongcleoffer':
				return MoongcleOffer::find($this->product_idx);
			case 'moongclebundle':
				return MoongcleBundle::find($this->product_idx);
			default:
				return null;
		}
	}
}
