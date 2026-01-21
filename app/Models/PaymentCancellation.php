<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCancellation extends Model
{
    protected $table = 'payment_cancellations';
    protected $primaryKey = 'cancellation_idx';
    public $timestamps = true;

    protected $fillable = [
        'payment_idx',
        'canceled_amount',
        'cancellation_reason',
        'cancellation_status',
        'cancellation_key',
    ];

    // 결제와의 N:1 관계 설정
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_idx', 'payment_idx');
    }
}