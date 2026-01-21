<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// 마이그레이션 실행
if (!Capsule::schema()->hasTable('partner_users')) {
    Capsule::schema()->create('partner_users', function (Blueprint $table) {
        $table->bigIncrements('partner_user_idx');
        $table->integer('partner_user_level')->index();
        $table->string('partner_user_nickname', 50)->index();
        $table->string('partner_user_password', 255)->nullable();
        $table->string('partner_user_name', 100)->nullable()->index();
        $table->string('partner_user_email', 100)->unique()->nullable()->index();
        $table->string('partner_user_phone_number', 50)->nullable()->index();
        $table->string('partner_user_login_type', 10)->nullable()->index(); // email
        $table->string('partner_user_status', 10)->nullable()->index(); // normal, stop, block, delete
        $table->string('partner_user_image', 255)->nullable();
        $table->string('partner_user_referral_code', 100)->nullable()->index();
        $table->timestamp('partner_user_created_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->index();
        $table->timestamp('partner_user_updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP'))->index();
        $table->timestamp('partner_user_last_login_at')->nullable()->index();
    });

    $hashedPassword = password_hash('0B9b2j7wNRhWWDhpGcAr', PASSWORD_DEFAULT);

    // 기본 슈퍼 유저 추가
    Capsule::table('partner_users')->insert([
        'partner_user_level' => 9, // 슈퍼 유저 레벨
        'partner_user_nickname' => 'SuperAdmin',
        'partner_user_password' => $hashedPassword,
        'partner_user_name' => 'Super Admin',
        'partner_user_email' => 'superadmin@moongcletrip.com',
        'partner_user_phone_number' => '010-0000-0000',
        'partner_user_login_type' => 'email',
        'partner_user_status' => 'normal',
        'partner_user_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'partner_user_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ]);
}
