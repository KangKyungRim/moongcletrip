<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcleoffer_bundle')) {
    Capsule::schema()->create('moongcleoffer_bundle', function (Blueprint $table) {
        $table->bigIncrements('moongcleoffer_bundle_idx');  // 기본 키
        $table->bigInteger('moongcleoffer_idx')->unsigned();
        $table->bigInteger('moongclebundle_idx')->unsigned();
        $table->timestamps();  // 생성 시각, 수정 시각

        $table->unique(['moongcleoffer_idx', 'moongclebundle_idx']);

        $table->foreign('moongcleoffer_idx')->references('moongcleoffer_idx')->on('moongcleoffers')->onDelete('cascade');
        $table->foreign('moongclebundle_idx')->references('moongclebundle_idx')->on('moongclebundles')->onDelete('cascade');
    });
}
