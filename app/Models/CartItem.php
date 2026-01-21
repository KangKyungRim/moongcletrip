<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
	// 테이블 이름 설정
	protected $table = 'cart_items';

	// 기본 키 설정
	protected $primaryKey = 'cart_item_idx';

	// 자동 타임스탬프 필드 사용
	public $timestamps = true;

	// 대량 할당이 가능한 필드
	protected $fillable = [
		'travel_cart_idx',
		'product_idx',
		'product_category',
		'quantity',
	];

	// 상품 데이터를 가져오는 메서드
	public function getProduct()
	{
		switch ($this->product_category) {
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

	// 장바구니와의 관계 설정 (N:1)
	public function cart()
	{
		return $this->belongsTo(TravelCart::class, 'travel_cart_idx', 'travel_cart_idx');
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
