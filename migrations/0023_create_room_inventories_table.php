<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('room_inventories')) {
    Capsule::schema()->create('room_inventories', function (Blueprint $table) {
        $table->bigIncrements('inventory_idx');  // 기본 키
        $table->bigInteger('room_idx')->unsigned()->index();
        $table->bigInteger('rateplan_idx')->default(0)->unsigned()->index();
        $table->bigInteger('room_rateplan_idx')->nullable()->unsigned()->index();
        $table->date('inventory_date')->index();  // 재고가 적용되는 날짜
        $table->integer('inventory_quantity')->index();  // 해당 날짜에 판매 가능한 객실 수량
        $table->integer('inventory_sold_quantity')->default(0)->nullable();
        $table->boolean('is_closed')->default(0)->index();
        $table->timestamps();

        $table->index(['room_rateplan_idx', 'inventory_date'], 'idx_rateplan_date');
        $table->index(['rateplan_idx', 'inventory_date', 'inventory_quantity'], 'idx_rateplan_quantity_date');

        // 유니크 제약 조건: 같은 객실의 같은 날짜에 대한 재고는 한 번만 입력
        $table->unique(['room_idx', 'rateplan_idx', 'inventory_date']);
    });
}
