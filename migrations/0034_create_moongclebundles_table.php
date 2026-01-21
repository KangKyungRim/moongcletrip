<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongclebundles')) {
    Capsule::schema()->create('moongclebundles', function (Blueprint $table) {
        $table->bigIncrements('moongclebundle_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('partner_idx')->nullable()->unsigned()->index();  // 뭉클오퍼를 만든 파트너
        $table->bigInteger('user_idx')->nullable()->unsigned()->index();  // 뭉클오퍼를 만든 유저
        $table->bigInteger('moongcle_point_idx')->unsigned()->nullable()->index();  // Moongcle Points ID (nullable)
        $table->string('moongclebundle_status', 10)->default('disabled')->index();  // 딜 활성화 여부
        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('set null');
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('set null');
        $table->foreign('moongcle_point_idx')->references('moongcle_point_idx')->on('moongcle_points')->onDelete('set null');
    });
}