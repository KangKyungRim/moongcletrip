<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('terms')) {
    Capsule::schema()->create('terms', function (Blueprint $table) {
        $table->bigIncrements('term_idx');  // 약관 ID (기본 키)
        $table->string('title', 255);  // 약관 제목
        $table->text('term_body');  // 약관 내용
        $table->string('category', 100)->nullable()->index();  // 약관 카테고리 (이용약관, 개인정보처리방침 등)
        $table->string('status', 50)->default('active')->index();  // 약관 상태 (active, inactive)
        $table->timestamps();  // 생성 시각, 수정 시각
        $table->timestamp('published_at')->nullable()->index();  // 게시 시각
    });
}