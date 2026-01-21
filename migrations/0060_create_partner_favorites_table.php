<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('partner_favorites')) {
    Capsule::schema()->create('partner_favorites', function (Blueprint $table) {
        $table->bigIncrements('favorite_idx');  // 기본 키
        $table->bigInteger('user_idx')->unsigned()->index();  // 유저 ID (관심을 지정한 유저)
        $table->bigInteger('partner_idx')->unsigned()->index();  // 파트너 ID (관심 대상)
        $table->bigInteger('moongcleoffer_idx')->unsigned()->nullable()->index();
        $table->string('target', 30)->default('partner')->index();
        $table->timestamps();  // 생성 시각 및 수정 시각

        // 유니크 제약 조건: 같은 유저가 동일 파트너를 중복해서 관심 지정하지 못하도록 설정
        $table->unique(['user_idx', 'partner_idx', 'target']);

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('cascade');
    });
}