<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
	protected $table = 'search_logs';
	protected $primaryKey = 'log_idx';
	public $timestamps = true;

	protected $fillable = [
		'user_idx',
		'token_idx',
		'search_params',
		'result_count',
		'user_ip',
	];

	// 유저와의 관계 설정
	public function user()
	{
		return $this->belongsTo(User::class, 'user_idx', 'user_idx');
	}

	// 토큰과의 관계 설정
	public function token()
	{
		return $this->belongsTo(Token::class, 'token_idx', 'token_idx');
	}
}
