<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// 마이그레이션 실행
if (!Capsule::schema()->hasTable('tokens')) {
    Capsule::schema()->create('tokens', function (Blueprint $table) {
        $table->bigIncrements('token_idx');
        $table->string('token', 512)->nullable(); // 토큰 값은 nullable
        $table->bigInteger('user_idx')->unsigned()->nullable()->index();  // 유저와 연결되는 외래 키 (nullable)
        $table->bigInteger('guest_idx')->unsigned()->nullable()->index();  // 유저와 연결되는 외래 키 (nullable)
        $table->string('token_device_type', 10)->index(); // android, ios, other
        $table->boolean('token_is_active')->default(true);  // 토큰 활성화 여부
        $table->timestamp('token_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();
        $table->timestamp('token_last_used_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();

        // 유저와의 외래 키 관계 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('set null');
    });
}
