<?php

require 'config/bootstrap.php';

foreach (glob('migrations/drop/*_drop.php') as $filename) {
	require $filename;  // 테이블 삭제 또는 수정
}

echo "All migrations have been rolled back successfully.\n";
