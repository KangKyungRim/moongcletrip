<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('service_detail')) {
    Capsule::schema()->create('service_detail', function (Blueprint $table) {
        $table->bigIncrements('service_detail_idx');
        $table->bigInteger('partner_idx')->unsigned()->index();  // Partner ID
        $table->string('service_name', 512);
        $table->text('service_description')->nullable();
        $table->integer('service_order')->nullable()->index();
        $table->timestamps();  // 생성 시각
    });
}