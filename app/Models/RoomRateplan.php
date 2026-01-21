<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRateplan extends Model
{
	// 테이블 이름 설정
	protected $table = 'room_rateplan';

	// 기본 키 설정
	protected $primaryKey = 'room_rateplan_idx';

	// 자동 타임스탬프 필드 사용
	public $timestamps = true;

	protected $fillable = [
		'partner_idx',
		'room_idx',
		'rateplan_idx',
		'rateplan_sanha_idx',
        'rateplan_tl_idx',
        'rateplan_onda_idx',
        'rateplan_waug_idx',
		'rateplan_thirdparty',
		'room_rateplan_status'
	];

	// Room과의 관계 설정 (N:1)
	public function room()
	{
		return $this->belongsTo(Room::class, 'room_idx', 'room_idx');
	}

	// RatePlan과의 관계 설정 (N:1)
	public function rateplan()
	{
		return $this->belongsTo(RatePlan::class, 'rateplan_idx', 'rateplan_idx');
	}
}
