<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('content_images')) {
    Capsule::schema()->create('content_images', function (Blueprint $table) {
        $table->bigIncrements('content_image_idx');  // 이미지 ID (기본 키)
        $table->bigInteger('entity_id')->unsigned();  // 연결된 엔티티(공지, FAQ, 약관, 아티클 등)의 ID
        $table->string('entity_type', 50)->index();  // 엔티티 유형 (공지, FAQ, 약관, 아티클 등)
        $table->string('content_image_type', 30)->index();  // 이미지 유형 (예: main, sub 등)
        $table->string('content_image_origin_path', 255);  // 원본 이미지 경로
        $table->string('content_image_small_path', 255)->nullable();  // 작은 이미지 경로
        $table->string('content_image_normal_path', 255)->nullable();  // 중간 크기 이미지 경로
        $table->timestamps();  // 생성 시각, 수정 시각

        // entity_id와 entity_type의 조합으로 인덱스 생성 (유니크는 아닙니다.)
        $table->index(['entity_id', 'entity_type']);
    });
}