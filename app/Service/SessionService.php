<?php

namespace App\Service;

use App\Repository\RedisSessionRepository;
use App\Exceptions\InvalidArgumentException;

class SessionService
{
    protected RedisSessionRepository $redisSessionRepository;

    /**
     * @param RedisSessionRepository|null $redisSessionRepository
     */
    public function __construct(RedisSessionRepository $redisSessionRepository = null)
    {
        // session_save_path('./auth/session'); // 実行場所からのパス
        // session_start();
        $this->redisSessionRepository = $redisSessionRepository ?? new RedisSessionRepository();
    }

    /**
     * @param string $loginUserId
     * @return boolean
     */
    public function execute(string $loginUserId): bool
    {
        // isLoggedIn?
        if (!$loginUserId) {
            throw new InvalidArgumentException('Failed: do not exist user_id');
        }
        // userId is binded by sessionId.
        $newSessionId = uniqid('yuses_');
        $this->redisSessionRepository->saveSession($newSessionId, $loginUserId);

        // this session is called from user's browser.
        $userId = $this->redisSessionRepository->getUserIdBySession($newSessionId);

        if ($userId) {
            return true;
        }
        return false;
    }
}
