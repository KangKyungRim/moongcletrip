<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('partner_faq')) {
    Capsule::schema()->create('partner_faq', function (Blueprint $table) {
        $table->bigIncrements('faq_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('partner_idx')->nullable()->unsigned()->index();
        $table->text('question')->nullable()->index();
        $table->text('answer')->nullable()->index();
        $table->integer('faq_order')->default(0)->nullable()->index();
        $table->timestamps();
    });
}
