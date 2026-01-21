<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('facility_detail')) {
    Capsule::schema()->create('facility_detail', function (Blueprint $table) {
        $table->bigIncrements('facility_detail_idx');
        $table->bigInteger('partner_idx')->unsigned()->index();  // Partner ID
        $table->string('facility_name', 512);
        $table->string('facility_sub', 512);
        $table->text('facility_description')->nullable();
        $table->integer('facility_order')->nullable()->index();
        $table->timestamps();  // 생성 시각
    });
}