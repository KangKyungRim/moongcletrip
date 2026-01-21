<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('stays')) {
    Capsule::schema()->create('stays', function (Blueprint $table) {
        $table->bigIncrements('stay_idx');  // 기본 키 (Auto-increment)
        $table->time('stay_checkin_rule')->nullable()->index();
        $table->time('stay_checkout_rule')->nullable()->index();
        $table->text('stay_basic_info')->nullable();
        $table->text('stay_important_info')->nullable();
        $table->text('stay_notice_info')->nullable();
        $table->text('stay_amenity_info')->nullable();
        $table->text('stay_breakfast_info')->nullable();
        $table->text('stay_personnel_info')->nullable();
        $table->text('stay_cancel_info')->nullable();
        $table->string('stay_status', 10)->default('disabled')->index();
        $table->timestamp('stay_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 생성 시각
        $table->timestamp('stay_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 수정 시각
    });
}
