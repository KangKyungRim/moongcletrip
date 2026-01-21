<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockNotification extends Model
{
    protected $table = 'restock_notifications';
    protected $primaryKey = 'notification_idx';
    public $timestamps = true;

    protected $fillable = [
        'user_idx',
        'product_idx',
        'product_type',
        'notification_date',
        'is_notified',
        'is_active',
    ];

    // 유저와의 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }

    // 상품을 동적으로 가져오는 로직 (Room, Ticket, Air)
    public function getProduct()
    {
        switch ($this->product_type) {
            case 'room_inventories':
                return RoomInventory::find($this->product_idx);
            default:
                return null;
        }
    }
}