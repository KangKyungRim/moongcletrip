<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('cancel_rules_draft')) {
    Capsule::schema()->create('cancel_rules_draft', function (Blueprint $table) {
        $table->bigIncrements('cancel_rules_draft_idx');  // 기본 키
        $table->bigInteger('cancel_rule_idx')->unsigned()->nullable();  // 실제 취소 규칙 테이블 ID
        $table->bigInteger('partner_idx')->unsigned()->nullable();  // 파트너 외래 키
        $table->integer('cancel_rules_order')->index();  // 규칙 순서
        $table->integer('cancel_rules_percent')->index();  // 취소 시 환불 비율
        $table->integer('cancel_rules_day')->index();  // 취소 가능 일수
        $table->time('cancel_rules_time')->nullable()->index();  // 취소 시간
        $table->boolean('is_approved')->default(false)->index();  // 심사 상태 (false: 미승인, true: 승인됨)
        $table->timestamps();

        // 외래 키 설정
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('cascade');
    });
}