<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
	protected $table = 'reviews';
	protected $primaryKey = 'review_idx';
	public $timestamps = true;

	protected $fillable = [
		'user_idx',
		'partner_idx',
		'partner_name',
		'moongcledeal_idx',
		'product_idx',
		'payment_item_idx',
		'review_category',
		'rating',
		'review_content',
		'is_active',
	];

	// 유저와의 관계 설정
	public function user()
	{
		return $this->belongsTo(User::class, 'user_idx', 'user_idx');
	}

	// 뭉클딜과의 관계 설정
	public function moongcledeal()
	{
		return $this->belongsTo(MoongcleDeal::class, 'moongcledeal_idx', 'moongcledeal_idx');
	}

	// 리뷰와 파트너의 다대다 관계 설정
	public function partners()
	{
		return $this->belongsToMany(Partner::class, 'review_partner', 'review_idx', 'partner_idx');
	}

	// 리뷰와 이미지의 1:N 관계 설정
	public function images()
	{
		return $this->hasMany(ReviewImage::class, 'review_idx', 'review_idx');
	}

	// 리뷰와 댓글의 관계 설정 (1:N)
	public function comments()
	{
		return $this->hasMany(ReviewComment::class, 'review_idx', 'review_idx')
			->whereNull('parent_comment_idx');  // 부모 댓글만 가져옴
	}

	// 리뷰 대상 로직으로 처리
	public function getProduct()
	{
		switch ($this->review_category) {
			case 'room_price':
				return $this->getRoomWithRatePlan($this->product_idx);
			case 'moongcleoffer':
				return MoongcleOffer::find($this->product_idx);
			case 'moongclebundle':
				return MoongcleBundle::find($this->product_idx);
			default:
				return null;
		}
	}

	// RoomPrice 아이디를 이용해 Room과 RatePlan을 함께 가져오는 로직
	public function getRoomWithRatePlan($roomPriceId)
	{
		// RoomPrice 모델을 통해 Room과 RatePlan을 함께 가져오기
		$roomPrice = RoomPrice::find($roomPriceId);

		if ($roomPrice) {
			return [
				'room' => $roomPrice->room,
				'rateplan' => $roomPrice->rateplan,
			];
		}
		return null;
	}
}
