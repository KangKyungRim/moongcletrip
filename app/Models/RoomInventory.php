<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomInventory extends Model
{
    protected $table = 'room_inventories';
    protected $primaryKey = 'inventory_idx';

    public $timestamps = true;

    // 대량 할당이 가능한 필드
    protected $fillable = [
        'room_idx',
        'rateplan_idx',
        'room_rateplan_idx',
        'inventory_date',
        'inventory_quantity',
        'inventory_sold_quantity',
        'is_closed'
    ];

    // 재고는 하나의 객실에 속함
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_idx', 'room_idx');
    }
}
