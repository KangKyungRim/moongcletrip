<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('search_logs')) {
    Capsule::schema()->create('search_logs', function (Blueprint $table) {
        $table->bigIncrements('log_idx');  // 로그 ID (기본 키)
        $table->bigInteger('user_idx')->unsigned()->nullable()->index();  // 유저 ID (비회원일 경우 null 가능)
        $table->bigInteger('token_idx')->unsigned()->nullable()->index();  // 유저 Token (웹일 경우 null 가능)
        $table->json('search_params');  // 검색 조건 (JSON 형식으로 인원, 날짜, 지역, 태그 등 기록)
        $table->integer('result_count')->default(0);  // 검색 결과 수
        $table->ipAddress('user_ip')->nullable();  // 검색을 수행한 유저의 IP 주소
        $table->timestamps();  // 생성 시각 및 수정 시각

        // 외래 키 설정 (유저가 있을 경우 연결)
        $table->foreign('user_idx')->references('user_idx')->on('users')->onDelete('set null');
    });
}