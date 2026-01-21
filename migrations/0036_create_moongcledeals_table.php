<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcledeals')) {
    Capsule::schema()->create('moongcledeals', function (Blueprint $table) {
        $table->bigIncrements('moongcledeal_idx');  // 기본 키 (Auto-increment)
        $table->bigInteger('user_idx')->unsigned()->nullable()->index();  // 뭉클딜을 만든 유저
        $table->string('title', 500)->nullable()->after('represent');
        $table->json('priority')->nullable();  // 우선순위
        $table->json('selected')->nullable();  // 선택 사항들
        $table->string('status', 20)->default('disabled')->index();  // 딜 활성화 여부
        $table->boolean('moongcledeal_create_complete')->default(false)->index();  // 딜 작성 완료 여부
        $table->boolean('represent')->default(false)->index();
        $table->timestamps();  // 생성 시각, 수정 시각

        // 외래 키 설정
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('set null');
    });
}
