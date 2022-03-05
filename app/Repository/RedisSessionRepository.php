<?php

namespace App\Repository;

use Predis\Client as RedisClient;
use Dotenv\Dotenv as Dotenv;

class RedisSessionRepository
{
    private RedisClient $redis;

    public function __construct()
    {
        // グローバルにまとめたい
        $dotenv = Dotenv::createImmutable('./');
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
        $this->redis->expire($sessionId, $_ENV['REDIS_TTL']);
    }

    public function getUserIdBySession(string $sessionId): string
    {
        return $this->redis->get($sessionId);
    }
}