<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = 'points';
    protected $primaryKey = 'point_idx';
    public $timestamps = true;

    protected $fillable = [
        'user_idx',
        'point_type',
        'point_amount',
        'description',
    ];

    // 유저와의 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }
}