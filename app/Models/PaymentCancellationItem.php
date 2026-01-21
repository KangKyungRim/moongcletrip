<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCancellationItem extends Model
{
    protected $table = 'payment_cancellation_items';
    protected $primaryKey = 'cancellation_item_idx';
    public $timestamps = true;

    protected $fillable = [
        'cancellation_idx',
        'payment_item_idx',
        'canceled_quantity',
        'canceled_amount',
    ];

    // 결제 취소와의 관계 설정
    public function cancellation()
    {
        return $this->belongsTo(PaymentCancellation::class, 'cancellation_idx', 'cancellation_idx');
    }

    // 결제된 아이템과의 관계 설정
    public function paymentItem()
    {
        return $this->belongsTo(PaymentItem::class, 'payment_item_idx', 'payment_item_idx');
    }
}