<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
	// 테이블 이름 설정
	protected $table = 'rooms';

	// 기본 키 설정
	protected $primaryKey = 'room_idx';

	// 자동 타임스탬프 사용하지 않음 (커스텀 필드 사용)
	public $timestamps = false;

	// 대량 할당이 가능한 필드
	protected $fillable = [
		'partner_idx',
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
		'room_status',
		'room_created_at',
		'room_updated_at',
	];

	protected $casts = [
		'room_bed_type' => 'array',
	];

	const CREATED_AT = 'room_created_at';  // 생성 시각 필드
	const UPDATED_AT = 'room_updated_at';  // 업데이트 시각 필드

	// 날짜 형식으로 변환할 필드
	protected $dates = [
		'room_created_at',
		'room_updated_at',
	];

	// 파트너와의 관계 (하나의 파트너가 여러 개의 객실을 가질 수 있음)
	public function partner()
	{
		return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
	}

	public function draft()
    {
        return $this->hasOne(RoomDraft::class, 'room_idx', 'room_idx');
    }

	// RoomPrice와의 1:N 관계
    public function prices()
    {
        return $this->hasMany(RoomPrice::class, 'room_idx', 'room_idx');
    }

	// 다대다 관계: 객실과 요금제
	public function rateplans()
	{
		return $this->belongsToMany(Rateplan::class, 'room_rateplan', 'room_idx', 'rateplan_idx')
			->withTimestamps();
	}

	// 객실의 재고 정보
	public function inventories()
	{
		return $this->hasMany(RoomInventory::class, 'room_idx', 'room_idx');
	}

	// 특정 날짜에 객실의 재고를 가져오는 메서드
    public function inventoryForDate($date)
    {
        return $this->inventories()->where('inventory_date', $date)->first();
    }

	// 태그 연결을 위한 관계 설정
    public function tags()
    {
        return $this->hasMany(TagConnection::class, 'item_idx', 'room_idx')
                    ->where('item_type', 'room');
    }

	// 파트너에 연결된 승인된 이미지 조회
	public function approvedImages()
	{
		return Image::where('image_entity_id', $this->room_idx)
			->where('image_entity_type', 'room')
			->get();
	}

	// 파트너에 연결된 임시 이미지 조회
	public function draftImages()
	{
		return ImageDraft::where('image_entity_id', $this->room_idx)
			->where('image_entity_type', 'room')
			->get();
	}
}
