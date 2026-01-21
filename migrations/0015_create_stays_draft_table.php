<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('stays_draft')) {
    Capsule::schema()->create('stays_draft', function (Blueprint $table) {
        $table->bigIncrements('stay_draft_idx');  // 기본 키
        $table->bigInteger('stay_idx')->unsigned()->nullable();
        $table->time('stay_checkin_rule')->nullable()->index();
        $table->time('stay_checkout_rule')->nullable()->index();
        $table->text('stay_basic_info')->nullable();
        $table->text('stay_important_info')->nullable();
        $table->text('stay_notice_info')->nullable();
        $table->text('stay_amenity_info')->nullable();
        $table->text('stay_breakfast_info')->nullable();
        $table->text('stay_personnel_info')->nullable();
        $table->text('stay_cancel_info')->nullable();
        $table->boolean('is_approved')->default(false)->index();  // 심사 상태 (false: 미승인, true: 승인됨)
        $table->timestamps();
    });
}
