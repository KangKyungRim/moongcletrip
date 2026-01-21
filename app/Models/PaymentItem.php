<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    protected $table = 'payment_items';
    protected $primaryKey = 'payment_item_idx';
    public $timestamps = true;

    protected $fillable = [
        'user_idx',
        'payment_idx',
        'cart_item_idx',
        'partner_idx',
        'payment_partner_charge',
        'product_idx',
        'product_category',
        'product_type',
        'product_name',
        'product_partner_name',
        'product_detail_name',
        'product_benefits',
        'datewise_product_data',
        'item_basic_price',
        'item_sale_price',
        'item_origin_sale_price',
        'quantity',
        'reservation_number',
        'reservation_pending_code',
        'reservation_confirmed_code',
        'start_date',
        'end_date',
        'free_cancel_date',
        'refundable',
        'reservation_personnel',
        'payment_status',
        'reservation_status',
        'thirdparty_type',
        'refund_policy',
        'canceled_quantity',
        'canceled_amount',
        'canceled_reason'
    ];

    protected $casts = [
        'product_benefits' => 'array',
        'datewise_product_data' => 'array',
        'reservation_personnel' => 'array',
        'refund_policy' => 'array'
    ];

    // Payment과의 N:1 관계 설정
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_idx', 'payment_idx');
    }

    // 결제된 장바구니 아이템과의 관계 설정
    public function cartItem()
    {
        return $this->belongsTo(CartItem::class, 'cart_item_idx', 'cart_item_idx');
    }
}
