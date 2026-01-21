<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcleoffers')) {
    Capsule::schema()->create('moongcleoffers', function (Blueprint $table) {
        $table->bigIncrements('moongcleoffer_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('stay_moongcleoffer_idx')->unsigned()->index();
        $table->bigInteger('partner_idx')->unsigned()->index();
        $table->bigInteger('room_idx')->nullable()->unsigned()->index();
        $table->bigInteger('rateplan_idx')->nullable()->unsigned()->index();
        $table->bigInteger('base_product_idx')->unsigned()->index();  // 상품의 외래 키
        $table->string('moongcleoffer_category', 30)->index();  // 상품 카테고리 (예: roomRateplan, ticket, air)
        $table->bigInteger('moongcle_point_idx')->unsigned()->nullable()->index();  // Moongcle Points ID (nullable)
        $table->string('moongcleoffer_status', 10)->default('disabled')->index();  // 딜 활성화 여부
        $table->decimal('minimum_discount', 5, 2)->default(0)->index();
        $table->integer('moongcleoffer_attractive')->default(0)->index();
        $table->timestamps();  // 생성 시각, 수정 시각

        // 인덱스 설정 (상품 ID와 카테고리 조합에 대한 인덱스)
        $table->index(['base_product_idx', 'moongcleoffer_category']);
        $table->index(['moongcleoffer_status', 'moongcleoffer_category']);
        $table->index(['base_product_idx', 'moongcleoffer_category', 'moongcleoffer_status'], 'base_idx_category_index');

        $table->foreign('moongcle_point_idx')->references('moongcle_point_idx')->on('moongcle_points')->onDelete('set null');
    });
}
