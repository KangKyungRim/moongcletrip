<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('stay_moongcleoffers')) {
    Capsule::schema()->create('stay_moongcleoffers', function (Blueprint $table) {
        $table->bigIncrements('stay_moongcleoffer_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('partner_idx')->unsigned()->index();
        $table->bigInteger('rateplan_idx')->nullable()->unsigned()->index();
        $table->string('stay_moongcleoffer_title', 512);
        $table->timestamp('sale_start_date')->nullable()->index();
        $table->timestamp('sale_end_date')->nullable()->index();
        $table->timestamp('stay_start_date')->nullable()->index();
        $table->timestamp('stay_end_date')->nullable()->index();
        $table->json('blackout_dates')->nullable();
        $table->json('benefits')->nullable();
        $table->json('rooms')->nullable();
        $table->json('tags')->nullable();
        $table->json('curated_tags')->nullable();
        $table->string('audience', 20)->default('all')->index();
        $table->text('custom_message')->nullable();
        $table->integer('attractive')->default(0)->index();
        $table->string('stay_moongcleoffer_status', 10)->default('disabled')->index();
        $table->timestamps();
    });
}
