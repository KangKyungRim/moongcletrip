<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('review_images')) {
    Capsule::schema()->create('review_images', function (Blueprint $table) {
        $table->bigIncrements('review_image_idx');  // 이미지 ID (기본 키)
        $table->bigInteger('review_idx')->unsigned()->index();  // 리뷰 ID
        $table->string('review_image_origin_path', 255);  // 원본 이미지 경로
        $table->string('review_image_small_path', 255)->nullable();  // 작은 이미지 경로
        $table->string('review_image_normal_path', 255)->nullable();  // 중간 크기 이미지 경로
        $table->string('review_image_big_path', 255)->nullable();  // 중간 크기 이미지 경로
        $table->integer('review_image_order')->nullable()->index();  // 중간 크기 이미지 경로
        $table->string('review_image_extension', 10)->nullable()->index();
        $table->timestamps();  // 생성 시각

        // 외래 키 설정
        $table->foreign('review_idx')->references('review_idx')->on('reviews')->onDelete('cascade');
    });
}