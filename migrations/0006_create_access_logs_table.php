<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('access_logs') && Capsule::schema()->hasTable('users') && Capsule::schema()->hasTable('tokens')) {
    Capsule::schema()->create('access_logs', function (Blueprint $table) {
        $table->bigIncrements('access_idx');  // 고유 ID
        $table->bigInteger('user_idx')->unsigned()->nullable()->index();  // 로그인한 유저일 경우 유저 ID
        $table->bigInteger('token_idx')->unsigned()->nullable()->index(); // 토큰만 있는 경우 또는 로그인한 유저의 토큰
        $table->string('access_ip', 45)->nullable();  // 로그인한 IP 주소 (IPv6 대응 가능)
        $table->string('access_device_type', 10)->nullable(); // 로그인한 기기 타입 (android, ios 등)
        $table->string('access_path', 255)->index()->nullable(); // 링크
        $table->longText('access_full_path')->nullable(); // 링크
        $table->timestamp('access_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));  // 접근 시간

        // 외래 키 설정: user_idx -> users 테이블의 user_idx
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('set null');

        // 외래 키 설정: token_idx -> tokens 테이블의 token_idx
        $table->foreign('token_idx')->references('token_idx')->on('tokens')->onDelete('set null');
    });
}
