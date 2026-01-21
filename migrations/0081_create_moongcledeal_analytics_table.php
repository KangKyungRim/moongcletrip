<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcledeal_analytics')) {
    Capsule::schema()->create('moongcledeal_analytics', function (Blueprint $table) {
        $table->bigIncrements('moongcledeal_analytic_idx');

        // 날짜 관련
        $table->string('month_key', 7)->nullable()->index();

        // 인원 및 동행 조건
        $table->integer('personnel')->default(1)->index();
        $table->string('companion_tag', 50)->nullable();

        // 반려동물 관련
        $table->string('pet_size_tag', 50)->nullable()->index();
        $table->string('pet_weight_tag', 50)->nullable()->index();
        $table->string('pet_count_tag', 50)->nullable()->index();

        // 지역
        $table->string('city_tag', 50)->nullable()->index();

        // 선호 태그들
        $table->string('taste_tags', 1024)->nullable()->index();

        // 원본 JSON
        $table->json('search_json')->nullable();

        $table->dateTime('created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
        $table->dateTime('updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'));
    });
}