<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('review_comment_reports')) {
    Capsule::schema()->create('review_comment_reports', function (Blueprint $table) {
        $table->bigIncrements('comment_report_idx');  // 신고 ID
        $table->bigInteger('comment_idx')->unsigned()->index();  // 신고된 댓글 ID
        $table->bigInteger('user_idx')->unsigned()->index();  // 신고를 한 유저 ID
        $table->text('report_reason');  // 신고 이유
        $table->string('status', 50)->default('pending')->index();  // 처리 상태 (예: pending, resolved)
        $table->timestamps();  // 생성 시각 및 수정 시각

        // 외래 키 설정
        $table->foreign('comment_idx')->references('comment_idx')->on('review_comments')->onDelete('cascade');
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}