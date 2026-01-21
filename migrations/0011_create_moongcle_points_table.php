<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('moongcle_points')) {
    Capsule::schema()->create('moongcle_points', function (Blueprint $table) {
        $table->bigIncrements('moongcle_point_idx');  // 고유 ID
        $table->bigInteger('partner_idx')->unsigned()->nullable();
        $table->text('moongcle_point_introduction')->nullable();  // 소개 (긴 텍스트 가능)
        
        // 뭉클 포인트 1 ~ 5 및 각 포인트의 사진 경로
        $table->string('moongcle_point_1_title', 255)->nullable();  // 뭉클포인트 1 제목
        $table->text('moongcle_point_1_description')->nullable();  // 뭉클포인트 1 설명
        $table->string('moongcle_point_1_image', 255)->nullable();  // 뭉클포인트 1 이미지 경로
        
        $table->string('moongcle_point_2_title', 255)->nullable();  // 뭉클포인트 2 제목
        $table->text('moongcle_point_2_description')->nullable();  // 뭉클포인트 2 설명
        $table->string('moongcle_point_2_image', 255)->nullable();  // 뭉클포인트 2 이미지 경로
        
        $table->string('moongcle_point_3_title', 255)->nullable();  // 뭉클포인트 3 제목
        $table->text('moongcle_point_3_description')->nullable();  // 뭉클포인트 3 설명
        $table->string('moongcle_point_3_image', 255)->nullable();  // 뭉클포인트 3 이미지 경로
        
        $table->string('moongcle_point_4_title', 255)->nullable();  // 뭉클포인트 4 제목
        $table->text('moongcle_point_4_description')->nullable();  // 뭉클포인트 4 설명
        $table->string('moongcle_point_4_image', 255)->nullable();  // 뭉클포인트 4 이미지 경로
        
        $table->string('moongcle_point_5_title', 255)->nullable();  // 뭉클포인트 5 제목
        $table->text('moongcle_point_5_description')->nullable();  // 뭉클포인트 5 설명
        $table->string('moongcle_point_5_image', 255)->nullable();  // 뭉클포인트 5 이미지 경로
        
        // 한눈에 보기 영상 링크
        $table->string('overview_video_url', 255)->nullable();  // 한눈에 보기 영상 URL
        
        // 투숙 정원 (최소 ~ 최대)
        $table->integer('min_occupancy')->nullable();  // 최소 투숙 정원
        $table->integer('max_occupancy')->nullable();  // 최대 투숙 정원
        
        // 주중 및 주말 평균 요금
        $table->decimal('weekday_average_price', 10, 2)->nullable();  // 주중 평균 요금
        $table->decimal('weekend_average_price', 10, 2)->nullable();  // 주말 평균 요금
        
        // 인기 시설 및 주변 놀거리
        $table->text('popular_facilities')->nullable();  // 인기 시설
        $table->text('nearby_attractions')->nullable();  // 주변 놀거리
        
        $table->timestamps();  // 생성 및 수정 시각
    });
}