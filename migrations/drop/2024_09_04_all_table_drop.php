<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->disableForeignKeyConstraints();  // 외래 키 제약 비활성화

// 모든 테이블 가져오기
$tables = Capsule::select('SHOW TABLES');

// 테이블 이름 추출
foreach ($tables as $table) {
    $tableArray = get_object_vars($table); // 객체를 배열로 변환
    $tableName = array_values($tableArray)[0]; // 테이블 이름 가져오기

    // 테이블 삭제
    Capsule::schema()->dropIfExists($tableName);
}


Capsule::schema()->enableForeignKeyConstraints();  // 외래 키 제약 활성화