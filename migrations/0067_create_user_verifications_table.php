<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('user_verifications')) {
    Capsule::schema()->create('user_verifications', function (Blueprint $table) {
        $table->bigIncrements('verification_idx');  // 기본 키
        $table->bigInteger('user_idx')->unsigned();  // 외래 키 (users 테이블 참조)
        $table->string('token', 64);  // 인증에 사용되는 토큰
        $table->timestamp('created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));  // 토큰 생성 시각
    });
}
