<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcleoffer_benefit')) {
    Capsule::schema()->create('moongcleoffer_benefit', function (Blueprint $table) {
        $table->bigIncrements('moongcleoffer_benefit_idx');  // 기본 키
        $table->bigInteger('moongcleoffer_idx')->unsigned()->index();  // 뭉클딜 외래 키
        $table->bigInteger('benefit_idx')->unsigned()->index();  // 혜택 외래 키
        $table->timestamps();

        // 유니크 제약 조건: 같은 딜과 혜택의 중복을 방지
        $table->unique(['moongcleoffer_idx', 'benefit_idx']);

        // 외래 키 설정
        $table->foreign('moongcleoffer_idx')->references('moongcleoffer_idx')->on('moongcleoffers')->onDelete('cascade');
        $table->foreign('benefit_idx')->references('benefit_idx')->on('benefits')->onDelete('cascade');
    });
}