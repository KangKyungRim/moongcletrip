<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('travel_carts')) {
    Capsule::schema()->create('travel_carts', function (Blueprint $table) {
        $table->bigIncrements('travel_cart_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('user_idx')->unsigned()->index();  // 장바구니 주인의 유저
        $table->string('travel_cart_status', 50)->default('pending');  // 장바구니 상태 (pending, ordered, canceled)

        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}