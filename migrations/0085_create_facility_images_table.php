<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('facility_images')) {
    Capsule::schema()->create('facility_images', function (Blueprint $table) {
        $table->bigIncrements('facility_image_idx');  // 이미지 ID (기본 키)
        $table->bigInteger('facility_detail_idx')->unsigned()->index();  // Partner ID
        $table->string('image_origin_name', 255);
        $table->string('image_origin_path', 255);  // 원본 이미지 경로
        $table->string('image_small_path', 255)->nullable();  // 작은 이미지 경로
        $table->string('image_normal_path', 255)->nullable();  // 중간 크기 이미지 경로
        $table->string('image_big_path', 255)->nullable();  // 중간 크기 이미지 경로
        $table->integer('image_origin_size')->nullable();
        $table->integer('image_order')->nullable()->index();  // 중간 크기 이미지 경로
        $table->string('image_extension', 10)->nullable()->index();
        $table->timestamps();  // 생성 시각
    });
}