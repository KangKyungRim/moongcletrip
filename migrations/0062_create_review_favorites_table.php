<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('review_favorites')) {
    Capsule::schema()->create('review_favorites', function (Blueprint $table) {
        $table->bigIncrements('favorite_idx');  // 기본 키
        $table->bigInteger('user_idx')->unsigned()->index();  // 찜한 유저 ID
        $table->bigInteger('review_idx')->unsigned()->index();  // 찜한 리뷰 ID
        $table->timestamps();  // 생성 시각 및 수정 시각

        // 유니크 제약 조건: 동일 유저가 동일 리뷰를 중복 찜할 수 없도록 설정
        $table->unique(['user_idx', 'review_idx']);

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
        $table->foreign('review_idx')->references('review_idx')->on('reviews')->onDelete('cascade');
    });
}