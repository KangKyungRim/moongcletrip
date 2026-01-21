<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('sanha_logs')) {
    Capsule::schema()->create('sanha_logs', function (Blueprint $table) {
        $table->bigIncrements('sanha_log_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('payment_idx')->nullable()->unsigned()->index();
        $table->bigInteger('payment_item_idx')->nullable()->unsigned()->index();
        $table->string('action_type', 10)->nullable()->index();
        $table->string('pms_code', 100)->nullable()->index();
        $table->string('response_code', 100)->nullable()->index();
        $table->string('response_result', 10)->nullable()->index();
        $table->timestamps();
    });
}
