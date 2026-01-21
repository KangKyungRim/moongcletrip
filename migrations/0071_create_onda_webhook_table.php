<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// 마이그레이션 실행
if (!Capsule::schema()->hasTable('onda_webhook')) {
    Capsule::schema()->create('onda_webhook', function (Blueprint $table) {
        $table->bigIncrements('onda_webhook_idx');
        $table->string('event_type', 30)->index();
        $table->json('event_detail');
        $table->timestamp('event_timestamp')->index();
        $table->string('event_progress_status', 30)->index(); // pending, progress, completed, error
        $table->timestamps();

        $table->index(['created_at', 'updated_at']);
    });
}
