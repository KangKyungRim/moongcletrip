<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('main_view_list')) {
    Capsule::schema()->create('main_view_list', function (Blueprint $table) {
        $table->bigIncrements('list_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('partner_idx')->nullable()->unsigned()->index();
        $table->string('list_type', 20)->nullable()->index();
        $table->integer('list_order')->default(0)->nullable()->index();
        $table->timestamps();
    });
}
