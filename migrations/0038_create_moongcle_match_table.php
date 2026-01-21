<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcle_match')) {
    Capsule::schema()->create('moongcle_match', function (Blueprint $table) {
        $table->bigIncrements('moongcle_match_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('moongcledeal_idx')->unsigned()->index();
        $table->bigInteger('product_idx')->unsigned()->index();
        $table->string('moongcle_match_category', 50)->index();  // 연결된 모델의 카테고리 (moongcleoffer, moongclebundle 등)
        $table->boolean('moongcle_match_is_read')->default(false)->index();  // 유저가 읽었는지 여부
        $table->boolean('moongcle_match_is_detailed_view')->default(false)->index();  // 상세 내용을 봤는지 여부
        $table->string('moongcle_match_status', 10)->default('enabled')->index();  // 활성화 여부
        $table->string('notification_status', 10)->default('pending')->index();
        $table->timestamp('notification_time')->nullable()->index();
        $table->integer('match_score')->nullable()->index();
        $table->timestamp('processing_at')->nullable()->index();
        $table->timestamps();  // 생성 시각, 수정 시각

        // 유니크 제약 조건: 같은 딜과 태그의 중복을 방지
        $table->unique(['moongcledeal_idx', 'product_idx', 'moongcle_match_category'], 'moongcle_product_match');

        // 외래 키 설정
        $table->foreign('moongcledeal_idx')->references('moongcledeal_idx')->on('moongcledeals')->onDelete('cascade');
    });
}
