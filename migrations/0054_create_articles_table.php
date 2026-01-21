<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('articles')) {
    Capsule::schema()->create('articles', function (Blueprint $table) {
        $table->bigIncrements('article_idx');  // 아티클 ID (기본 키)
        $table->string('title', 255);  // 아티클 제목
        $table->text('article_body');  // 아티클 내용
        $table->string('article_thumbnail_path', 255)->nullable();
        $table->string('article_button_name', 255)->nullable();
        $table->string('article_button_link', 255)->nullable();
        $table->string('status', 50)->default('active')->index();  // 아티클 상태 (active, inactive)
        $table->timestamps();  // 생성 시각, 수정 시각
        $table->timestamp('published_at')->nullable()->index();  // 게시 시각
    });
}