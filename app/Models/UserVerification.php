<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
	protected $table = 'user_verifications';
	protected $primaryKey = 'verification_idx';

	public $timestamps = false;

	// 대량 할당 가능한 필드
	protected $fillable = [
		'user_idx',
		'token',
		'created_at'
	];

	// 유효기간이 지난 토큰인지 확인하는 함수 (예: 10분 유효)
	public function isExpired()
	{
		$createdAt = strtotime($this->created_at);
		return (time() - $createdAt) > 600;  // 10분 (600초)
	}
}
