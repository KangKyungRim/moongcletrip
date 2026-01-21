<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('cart_items')) {
    Capsule::schema()->create('cart_items', function (Blueprint $table) {
        $table->bigIncrements('cart_item_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('travel_cart_idx')->unsigned()->index();  // 장바구니 외래 키
        $table->bigInteger('product_idx')->unsigned()->index();  // 상품 외래 키 (offer, room_price, bundle 등)
        $table->string('product_category', 50)->index();  // 상품 카테고리 (moongcleoffer, room_price, moongclebundle)

        $table->integer('quantity')->unsigned()->default(1);  // 상품 수량
        $table->timestamps();  // 생성 시각, 수정 시각

        // 유니크 제약 조건: 같은 장바구니, 같은 상품 중복 방지
        $table->unique(['travel_cart_idx', 'product_idx', 'product_category']);

        // 외래 키 설정
        $table->foreign('travel_cart_idx')->references('travel_cart_idx')->on('travel_carts')->onDelete('cascade');
    });
}