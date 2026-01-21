<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('rooms_draft')) {
    Capsule::schema()->create('rooms_draft', function (Blueprint $table) {
        $table->bigIncrements('rooms_draft_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('room_idx')->unsigned()->nullable();
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
        $table->boolean('room_is_approved')->default(true)->index();
        $table->timestamps();
    });
}
