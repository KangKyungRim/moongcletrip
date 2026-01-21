<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
	protected $table = 'images';  // 테이블 이름
	protected $primaryKey = 'image_idx';  // 기본 키
	public $timestamps = false;  // 자동 타임스탬프 사용하지 않음

	protected $fillable = [
		'image_entity_id',  // 엔티티 ID (파트너, 스테이, 객실 등)
		'image_entity_type',  // 엔티티 유형 (partner, stay, room 등)
		'image_type',  // 이미지 유형
		'image_origin_name',
		'image_origin_path',  // 원본 이미지 경로
		'image_small_path',  // 250px 이미지 경로
		'image_normal_path',  // 500px 크기 이미지 경로
		'image_big_path',  // 1000px 크기 이미지 경로
		'image_origin_size',
		'image_order',
		'image_created_at',  // 이미지 생성 시각
		'image_updated_at',  // 이미지 업데이트 시각
	];

	const CREATED_AT = 'image_created_at';  // 생성 시각 필드
	const UPDATED_AT = 'image_updated_at';  // 업데이트 시각 필드

	// 날짜 형식으로 변환할 필드
	protected $dates = [
		'image_created_at',
		'image_updated_at',
	];
}
