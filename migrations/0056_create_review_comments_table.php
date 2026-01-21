<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('review_comments')) {
    Capsule::schema()->create('review_comments', function (Blueprint $table) {
        $table->bigIncrements('comment_idx');  // 댓글 ID (기본 키)
        $table->bigInteger('review_idx')->unsigned()->index();  // 리뷰 ID
        $table->bigInteger('user_idx')->unsigned()->index();  // 댓글 작성자 (유저 ID)
        $table->text('comment_body');  // 댓글 내용
        $table->bigInteger('parent_comment_idx')->unsigned()->nullable()->index();  // 부모 댓글 ID (대댓글일 경우)
        $table->string('status', 50)->default('active')->index();  // 댓글 상태 (active, deleted 등)
        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('review_idx')->references('review_idx')->on('reviews')->onDelete('cascade');
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}