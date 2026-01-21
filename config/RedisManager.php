<?php

use Predis\Client;

class RedisManager {
    private static $instance = null;

    private function __construct() {
        // private 생성자로 외부 인스턴스 생성을 막음
    }

    public static function getInstance() {
        if (self::$instance === null) {
            // Predis 클라이언트 구성
            $config = [
                'scheme' => 'tcp',
                'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
                'port' => $_ENV['REDIS_PORT'] ?? 6379,
            ];

            // Redis 인증 추가
            if (!empty($_ENV['REDIS_PASSWORD'])) {
                $config['password'] = $_ENV['REDIS_PASSWORD'];
            }

            // Predis 클라이언트 생성
            try {
                self::$instance = new Client($config);

                // Redis 연결 테스트
                self::$instance->connect();
            } catch (\Exception $e) {
                error_log('Predis 연결 실패: ' . $e->getMessage());
                throw new \Exception('Redis 연결 중 문제가 발생했습니다.');
            }
        }

        return self::$instance;
    }

    /**
     * Redis 연결 종료
     */
    public static function disconnect() {
        if (self::$instance !== null) {
            self::$instance->disconnect();
            self::$instance = null;
        }
    }
}