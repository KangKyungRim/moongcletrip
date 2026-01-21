<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('notices')) {
    Capsule::schema()->create('notices', function (Blueprint $table) {
        $table->bigIncrements('notice_idx');  // 공지사항 ID (기본 키)
        $table->string('title', 255);  // 공지 제목
        $table->text('notice_body');  // 공지 내용
        $table->string('status', 50)->default('active')->index();  // 공지 상태 (active, inactive)
        $table->timestamps();  // 생성 시각, 수정 시각
        $table->timestamp('published_at')->nullable()->index();  // 게시 시각
    });
}