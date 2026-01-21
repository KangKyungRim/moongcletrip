<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageDraft extends Model
{
	protected $table = 'images_draft';  // 테이블 이름
	protected $primaryKey = 'image_draft_idx';  // 기본 키
	public $timestamps = true;  // 자동 타임스탬프 사용하지 않음

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
		'is_approved'
	];
}
