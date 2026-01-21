<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('review_tags')) {
    Capsule::schema()->create('review_tags', function (Blueprint $table) {
        $table->bigIncrements('review_tag_idx');  // 기본 키
        $table->bigInteger('tag_idx')->unsigned()->index();  // 태그 외래 키
        $table->string('tag_name', 255)->index();
        $table->string('tag_machine_name', 255)->index();
        $table->bigInteger('review_idx')->unsigned()->index();
        $table->integer('tag_order')->default(0)->unsigned()->index();
        $table->timestamps();

        // 외래 키 설정
        $table->foreign('tag_idx')->references('tag_idx')->on('moongcle_tags')->onDelete('cascade');
    });
}