<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// 마이그레이션 실행
if (!Capsule::schema()->hasTable('users')) {
    Capsule::schema()->create('users', function (Blueprint $table) {
        $table->bigIncrements('user_idx');
        $table->boolean('user_is_guest')->default(true)->index();
        $table->string('user_id', 255)->unique()->nullable()->index();
        $table->string('user_nickname', 50)->index();
        $table->string('user_password', 255)->nullable();
        $table->string('user_name', 100)->nullable()->index();
        $table->string('user_email', 100)->nullable()->index();
        $table->string('user_phone_number', 50)->nullable()->index();
        $table->string('user_login_type', 10)->nullable()->index(); // kakao, email, naver, apple, google
        $table->string('user_status', 10)->nullable()->index(); // prepare, normal, stop, block, delete
        $table->integer('user_heartbeat')->default(50);
        $table->integer('user_level')->default(2)->index();
        $table->string('user_app_version', 10)->nullable()->index();
        $table->integer('user_points')->default(0);
        $table->timestamp('user_birthday')->nullable()->index();
        $table->string('user_image', 255)->nullable();
        $table->string('user_referral_code', 100)->nullable()->index();
        $table->string('user_customer_key', 100)->nullable()->index();
        $table->string('reservation_name', 100)->nullable()->index();
        $table->string('reservation_email', 100)->nullable()->index();
        $table->string('reservation_phone', 50)->nullable()->index();
        $table->boolean('user_agree_age')->default(false);
        $table->boolean('user_agree_terms')->default(false);
        $table->boolean('user_agree_privacy')->default(false);
        $table->boolean('user_agree_location')->default(false);
        $table->boolean('user_agree_marketing')->default(false);
        $table->timestamp('user_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();
        $table->timestamp('user_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();
        $table->timestamp('user_last_login_date')->nullable()->index();
    });
}
