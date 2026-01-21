<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('cancel_rules')) {
    Capsule::schema()->create('cancel_rules', function (Blueprint $table) {
        $table->bigIncrements('cancel_rule_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('partner_idx')->unsigned()->nullable();  // 파트너 외래 키 (nullable)
        $table->integer('cancel_rules_order')->index();  // 규칙 순서
        $table->integer('cancel_rules_percent')->index();  // 취소 시 환불 비율
        $table->integer('cancel_rules_day')->index();  // 취소 가능 일수
        $table->time('cancel_rules_time')->nullable()->index();  // 취소 시간
        $table->timestamp('cancel_rules_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 생성 시각
        $table->timestamp('cancel_rules_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 수정 시각

        $table->unique(['partner_idx', 'cancel_rules_percent', 'cancel_rules_day', 'cancel_rules_order'], 'idx_partner_id_percent_day');

        // 파트너 외래 키
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('cascade');
    });
}
