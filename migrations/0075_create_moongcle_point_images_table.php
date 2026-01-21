<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcle_point_images')) {
    Capsule::schema()->create('moongcle_point_images', function (Blueprint $table) {
        $table->bigIncrements('moongcle_point_image_idx');  // 이미지 ID (기본 키)
        $table->bigInteger('moongcle_point_idx')->unsigned()->index();  // 리뷰 ID
        $table->string('image_origin_name', 255);
        $table->string('image_origin_path', 255);  // 원본 이미지 경로
        $table->string('image_small_path', 255)->nullable();  // 작은 이미지 경로
        $table->string('image_normal_path', 255)->nullable();  // 중간 크기 이미지 경로
        $table->string('image_big_path', 255)->nullable();  // 중간 크기 이미지 경로
        $table->integer('image_origin_size')->nullable();
        $table->integer('image_order')->nullable()->index();  // 중간 크기 이미지 경로
        $table->string('image_extension', 10)->nullable()->index();
        $table->timestamps();  // 생성 시각

        // 외래 키 설정
        $table->foreign('moongcle_point_idx')->references('moongcle_point_idx')->on('moongcle_points')->onDelete('cascade');
    });
}