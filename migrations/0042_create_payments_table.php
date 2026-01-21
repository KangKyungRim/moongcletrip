<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('payments')) {
    Capsule::schema()->create('payments', function (Blueprint $table) {
        $table->bigIncrements('payment_idx');  // 결제 ID (기본 키)
        $table->bigInteger('travel_cart_idx')->unsigned()->nullable()->nullable()->index();  // 결제된 장바구니 ID
        $table->bigInteger('user_idx')->unsigned()->nullable()->index();  // 결제한 유저 ID
        $table->string('payment_password', 255)->nullable();
        $table->string('payment_unique_code', 255)->unique();

        $table->decimal('payment_total_amount', 14, 2);  // 결제 총액
        $table->decimal('payment_sale_amount', 14, 2);  // 결제 총액
        $table->decimal('used_point_amount', 14, 2)->default(0);  // 사용한 포인트 금액
        $table->string('payment_status', 50)->default('pending')->index();  // 결제 상태 (pending, completed, failed, canceled, partial_canceled)
        $table->string('payment_method', 50)->default('TOSS')->index();  // 결제 방식 (TOSS, KGM 등)
        $table->string('payment_name', 255);
        $table->string('payment_key', 100)->nullable()->index();  // 결제 고유 키
        $table->string('payment_type', 20)->default('NORMAL')->index();  // 결제 유형 (예: NORMAL, RECURRING)
        $table->string('payment_order_id', 100)->nullable()->index();  // 주문 ID
        $table->decimal('payment_amount', 14, 2)->nullable()->index();  // 결제 금액
        $table->string('payment_error_code', 50)->nullable()->index();  // 결제 오류 코드
        $table->text('payment_error_message')->nullable();  // 결제 오류 메시지

        $table->string('reservation_name', 100);  // 예약자 이름
        $table->string('reservation_phone', 50);  // 예약자 전화번호
        $table->string('reservation_email', 100);  // 예약자 이메일
        $table->string('visit_name', 100);  // 이용자 이름
        $table->string('visit_phone', 50);  // 이용자 전화번호
        $table->string('visit_email', 100);  // 이용자 이메일
        $table->string('visit_way', 50)->nullable();  // 방문 수단 (예: 자가용, 대중교통 등)

        // 쿠폰 관련 필드 추가
        $table->bigInteger('coupon_user_idx')->unsigned()->nullable()->index();  // 적용된 쿠폰 (유저 쿠폰 ID)
        $table->decimal('coupon_discount_amount', 14, 2)->nullable();  // 쿠폰 할인 금액
        $table->string('coupon_name', 255)->nullable();

        $table->decimal('refund_amount', 14, 2)->default(0);
        $table->decimal('refund_point_amount', 14, 2)->default(0);

        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('travel_cart_idx')->references('travel_cart_idx')->on('travel_carts')->onDelete('set null');
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('set null');
        $table->foreign('coupon_user_idx')->references('coupon_user_idx')->on('coupon_user')->onDelete('set null');
    });
}
