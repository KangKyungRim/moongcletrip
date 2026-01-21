<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('coupons')) {
    Capsule::schema()->create('coupons', function (Blueprint $table) {
        $table->bigIncrements('coupon_idx');  // 쿠폰 ID (기본 키)
        $table->string('coupon_name', 255);  // 쿠폰명
        $table->string('coupon_code', 100)->unique();  // 쿠폰 코드 (유니크)
        $table->string('coupon_type', 30)->default('discount')->index();  // 쿠폰 유형 (할인권, 혜택 추가) 'discount', 'benefit'
        $table->decimal('discount_amount', 10, 2)->nullable()->index();  // 할인가 (할인권일 경우)
        $table->decimal('minimum_order_price', 10, 2)->nullable();  // 최소 적용 가능 가격
        $table->integer('total_issuance')->unsigned();  // 총 발행 수
        $table->integer('download_count')->default(0);  // 다운로드 수
        $table->integer('used_count')->default(0);  // 사용된 수
        $table->boolean('is_active')->default(true)->index();  // 활성화 여부
        $table->boolean('show_in_coupon_wallet')->default(true)->index();  // 쿠폰함에 표시 여부

        // 적용 가능한 파트너 (전체 적용 또는 특정 파트너)
        $table->bigInteger('partner_idx')->unsigned()->nullable();  // 파트너 ID (nullable이면 전체 적용)

        $table->date('start_date')->nullable();  // 사용 가능 시작일
        $table->date('end_date')->nullable();  // 사용 가능 종료일

        $table->timestamps();  // 생성 및 수정 시각

        // 외래 키 설정 (파트너와의 관계)
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('set null');
    });
}