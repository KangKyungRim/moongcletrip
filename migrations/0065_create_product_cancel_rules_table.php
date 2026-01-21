<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('product_cancel_rules')) {
    Capsule::schema()->create('product_cancel_rules', function (Blueprint $table) {
        $table->bigIncrements('product_cancel_rule_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('product_idx')->unsigned()->index();  // Product ID (Room+RatePlan, Ticket, Air 등의 ID)
        $table->string('product_category', 50)->index();  // 상품 카테고리 (Ticket, Room+RatePlan, Air)
        $table->integer('cancel_rule_order')->index();  // 규칙 순서
        $table->integer('cancel_rule_percent')->index();  // 취소 시 환불 비율
        $table->integer('cancel_rule_day')->index();  // 취소 가능 일수
        $table->time('cancel_rule_time')->nullable()->index();  // 취소 시간
        $table->date('start_date')->nullable();  // 규정이 적용되는 시작 날짜
        $table->date('end_date')->nullable();  // 규정이 적용되는 종료 날짜
        $table->boolean('is_approved')->default(false);  // 승인 여부 (심사 통과 여부)
        $table->timestamps();  // 생성 시각 및 수정 시각
    });
}