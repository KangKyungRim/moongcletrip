<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// 마이그레이션 실행
if (!Capsule::schema()->hasTable('benefits')) {
    Capsule::schema()->create('benefits', function (Blueprint $table) {
        $table->bigIncrements('benefit_idx');
        $table->string('benefit_name', 255)->index();
        $table->boolean('benefit_is_upcharge')->default(false)->index();
        $table->integer('benefit_upcharge')->default(0)->index();
        $table->boolean('benefit_recommend')->default(false)->index();
        $table->string('benefit_category', 30)->nullable()->index();
        $table->timestamp('benefit_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();
        $table->timestamp('benefit_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();
    });
}
