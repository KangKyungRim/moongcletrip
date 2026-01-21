<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('vouchers')) {
    Capsule::schema()->create('vouchers', function (Blueprint $table) {
        $table->bigIncrements('voucher_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('user_idx')->unsigned()->index();  // 알림을 받는 유저
        $table->bigInteger('item_idx')->unsigned()->nullable(); // payment_idx
        $table->string('item_type', 50)->nullable()->index(); // paymentItem
        $table->string('item_status', 50)->nullable(); // booking / cancel
        $table->string('title', 255)->nullable();  // 알림 제목
        $table->text('message')->nullable();  // 알림 내용
        $table->string('send_type', 30)->default('email')->index(); // email / kakao
        $table->string('status', 30)->default('pending')->index(); // pending / send / fail
        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}
