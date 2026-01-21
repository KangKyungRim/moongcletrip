<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('room_rateplan')) {
    Capsule::schema()->create('room_rateplan', function (Blueprint $table) {
        $table->bigIncrements('room_rateplan_idx');  // 기본 키
        $table->bigInteger('partner_idx')->unsigned();
        $table->bigInteger('room_idx')->unsigned();  // 객실 외래 키
        $table->bigInteger('rateplan_idx')->unsigned();  // 요금제 외래 키
        $table->bigInteger('rateplan_sanha_idx')->unsigned()->nullable()->index();
        $table->bigInteger('rateplan_tl_idx')->unsigned()->nullable()->index();
        $table->bigInteger('rateplan_onda_idx')->unsigned()->nullable()->index();
        $table->bigInteger('rateplan_waug_idx')->unsigned()->nullable()->index();
        $table->string('rateplan_thirdparty', 30)->default('custom')->index();
        $table->string('room_rateplan_status', 10)->default('enabled')->index();
        $table->timestamps();  // 생성 시각, 수정 시각

        // 유니크 제약 조건: 같은 객실과 요금제의 중복을 방지
        $table->unique(['room_idx', 'rateplan_idx']);

        // 외래 키 설정
        $table->foreign('room_idx')->references('room_idx')->on('rooms')->onDelete('cascade');
        $table->foreign('rateplan_idx')->references('rateplan_idx')->on('rateplans')->onDelete('cascade');
    });
}
