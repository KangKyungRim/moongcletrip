<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::table('benefits')->insert([
    [
        'benefit_name' => '조식 2인 무료',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
    [
        'benefit_name' => '조식 3인 무료',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
    [
        'benefit_name' => '조식 4인 무료',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
    [
        'benefit_name' => '수영장 2인 무료',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
    [
        'benefit_name' => '수영장 3인 무료',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
    [
        'benefit_name' => '수영장 4인 무료',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
    [
        'benefit_name' => '생맥주 4잔, BBQ 치킨 할인권',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
    [
        'benefit_name' => '롯데월드 입장권 50% 할인',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
    [
        'benefit_name' => '롯데월드 어트랙션 이용권 10% 할인',
        'benefit_is_upcharge' => false,
        'benefit_created_at' => Capsule::raw('CURRENT_TIMESTAMP'),
        'benefit_updated_at' => Capsule::raw('CURRENT_TIMESTAMP')
    ],
]);
