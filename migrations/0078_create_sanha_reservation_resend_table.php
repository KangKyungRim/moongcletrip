<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('sanha_reservation_resend')) {
    Capsule::schema()->create('sanha_reservation_resend', function (Blueprint $table) {
        $table->bigIncrements('resend_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('payment_idx')->nullable()->unsigned()->index();
        $table->bigInteger('payment_item_idx')->nullable()->unsigned()->index();
        $table->string('security_key', 100)->nullable()->index();
        $table->string('separated_value', 100)->nullable()->index();
        $table->string('result', 10)->nullable()->index();
        $table->string('result_code', 30)->nullable()->index();
        $table->timestamps();
    });
}
