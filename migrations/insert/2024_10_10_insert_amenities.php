<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// 마이그레이션 실행
if (Capsule::schema()->hasTable('benefits')) {
    $benefits = [
        '룸 업그레이드 (일~목 체크인, 객실 가능시)',
        '룸 업그레이드 (객실 가능시)',
        '무료 전망 업그레이드 (객실 가능시)',
        '고층 배정 (객실 가능시)',
        '미니바 무료',
        '객실 내 과일 바구니 제공',
        '객실 내 꽃다발 서비스',
        '1시간 레이트 체크아웃 (객실 가능시)',
        '2시간 레이트 체크아웃 (객실 가능시)',
        '3시간 레이트 체크아웃 (객실 가능시)',
        '1시간 얼리체크인 (객실 가능시)',
        '2시간 얼리체크인 (객실 가능시)',
        '24시간 스테이',
        '24시간 스테이 (13시 체크인~ 13시 체크아웃)',
        '36시간 스테이 (09시 체크인~21시 체크아웃)',
        '1일 1셔츠 무료세탁 서비스',
        '인원추가비 무료 (최대 정원까지)',
        '1박시 1박 무료 (뭉클 1박 예약+체크인시 1박 추가)',
        '2박시 1박 무료 (뭉클 2박 예약+체크인시 1박 추가)',
        '3박시 1박 무료 (뭉클 3박 예약+체크인시 1박 추가)',
        '7박시 1박 무료(뭉클 7박 예약+체크인시 1박 추가)',
        '와인 1병 무료',
        '샴페인 1병 무료',
        '객실 내 룸서비스 50% 이용권',
        '객실 내 룸서비스 최대 5만원 무료',
        '웰컴드링크',
        '웰컴드링크 2잔 무료',
        '아이스아메리카노 2잔 무료',
        '칵테일 2잔 무료',
        '조식 무료',
        '조식 50% 현장 할인',
        '조식 30% 현장 할인',
        '조식 1+1 혜택 (현장 결제)',
        '레스토랑 20% 할인권 (현장할인)',
        '베이커리 30% 할인권 (현장할인)',
        '카페 20% 할인권',
        '식음업장 5만원 자유이용권',
        '식음업장 7만원 자유이용권',
        '식음업장 10만원 자유이용권',
        '바베큐 숯/그릴 무료',
        '바베큐 전기그릴 무료',
        '웰컴기프트 (선착순 한정수량 예약건 당 1개 제공)',
        '사우나 무료',
        '수영장 무료',
        '객실내 수영장 온수 무료',
        '무료 자쿠지 온수',
        '수영장 2회 이용권',
        '수영장 무제한 이용권',
        '수영장 50% 현장 할인(현장 결제)',
        '이그제큐티브 라운지 무료',
        '이그제큐티브 라운지 50% 할인',
        '마사지/트리트먼트 무료',
        '마사지/트리트먼트 50% 현장할인',
        '무료 자전거 대여 서비스',
        '무료 발렛파킹',
        '무료 공항 픽업 서비스',
        '무료 공항 샌딩 서비스',
        '무료 공항 셔틀버스',
    ];

    foreach ($benefits as $benefit) {
        $existing = Capsule::table('benefits')
            ->where('benefit_name', $benefit)
            ->exists();

        if ($existing) {
            Capsule::table('benefits')
                ->where('benefit_name', $benefit)
                ->update([
                    'benefit_recommend' => 1,
                    'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
                ]);
        } else {
            Capsule::table('benefits')->insert([
                'benefit_name' => $benefit,
                'benefit_recommend' => 1,
                'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
                'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
            ]);
        }
    }
}
