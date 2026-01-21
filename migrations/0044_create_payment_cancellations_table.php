<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('payment_cancellations')) {
    Capsule::schema()->create('payment_cancellations', function (Blueprint $table) {
        $table->bigIncrements('cancellation_idx');  // 취소 ID (기본 키)
        $table->bigInteger('payment_idx')->unsigned()->index();  // 취소된 결제 ID
        $table->decimal('canceled_amount', 10, 2);  // 취소된 금액
        $table->string('cancellation_reason', 255)->nullable();  // 취소 사유
        $table->string('cancellation_status', 50)->default('pending');  // 취소 상태 (pending, completed)
        $table->string('cancellation_key', 255)->nullable();  // 취소 트랜잭션 키
        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('payment_idx')->references('payment_idx')->on('payments')->onDelete('cascade');
    });
}