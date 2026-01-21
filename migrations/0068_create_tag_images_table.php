<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('tag_images')) {
    Capsule::schema()->create('tag_images', function (Blueprint $table) {
        $table->bigIncrements('tag_image_idx');  // 기본 키
        $table->bigInteger('tag_idx')->unsigned();  // Tag ID
        $table->string('tag_image_origin_path', 255);  // 원본 이미지 경로
        $table->string('tag_image_small_path', 255)->nullable();  // 작은 이미지 경로
        $table->string('tag_image_normal_path', 255)->nullable();  // 중간 크기 이미지 경로
        $table->string('tag_image_origin_name', 255)->nullable();
        $table->timestamp('tag_image_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 생성 시각
        $table->timestamp('tag_image_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 수정 시각

        // 외래 키 설정
        $table->foreign('tag_idx')->references('tag_idx')->on('tags')->onDelete('cascade');
    });
}