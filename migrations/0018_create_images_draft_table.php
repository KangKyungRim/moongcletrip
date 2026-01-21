<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('images_draft')) {
    Capsule::schema()->create('images_draft', function (Blueprint $table) {
        $table->bigIncrements('image_draft_idx');  // 기본 키
        $table->bigInteger('image_entity_id')->unsigned();  // 엔티티 ID (파트너, 스테이, 객실 등)
        $table->string('image_entity_type', 50)->index();  // 엔티티 유형 (partner, stay, room 등)
        $table->string('image_type', 30)->index();  // 이미지 유형 (main, sub 등)
        $table->string('image_origin_name', 255);  // 원본 이미지 이름
        $table->string('image_origin_path', 255);  // 원본 이미지 경로
        $table->string('image_small_path', 255)->nullable();  // 250px 이미지 경로
        $table->string('image_normal_path', 255)->nullable();  // 500px 크기 이미지 경로
        $table->string('image_big_path', 255)->nullable();  // 1000px 크기 이미지 경로
        $table->integer('image_origin_size')->nullable();
        $table->integer('image_order')->nullable();
        $table->boolean('is_approved')->index();
        $table->timestamps();
    });
}
