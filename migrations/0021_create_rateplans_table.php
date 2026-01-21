<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('rateplans')) {
    Capsule::schema()->create('rateplans', function (Blueprint $table) {
        $table->bigIncrements('rateplan_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('partner_idx')->unsigned()->index();
        $table->bigInteger('rateplan_sanha_idx')->unsigned()->nullable()->index();
        $table->bigInteger('rateplan_tl_idx')->unsigned()->nullable()->index();
        $table->bigInteger('rateplan_onda_idx')->unsigned()->nullable()->index();
        $table->bigInteger('rateplan_waug_idx')->unsigned()->nullable()->index();
        $table->string('rateplan_thirdparty', 30)->default('custom')->index();
        $table->integer('rateplan_order')->default(0)->index();
        $table->string('rateplan_name', 255);  // 요금제 이름
        $table->string('rateplan_type', 30);
        $table->text('rateplan_description')->nullable();  // 요금제 설명
        $table->integer('rateplan_stay_min')->nullable()->index();
        $table->integer('rateplan_stay_max')->nullable()->index();
        $table->timestamp('rateplan_sales_from')->nullable()->index();
        $table->timestamp('rateplan_sales_to')->nullable()->index();
        $table->integer('rateplan_cutoff_days')->nullable()->index();
        $table->boolean('rateplan_is_refundable')->default(true)->index();
        $table->boolean('rateplan_has_breakfast')->default(false)->index();
        $table->boolean('rateplan_has_lunch')->default(false)->index();
        $table->boolean('rateplan_has_dinner')->default(false)->index();
        $table->boolean('rateplan_meal_count')->default(0)->index();
        $table->string('rateplan_status', 10)->default('enabled')->index();  // 요금제 활성화 여부
        $table->timestamp('rateplan_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 생성 시각
        $table->timestamp('rateplan_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 수정 시각

        // 외래 키 설정
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('cascade');
    });
}
