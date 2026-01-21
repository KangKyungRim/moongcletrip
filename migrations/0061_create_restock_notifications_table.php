<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('restock_notifications')) {
    Capsule::schema()->create('restock_notifications', function (Blueprint $table) {
        $table->bigIncrements('notification_idx');  // 알림 ID (기본 키)
        $table->bigInteger('user_idx')->unsigned()->index();  // 유저 ID
        $table->bigInteger('product_idx')->unsigned()->index();  // 상품 ID (room_inventories, ticket_inventories, air_inventories 중 하나의 ID)
        $table->string('product_type', 50)->index();  // 상품 유형 (room_inventories, ticket_inventories, air_inventories)
        $table->boolean('is_notified')->default(false)->index();  // 알림이 발송되었는지 여부
        $table->boolean('is_active')->default(true)->index();
        $table->timestamps();  // 생성 및 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}