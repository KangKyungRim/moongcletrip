<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('payment_items')) {
    Capsule::schema()->create('payment_items', function (Blueprint $table) {
        $table->bigIncrements('payment_item_idx');  // 기본 키
        $table->bigInteger('user_idx')->unsigned()->nullable()->index();
        $table->bigInteger('payment_idx')->unsigned()->nullable()->index();  // 결제 외래 키
        $table->bigInteger('cart_item_idx')->unsigned()->nullable()->index();  // 결제된 장바구니 아이템

        $table->bigInteger('partner_idx')->unsigned()->nullable()->index();
        $table->decimal('payment_partner_charge', 5, 2)->default(12);
        $table->bigInteger('product_idx')->unsigned()->index();  // 상품 외래 키 (offer, room_rateplan, bundle 등)
        $table->string('product_category', 50)->index();  // 상품 카테고리 stay / air
        $table->string('product_type', 50)->index();  // 상품 타입 (moongcleoffer, room_rateplan, moongclebundle)
        $table->string('product_name', 255)->index();
        $table->string('product_partner_name', 255)->index();
        $table->string('product_detail_name', 255)->nullable();
        $table->json('product_benefits')->nullable(); // 혜택 등 json 형태로
        $table->json('datewise_product_data')->nullable(); // Rateplan Price 등 날짜별 금액 및 ID 저장

        $table->decimal('item_basic_price', 14, 2);  // 아이템 가격
        $table->decimal('item_sale_price', 14, 2);  // 아이템 가격
        $table->decimal('item_origin_sale_price', 14, 2);  // 아이템 가격
        $table->integer('quantity')->unsigned();  // 아이템 수량

        // 예약 관련 코드 필드 추가
        $table->string('reservation_number', 30)->nullable()->index();  // 예약 예정 코드
        $table->string('reservation_pending_code', 50)->nullable();  // 예약 예정 코드
        $table->string('reservation_confirmed_code', 50)->nullable();  // 예약 확정 코드

        $table->timestamp('start_date')->nullable(); // 사용 시작일
        $table->timestamp('end_date')->nullable(); // 사용 완료일
        $table->timestamp('free_cancel_date')->nullable(); // 무료 취소 가능일
        $table->boolean('refundable')->default(true)->index(); // 취소 가능 여부
        
        $table->json('reservation_personnel')->nullable(); // 성인, 아동, 유아 등 선택한 인원이 존재할 경우

        $table->string('payment_status', 50)->nullable(); // 상태 pending / paid / canceled
        $table->string('reservation_status', 50)->nullable(); // 상태 pending / confirmed / canceled / completed

        $table->string('thirdparty_type', 30)->default('custom')->index();

        // 취소 관련 필드 추가
        $table->json('refund_policy')->nullable();
        $table->integer('canceled_quantity')->default(0);  // 취소된 수량
        $table->decimal('canceled_amount', 14, 2)->default(0);  // 취소된 금액
        $table->string('canceled_reason', 255)->nullable();  // 취소된 금액

        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('set null');
        $table->foreign('payment_idx')->references('payment_idx')->on('payments')->onDelete('set null');
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('set null');
        $table->foreign('cart_item_idx')->references('cart_item_idx')->on('cart_items')->onDelete('set null');
    });
}
