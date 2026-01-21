<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('rooms')) {
    Capsule::schema()->create('rooms', function (Blueprint $table) {
        $table->bigIncrements('room_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('partner_idx')->unsigned()->nullable();
        $table->bigInteger('room_sanha_idx')->unsigned()->nullable()->index();
        $table->bigInteger('room_tl_idx')->unsigned()->nullable()->index();
        $table->bigInteger('room_onda_idx')->unsigned()->nullable()->index();
        $table->bigInteger('room_waug_idx')->unsigned()->nullable()->index();
        $table->string('room_thirdparty', 30)->default('custom')->index();
        $table->integer('room_order')->default(0)->index();
        $table->string('room_name', 255);
        $table->json('room_bed_type')->nullable();
        $table->json('room_details')->nullable();
        $table->integer('room_size')->nullable()->index();
        $table->integer('room_standard_person')->default(2)->index();
        $table->integer('room_max_person')->default(2)->index();
        $table->integer('room_adult_additional_price')->default(0)->index();
        $table->integer('room_child_additional_price')->default(0)->index();
        $table->integer('room_tiny_additional_price')->default(0)->index();
        $table->integer('room_child_age')->nullable()->index();
        $table->integer('room_tiny_month')->nullable()->index();
        $table->text('room_amenity')->nullable();
        $table->text('room_barrierfree')->nullable();
        $table->text('room_other_notes')->nullable();
        $table->string('room_status', 10)->default('disabled')->index();
        $table->timestamp('room_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 생성 시각
        $table->timestamp('room_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();  // 수정 시각

        // 복합 인덱스 추가
        $table->index(['partner_idx', 'room_status'], 'idx_partner_status');
        $table->index(['room_order', 'room_idx'], 'idx_order_idx');

        // 외래 키 설정
        $table->foreign('partner_idx')->references('partner_idx')->on('partners')->onDelete('cascade');
    });
}
