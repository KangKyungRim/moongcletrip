<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcledeal_tag')) {
    Capsule::schema()->create('moongcledeal_tag', function (Blueprint $table) {
        $table->bigIncrements('moongcledeal_tag_idx');  // 기본 키
        $table->bigInteger('moongcledeal_idx')->unsigned();  // 뭉클딜 외래 키
        $table->bigInteger('tag_idx')->unsigned();  // 태그 외래 키
        $table->integer('tag_order')->default(999)->unsigned();  // 태그 순서
        $table->string('status', 30)->nullable()->index();  // 태그 순서
        $table->timestamps();

        // 유니크 제약 조건: 같은 딜과 태그의 중복을 방지
        $table->unique(['moongcledeal_idx', 'tag_idx']);

        // 외래 키 설정
        $table->foreign('moongcledeal_idx')->references('moongcledeal_idx')->on('moongcledeals')->onDelete('cascade');
        $table->foreign('tag_idx')->references('tag_idx')->on('tags')->onDelete('cascade');
    });
}
