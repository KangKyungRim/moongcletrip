<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('review_partner')) {
    Capsule::schema()->create('review_partner', function (Blueprint $table) {
        $table->bigIncrements('review_partner_idx');  // 기본 키
        $table->bigInteger('review_idx')->unsigned()->index();  // 리뷰 ID
        $table->bigInteger('partner_idx')->unsigned()->index();  // 파트너 ID

        // 유니크 제약 조건: 동일한 리뷰와 파트너의 중복 연결을 방지
        $table->unique(['review_idx', 'partner_idx']);

        // 외래 키 설정
        $table->foreign('review_idx')->references('review_idx')->on('reviews')->onDelete('cascade');
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('cascade');
    });
}