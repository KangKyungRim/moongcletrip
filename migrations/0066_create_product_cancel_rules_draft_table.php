<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('product_cancel_rules_draft')) {
    Capsule::schema()->create('product_cancel_rules_draft', function (Blueprint $table) {
        $table->bigIncrements('product_cancel_rule_draft_idx');  // 드래프트 취소 규정 ID (기본 키)
        $table->bigInteger('product_cancel_rule_idx')->unsigned()->index();  // 복제된 취소 규정 ID
        $table->integer('new_cancel_rule_order')->index();  // 수정된 규칙 순서
        $table->integer('new_cancel_rule_percent')->index();  // 수정된 환불 비율
        $table->integer('new_cancel_rule_day')->index();  // 수정된 취소 가능 일수
        $table->time('new_cancel_rule_time')->nullable()->index();  // 수정된 취소 시간
        $table->boolean('is_approved')->default(false);  // 승인 여부
        $table->timestamps();  // 생성 시각 및 수정 시각

        // 외래 키 설정
        $table->foreign('product_cancel_rule_idx')->references('product_cancel_rule_idx')->on('product_cancel_rules')->onDelete('cascade');
    });
}
