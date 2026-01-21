<?php
// bootstrap.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Database.php';
require_once  __DIR__ . '/RedisManager.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Database 싱글톤 인스턴스 초기화 (Capsule 연결)
Database::getInstance();