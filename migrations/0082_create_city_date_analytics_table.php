<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('city_date_analytics')) {
    Capsule::schema()->create('city_date_analytics', function (Blueprint $table) {
        $table->bigIncrements('analytics_idx');

        $table->string('month_key', 7)->nullable()->index();
        $table->string('city_tag', 50)->nullable()->index();
        $table->integer('count')->default(0)->index();

        $table->timestamps();
    });
}