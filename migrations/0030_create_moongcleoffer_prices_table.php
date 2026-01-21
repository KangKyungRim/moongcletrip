<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcleoffer_prices')) {
    Capsule::schema()->create('moongcleoffer_prices', function (Blueprint $table) {
        $table->bigIncrements('moongcleoffer_price_idx');  // 기본 키
        $table->bigInteger('moongcleoffer_idx')->unsigned()->index();  // 딜 외래 키
        $table->bigInteger('room_idx')->nullable()->unsigned();
        $table->bigInteger('rateplan_idx')->nullable()->unsigned();
        $table->bigInteger('room_rateplan_idx')->nullable()->unsigned();
        $table->bigInteger('base_idx')->nullable()->unsigned()->index();
        $table->string('base_type', 30)->nullable()->index();
        $table->date('moongcleoffer_price_date')->index();  // 가격이 적용되는 날짜
        $table->decimal('moongcleoffer_price_basic', 14, 2)->index();  // 가격
        $table->decimal('moongcleoffer_price_sale', 14, 2)->index();
        $table->decimal('moongcleoffer_discount_rate', 5, 2)->default(0)->index();  // 할인율 (예: 10.00%)
        $table->boolean('is_closed')->default(0)->index();
        $table->timestamps();  // 생성 시각, 수정 시각

        // 유니크 제약 조건: 딜, 날짜 조합이 중복되지 않도록 설정
        $table->unique(['moongcleoffer_idx', 'moongcleoffer_price_date'], 'offer_price_unique');
        $table->unique(['moongcleoffer_idx', 'base_idx', 'base_type'], 'moongcleoffer_prices_base_idx_type_idx');

        // 외래 키 설정
        $table->foreign('moongcleoffer_idx')->references('moongcleoffer_idx')->on('moongcleoffers')->onDelete('cascade');
    });
}
