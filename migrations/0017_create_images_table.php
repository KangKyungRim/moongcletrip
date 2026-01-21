<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('images')) {
    Capsule::schema()->create('images', function (Blueprint $table) {
        $table->bigIncrements('image_idx');  // 기본 키
        $table->bigInteger('image_entity_id')->unsigned();  // 엔티티 ID (파트너, 스테이, 객실 등)
        $table->string('image_entity_type', 50)->index();  // 엔티티 유형 (partner, stay, room 등)
        $table->string('image_type', 30)->index();  // 이미지 유형 (main, sub 등)
        $table->string('image_origin_name', 255);  // 원본 이미지 이름
        $table->string('image_origin_path', 255);  // 원본 이미지 경로
        $table->string('image_small_path', 255)->nullable();  // 250px 이미지 경로
        $table->string('image_normal_path', 255)->nullable();  // 500px 크기 이미지 경로
        $table->string('image_big_path', 255)->nullable();  // 1000px 크기 이미지 경로
        $table->integer('image_origin_size')->nullable();
        $table->integer('image_order')->nullable();
        $table->timestamp('image_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 생성 시각
        $table->timestamp('image_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 수정 시각

        $table->index(['image_entity_id', 'image_entity_type'], 'idx_entityid_type');
        $table->index(['image_entity_id', 'image_entity_type', 'image_order'], 'idx_idx_type_order_index');
        $table->index(['image_entity_id', 'image_entity_type', 'image_order', 'image_normal_path'], 'idx_entity_id_normal_type');
        $table->index(['image_entity_id', 'image_entity_type', 'image_order', 'image_big_path'], 'idx_entity_id_big_type');
    });
}
