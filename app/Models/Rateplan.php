<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rateplan extends Model
{
	// 테이블 이름 설정
	protected $table = 'rateplans';

	// 기본 키 설정
	protected $primaryKey = 'rateplan_idx';

	// 자동 타임스탬프 필드 사용
	public $timestamps = false;

	// 대량 할당이 가능한 필드
	protected $fillable = [
		'partner_idx',
		'rateplan_sanha_idx',
        'rateplan_tl_idx',
        'rateplan_onda_idx',
        'rateplan_waug_idx',
		'rateplan_thirdparty',
		'rateplan_order',
		'rateplan_name',
		'rateplan_type',
		'rateplan_description',
		'rateplan_stay_min',
		'rateplan_stay_max',
		'rateplan_sales_from',
		'rateplan_sales_to',
		'rateplan_cutoff_days',
		'rateplan_is_refundable',
		'rateplan_has_breakfast',
		'rateplan_has_lunch',
		'rateplan_has_dinner',
		'rateplan_meal_count',
		'rateplan_status',
		'rateplan_created_at',
		'rateplan_updated_at',
	];

	// 커스텀 타임스탬프 필드 설정
	const CREATED_AT = 'rateplan_created_at';
	const UPDATED_AT = 'rateplan_updated_at';

	// 날짜 형식으로 변환할 필드
	protected $dates = [
		'rateplan_created_at',
		'rateplan_updated_at',
	];

	// 파트너와의 관계 설정 (1:N 관계)
	public function partner()
	{
		return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
	}

	// 객실과의 다대다 관계 설정
	public function rooms()
	{
		return $this->belongsToMany(Room::class, 'room_rateplan', 'rateplan_idx', 'room_idx')
			->withTimestamps();
	}

	// RoomPrice와의 1:N 관계
	public function prices()
	{
		return $this->hasMany(RoomPrice::class, 'rate_plan_idx', 'rateplan_idx');
	}

	// 혜택과의 다대다 관계 설정
	public function benefits()
	{
		return $this->belongsToMany(Benefit::class, 'benefit_rateplan', 'rateplan_idx', 'benefit_idx')
			->withTimestamps();
	}

	// 태그 연결을 위한 관계 설정
    public function tags()
    {
        return $this->hasMany(TagConnection::class, 'item_idx', 'rateplan_idx')
                    ->where('item_type', 'rateplan');
    }
}
