<?php

namespace App\Service;

use App\Repository\RedisSessionRepository;
use App\Exceptions\InvalidArgumentException;

class GetSessionService
{
    protected RedisSessionRepository $redisSessionRepository;

    /**
     * @param RedisSessionRepository|null $redisSessionRepository
     */
    public function __construct(RedisSessionRepository $redisSessionRepository = null)
    {
        $this->redisSessionRepository = $redisSessionRepository ?? new RedisSessionRepository();
    }

    /**
     * @param string $sessionId
     * @param string $userId
     * @return string
     */
    public function execute(string $sessionId, string $userId): string
    {
        // isLoggedIn?
        if (!$sessionId) {
            throw new InvalidArgumentException('Failed: do not exist session_id');
        }

        // this session is called from user's browser.
        $hasedUserId = $this->redisSessionRepository->getUserIdBySession($sessionId);

        // user_id is same to hashed_user_id?
        if (md5($userId) !== $hasedUserId) {
            throw new InvalidArgumentException('Failed: Invalid user_id');
        }

        if (!$userId) {
            throw new InvalidArgumentException('Failed: do not exist user_id');
        }
        return $userId;
    }
}
