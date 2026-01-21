<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerFavorite extends Model
{
    protected $table = 'partner_favorites';
    protected $primaryKey = 'favorite_idx';
    public $timestamps = true;

    protected $fillable = [
        'user_idx',
        'partner_idx',
        'moongcleoffer_idx',
        'target'
    ];

    // 유저와의 관계 설정
    public function user()
    {
        return $this->belongsTo(User::class, 'user_idx', 'user_idx');
    }

    // 파트너와의 관계 설정
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_idx', 'partner_idx');
    }
}