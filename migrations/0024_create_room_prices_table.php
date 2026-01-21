<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('room_prices')) {
    Capsule::schema()->create('room_prices', function (Blueprint $table) {
        $table->bigIncrements('room_price_idx');  // 가격 기본 키
        $table->bigInteger('room_idx')->unsigned();  // 객실 외래 키
        $table->bigInteger('rateplan_idx')->unsigned();  // 요금제 외래 키
        $table->bigInteger('room_rateplan_idx')->unsigned();
        $table->date('room_price_date');  // 가격이 적용되는 날짜
        $table->decimal('room_price_basic', 14, 2);  // 가격
        $table->decimal('room_price_sale', 14, 2);  // 할인 가격
        $table->decimal('room_price_sale_percent', 5, 2)->nullable();
        $table->string('room_price_currency', 10)->default('KRW');
        $table->string('room_price_promotion_type', 30)->nullable()->index();
        $table->integer('room_price_additional_adult')->nullable()->index();
        $table->integer('room_price_additional_child')->nullable()->index();
        $table->integer('room_price_additional_tiny')->nullable()->index();
        $table->time('room_price_checkin')->nullable()->index();
        $table->time('room_price_checkout')->nullable()->index();
        $table->integer('room_price_stay_min')->nullable()->index();
        $table->integer('room_price_stay_max')->nullable()->index();
        $table->boolean('is_closed')->default(0)->index();
        $table->timestamps();

        // 유니크 제약 조건: 객실, 요금제, 날짜 조합이 중복되지 않도록 설정
        $table->unique(['room_idx', 'rateplan_idx', 'room_price_date'], 'idx_room_id_price_type');
        $table->unique(['room_rateplan_idx', 'room_price_date'], 'idx_roomrate_id_price_type');
    });
}
