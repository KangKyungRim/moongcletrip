<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCancelRule extends Model
{
    protected $table = 'product_cancel_rules';
    protected $primaryKey = 'product_cancel_rule_idx';
    public $timestamps = true;

    protected $fillable = [
        'product_idx',
		'product_category',
        'cancel_rule_order',
        'cancel_rule_percent',
        'cancel_rule_day',
        'cancel_rule_time',
        'start_date',
        'end_date',
        'is_approved',
    ];

	// 상품 데이터를 가져오는 메서드
	// public function getProduct()
	// {
	// 	switch ($this->product_category) {
	// 		case 'room_price':
	// 			return $this->getRoomWithRatePlan($this->product_idx);
	// 		case 'moongcleoffer':
	// 			return MoongcleOffer::find($this->product_idx);
	// 		case 'moongclebundle':
	// 			return MoongcleBundle::find($this->product_idx);
	// 		default:
	// 			return null;
	// 	}
	// }

	// RoomPrice 아이디를 이용해 Room과 RatePlan을 함께 가져오는 로직
	// public function getRoomWithRatePlan($roomPriceId)
	// {
	// 	// RoomPrice 모델을 통해 Room과 RatePlan을 함께 가져오기
	// 	$roomPrice = RoomPrice::find($roomPriceId);

	// 	if ($roomPrice) {
	// 		return [
	// 			'room' => $roomPrice->room,
	// 			'rateplan' => $roomPrice->rateplan,
	// 		];
	// 	}
	// 	return null;
	// }

    // Draft와의 관계
    public function drafts()
    {
        return $this->hasMany(ProductCancelRuleDraft::class, 'product_cancel_rule_idx', 'product_cancel_rule_idx');
    }
}