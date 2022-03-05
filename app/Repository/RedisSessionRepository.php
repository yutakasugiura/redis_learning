<?php

namespace App\Repository;

use Predis\Client as RedisClient;

class RedisSessionRepository
{
    private RedisClient $redis;

    public function __construct()
    {
        // グローバルにまとめたい
        $dotenv = \Dotenv\Dotenv::createImmutable('./');
        $dotenv->load();

        $this->redis = new RedisClient([
            'schema' => $_ENV['REDIS_SCHEMA'],
            'host' => $_ENV['REDIS_HOST'],
            'port' => $_ENV['REDIS_PORT'],
        ]);
    }

    public function saveSession(string $sessionId, string $userId): void
    {
        $this->redis->set($sessionId, $userId);
        $this->redis->expire($sessionId, 3600 * 1); // 1min
    }

    public function getUserIdBySession(string $sessionId): string
    {
        return $this->redis->get($sessionId);
    }
}