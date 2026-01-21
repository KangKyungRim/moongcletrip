<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('curated_tags')) {
    Capsule::schema()->create('curated_tags', function (Blueprint $table) {
        $table->bigIncrements('curated_tag_idx');  // 기본 키
        $table->bigInteger('tag_idx')->unsigned()->index();  // 태그 외래 키
        $table->string('tag_name', 255)->index();
        $table->string('tag_machine_name', 255)->index();
        $table->bigInteger('item_idx')->unsigned()->index();  // 연결된 모델의 ID (moongcleoffer_idx, moongclebundle_idx 등)
        $table->string('item_type', 50)->index();  // 연결된 모델 (moongcleoffer, moongclebundle 등)
        $table->timestamps();

        $table->index(['item_idx', 'item_type'], 'idx_item_idx_type_idx');

        // 외래 키 설정
        $table->foreign('tag_idx')->references('tag_idx')->on('moongcle_tags')->onDelete('cascade');
    });
}