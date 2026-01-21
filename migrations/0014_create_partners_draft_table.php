<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('partners_draft')) {
    Capsule::schema()->create('partners_draft', function (Blueprint $table) {
        $table->bigIncrements('partner_draft_idx');  // 기본 키
        $table->bigInteger('partner_idx')->unsigned()->nullable();  // 실제 파트너 ID
        $table->bigInteger('partner_sanha_idx')->unsigned()->nullable()->index();
        $table->bigInteger('partner_tl_idx')->unsigned()->nullable()->index();
        $table->bigInteger('partner_onda_idx')->unsigned()->nullable()->index();
        $table->bigInteger('partner_waug_idx')->unsigned()->nullable()->index();
        $table->string('partner_thirdparty', 30)->default('custom')->index();
        $table->string('partner_name', 50)->index();
        $table->string('partner_category', 30)->nullable()->index(); // stay, ticket, air
        $table->string('partner_type', 30)->nullable()->index(); // hotel, pension
        $table->string('partner_grade', 30)->nullable()->index(); // 성급 등
        $table->decimal('partner_charge', 5, 2)->default(12);
        $table->string('partner_country', 10)->default('KR')->index();
        $table->string('partner_zip', 10)->nullable()->index();
        $table->string('partner_origin_address1', 255)->nullable()->index();
        $table->string('partner_origin_address2', 255)->nullable()->index();
        $table->string('partner_origin_address3', 255)->nullable()->index();
        $table->string('partner_address1', 255)->nullable()->index();
        $table->string('partner_address2', 255)->nullable()->index();
        $table->string('partner_address3', 255)->nullable()->index();
        $table->string('partner_city', 255)->nullable()->index();
        $table->string('partner_region', 255)->nullable()->index();
        $table->string('partner_region_detail', 255)->nullable()->index();
        $table->decimal('partner_latitude', 10, 7)->nullable();
        $table->decimal('partner_longitude', 10, 7)->nullable();
        $table->string('partner_phonenumber', 50)->nullable()->index();
        $table->string('partner_email', 255)->nullable()->index();
        $table->string('partner_reservation_phonenumber', 50)->nullable()->index();
        $table->string('partner_reservation_email', 255)->nullable()->index();
        $table->string('partner_manager_phonenumber', 50)->nullable()->index();
        $table->string('partner_manager_email', 255)->nullable()->index();
        $table->string('partner_calculation_type', 30)->nullable()->index();
        $table->boolean('is_approved')->default(false)->index();  // 심사 상태 (false: 미승인, true: 승인됨)
        $table->timestamps();
    });
}