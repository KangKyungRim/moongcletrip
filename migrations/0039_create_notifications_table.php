<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('notifications')) {
    Capsule::schema()->create('notifications', function (Blueprint $table) {
        $table->bigIncrements('notification_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('user_idx')->unsigned()->index();  // 알림을 받는 유저
        $table->bigInteger('base_idx')->unsigned()->nullable()->index();
        $table->bigInteger('target_idx')->unsigned()->nullable()->index();
        $table->string('notification_type', 50)->index();  // 알림 유형 (e.g., moongcledeal_match, announcement)
        $table->string('title', 255);  // 알림 제목
        $table->text('message');  // 알림 내용
        $table->text('link')->nullable();
        $table->boolean('is_read')->default(false)->index();  // 유저가 읽었는지 여부
        $table->string('push_status', 30)->nullable()->index();
        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}
