<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('faqs')) {
    Capsule::schema()->create('faqs', function (Blueprint $table) {
        $table->bigIncrements('faq_idx');  // FAQ ID (기본 키)
        $table->string('question', 255);  // 질문
        $table->text('answer');  // 답변
        $table->string('category', 100)->nullable()->index();  // FAQ 카테고리
        $table->string('status', 50)->default('active')->index();  // FAQ 상태 (active, inactive)
        $table->timestamps();  // 생성 시각, 수정 시각
    });
}