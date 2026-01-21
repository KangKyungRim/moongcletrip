<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('benefit_item')) {
    Capsule::schema()->create('benefit_item', function (Blueprint $table) {
        $table->bigIncrements('benefit_item_idx');  // 기본 키
        $table->bigInteger('benefit_idx')->unsigned()->index();  // 혜택 외래 키
        $table->string('benefit_name', 255)->index();
        $table->bigInteger('item_idx')->unsigned()->index();  // 요금제 외래 키
        $table->string('item_type', 30)->index();  // 요금제 외래 키
        $table->timestamps();

        // 유니크 제약 조건: 같은 혜택과 요금제의 중복을 방지
        $table->unique(['benefit_idx', 'item_idx', 'item_type']);
        $table->index(['item_idx', 'item_type'], 'idx_item_idx_type_idx');

        // 외래 키 설정
        $table->foreign('benefit_idx')->references('benefit_idx')->on('benefits')->onDelete('cascade');
    });
}