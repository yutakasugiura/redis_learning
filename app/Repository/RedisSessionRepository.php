<?php

namespace App\Repository;

use Predis\Client as RedisClient;
use Dotenv\Dotenv;
use PDOException;

class RedisSessionRepository
{
    private RedisClient $redis;

    public function __construct()
    {
        // TODO: グローバルにまとめたい
        $dotenv = Dotenv::createImmutable('./');
        $dotenv->load();

        // TODO: configに移行したい
        $this->redis = new RedisClient([
            'schema' => $_ENV['REDIS_SCHEMA'],
            'host' => $_ENV['REDIS_HOST'],
            'port' => $_ENV['REDIS_PORT'],
        ]);
    }

    /**
     * @param string $sessionId
     * @param string $userId
     * @return bool
     */
    public function saveSession(string $sessionId, string $userId): bool
    {
        try {
            $this->redis->set($sessionId, $userId);
            $this->redis->expire($sessionId, $_ENV['REDIS_TTL']);
            return true;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    /**
     * @param string $sessionId
     * @return string
     */
    public function getUserIdBySession(string $sessionId): string
    {
        return $this->redis->get($sessionId);
    }
}