<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomDraft extends Model
{
	// 테이블 이름 설정
	protected $table = 'rooms_draft';

	// 기본 키 설정
	protected $primaryKey = 'rooms_draft_idx';

	public $timestamps = true;

	// 대량 할당이 가능한 필드
	protected $fillable = [
		'room_idx',
		'room_sanha_idx',
		'room_tl_idx',
		'room_onda_idx',
		'room_waug_idx',
		'room_thirdparty',
		'room_order',
		'room_name',
		'room_bed_type',
		'room_details',
		'room_size',
		'room_standard_person',
		'room_max_person',
		'room_adult_additional_price',
		'room_child_additional_price',
		'room_tiny_additional_price',
		'room_child_age',
		'room_tiny_month',
		'room_amenity',
		'room_barrierfree',
		'room_other_notes',
		'room_is_approved',
	];

	protected $casts = [
		'room_bed_type' => 'array',
	];

	// 파트너와의 관계 (하나의 파트너가 여러 개의 객실을 가질 수 있음)
	public function partner()
	{
		return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
	}
}
