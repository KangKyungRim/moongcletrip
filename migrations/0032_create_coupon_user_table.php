<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('coupon_user')) {
    Capsule::schema()->create('coupon_user', function (Blueprint $table) {
        $table->bigIncrements('coupon_user_idx');  // 유저가 보유한 쿠폰 ID (기본 키)
        $table->bigInteger('coupon_idx')->unsigned();  // 쿠폰 ID (coupons 테이블과의 외래 키)
        $table->bigInteger('user_idx')->unsigned();  // 유저 ID (users 테이블과의 외래 키)
        $table->string('coupon_name', 255);  // 쿠폰명
        $table->string('coupon_code', 100)->nullable()->index();  // 쿠폰 코드
        $table->string('coupon_type', 30)->default('discount')->index();  // 쿠폰 유형 (할인권, 혜택 추가) 'discount', 'benefit'
        $table->decimal('discount_amount', 10, 2)->nullable()->index();  // 할인가 (할인권일 경우)
        $table->decimal('minimum_order_price', 10, 2)->nullable();  // 최소 적용 가능 가격
        $table->boolean('is_used')->default(false);  // 사용 여부
        $table->boolean('is_active')->default(true)->index();  // 활성화 여부
        $table->date('start_date')->nullable();  // 사용 가능 시작일
        $table->date('end_date')->nullable();  // 사용 가능 종료일
        $table->timestamp('used_at')->nullable();  // 사용 시각

        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('coupon_idx')->references('coupon_idx')->on('coupons')->onDelete('cascade');
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('cascade');
    });
}
