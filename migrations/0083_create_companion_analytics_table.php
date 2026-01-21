<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('companion_analytics')) {
    Capsule::schema()->create('companion_analytics', function (Blueprint $table) {
        $table->bigIncrements('analytics_idx');

        $table->string('city_tag', 50)->nullable()->index();
        $table->integer('personnel')->default(1)->index();
        $table->string('companion_tag', 50)->nullable()->index();
        $table->integer('count')->default(0)->index();

        $table->timestamps();
    });
}