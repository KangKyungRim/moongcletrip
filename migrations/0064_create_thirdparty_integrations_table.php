<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('thirdparty_integrations')) {
    Capsule::schema()->create('thirdparty_integrations', function (Blueprint $table) {
        $table->bigIncrements('thirdparty_integration_idx');  // 로그 ID (기본 키)
        $table->bigInteger('user_idx')->unsigned()->nullable()->index();  // 유저 ID (비회원일 경우 null 가능)
        $table->bigInteger('token_idx')->unsigned()->nullable()->index();  // 유저 Token (웹일 경우 null 가능)
        $table->bigInteger('payment_item_idx')->unsigned()->nullable()->index();  // Payment Item Idx
        $table->string('system', 30)->index();  // sanha, tl
        $table->string('confirm_code', 100);
        $table->string('response_result', 10)->index();
        $table->string('response_code', 50);
        $table->text('response_body');
        
        $table->timestamps();  // 생성 시각 및 수정 시각

        // 외래 키 설정 (유저가 있을 경우 연결)
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('set null');
        $table->foreign('token_idx')->references('token_idx')->on('tokens')->onDelete('set null');
        $table->foreign('payment_item_idx')->references('payment_item_idx')->on('payment_items')->onDelete('set null');
    });
}