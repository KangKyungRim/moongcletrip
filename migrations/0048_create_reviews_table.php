<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('reviews')) {
    Capsule::schema()->create('reviews', function (Blueprint $table) {
        $table->bigIncrements('review_idx');  // 리뷰 ID (기본 키)
        $table->bigInteger('user_idx')->unsigned()->index();  // 리뷰 작성자 (유저 ID)
        $table->bigInteger('partner_idx')->unsigned()->index();
        $table->string('partner_name', 255)->index();
        $table->bigInteger('moongcledeal_idx')->unsigned();  // 뭉클딜 외래 키
        $table->bigInteger('product_idx')->unsigned()->index();  // 리뷰 대상 ID (moongcleoffer, room_price, moongclebundle)
        $table->bigInteger('payment_item_idx')->unsigned()->index();
        $table->string('review_category', 50)->index();  // 상품 카테고리 (moongcleoffer, room_price, moongclebundle)
        $table->decimal('rating', 2, 1);  // 평점 (예: 4.5)
        $table->text('review_content');  // 리뷰 내용
        $table->boolean('is_active')->default(true);  // 리뷰 활성화 여부 (삭제된 리뷰는 false)
        $table->timestamps();  // 작성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}
