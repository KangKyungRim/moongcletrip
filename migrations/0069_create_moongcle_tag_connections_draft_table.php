<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcle_tag_connections_draft')) {
    Capsule::schema()->create('moongcle_tag_connections_draft', function (Blueprint $table) {
        $table->bigIncrements('tag_connection_idx');  // 기본 키
        $table->bigInteger('tag_idx')->unsigned()->index();  // 태그 외래 키
        $table->string('tag_name', 255)->index();
        $table->string('tag_machine_name', 255)->index();
        $table->bigInteger('item_idx')->unsigned()->index();  // 연결된 모델의 ID (benefit_idx, rateplan_idx 등)
        $table->string('item_type', 50)->index();  // 연결된 모델 (benefit, rateplan 등)
        $table->string('connection_type', 50)->nullable()->index();
        $table->string('connection_subtype', 50)->nullable()->index();
        $table->string('editor', 30)->nullable()->index();
        $table->boolean('is_curated')->default(false)->index();
        $table->boolean('is_approved')->index();
        $table->timestamps();

        // 유니크 제약 조건: 같은 태그, 카테고리, 아이템 조합의 중복을 방지
        // $table->unique(['tag_idx', 'item_idx', 'tag_connection_category'], 'tag_connection_draft');

        // 외래 키 설정
        $table->foreign('tag_idx')->references('tag_idx')->on('moongcle_tags')->onDelete('cascade');
    });
}
