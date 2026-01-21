<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('partner_user_assignment') && Capsule::schema()->hasTable('partners') && Capsule::schema()->hasTable('partner_users')) {
    Capsule::schema()->create('partner_user_assignment', function (Blueprint $table) {
        $table->bigInteger('partner_idx')->unsigned();
        $table->bigInteger('partner_user_idx')->unsigned();
        $table->boolean('is_manager')->default(false);  // 매니저 여부

        // 외래 키 설정
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('cascade');
        $table->foreign('partner_user_idx')->references('partner_user_idx')->on('partner_users')->onDelete('cascade');

        // 유니크 제약 조건: 각 파트너는 여러 유저와 연결될 수 있지만 매니저는 한 명만
        $table->unique(['partner_idx', 'partner_user_idx']);
    });
}