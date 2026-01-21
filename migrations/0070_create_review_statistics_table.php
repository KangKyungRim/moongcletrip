<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

if (!Capsule::schema()->hasTable('review_statistics')) {
	Capsule::schema()->create('review_statistics', function (Blueprint $table) {
		$table->bigIncrements('stat_idx');                 // 통계 ID (기본 키)
		$table->bigInteger('entity_idx')->unsigned()->index();      // 엔티티 ID (파트너, 스테이 등)
		$table->string('entity_type')->index();                    // 엔티티 유형 (e.g., 'partner', 'stay', 'ticket', 'air')
		$table->integer('review_count')->unsigned()->index();      // 리뷰 개수
		$table->decimal('average_rating', 3, 2)->index();          // 평균 리뷰 점수 (소수점 두 자리)
		$table->timestamp('last_updated')->default(Capsule::raw('CURRENT_TIMESTAMP'))->onUpdate(Capsule::raw('CURRENT_TIMESTAMP')); // 마지막 업데이트 시각
	});
}
