<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('review_reports')) {
    Capsule::schema()->create('review_reports', function (Blueprint $table) {
        $table->bigIncrements('report_idx');  // 신고 ID
        $table->bigInteger('review_idx')->unsigned()->index();  // 신고된 리뷰 ID
        $table->bigInteger('partner_idx')->unsigned()->index();  // 신고를 한 파트너 ID
        $table->text('report_reason');  // 신고 이유
        $table->string('status', 50)->default('pending')->index();  // 처리 상태 (예: pending, resolved)
        $table->timestamps();  // 생성 시각 및 수정 시각

        // 외래 키 설정
        $table->foreign('review_idx')->references('review_idx')->on('reviews')->onDelete('cascade');
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('cascade');
    });
}