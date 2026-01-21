<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// 마이그레이션 실행
if (!Capsule::schema()->hasTable('partners') && Capsule::schema()->hasTable('partner_users')) {
    Capsule::schema()->create('partners', function (Blueprint $table) {
        $table->bigIncrements('partner_idx');
        $table->bigInteger('partner_detail_idx')->unsigned()->nullable();
        $table->bigInteger('partner_sanha_idx')->unsigned()->nullable()->unique();
        $table->bigInteger('partner_tl_idx')->unsigned()->nullable()->unique();
        $table->bigInteger('partner_onda_idx')->unsigned()->nullable()->unique();
        $table->bigInteger('partner_waug_idx')->unsigned()->nullable()->unique();
        $table->string('partner_thirdparty', 30)->default('custom')->index();
        $table->string('partner_name', 255)->index();
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
        $table->string('partner_status', 10)->default('disabled')->index();
        $table->integer('average_discount')->nullable();
        $table->integer('search_index')->default(0)->index();
        $table->boolean('image_curated')->default(false)->index();
        $table->timestamp('partner_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();
        $table->timestamp('partner_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();
    });
}
