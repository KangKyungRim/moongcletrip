<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('points')) {
    Capsule::schema()->create('points', function (Blueprint $table) {
        $table->bigIncrements('point_idx');  // 포인트 ID (기본 키)
        $table->bigInteger('user_idx')->unsigned()->index();  // 포인트 소유자 (유저 ID)
        $table->enum('point_type', ['earn', 'use'])->default('earn');  // 포인트 타입 (적립 or 사용)
        $table->decimal('point_amount', 10, 2);  // 포인트 금액
        $table->string('description', 255)->nullable();  // 포인트 적립/사용에 대한 설명
        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}