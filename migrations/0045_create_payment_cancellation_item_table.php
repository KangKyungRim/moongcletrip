<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('payment_cancellation_items')) {
    Capsule::schema()->create('payment_cancellation_items', function (Blueprint $table) {
        $table->bigIncrements('cancellation_item_idx');  // 취소된 아이템 ID (기본 키)
        $table->bigInteger('cancellation_idx')->unsigned()->index();  // 결제 취소 ID
        $table->bigInteger('payment_item_idx')->unsigned()->index();  // 결제 아이템 ID

        $table->integer('canceled_quantity');  // 취소된 수량
        $table->decimal('canceled_amount', 10, 2);  // 취소된 금액
        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('cancellation_idx')->references('cancellation_idx')->on('payment_cancellations')->onDelete('cascade');
        $table->foreign('payment_item_idx')->references('payment_item_idx')->on('payment_items')->onDelete('cascade');
    });
}