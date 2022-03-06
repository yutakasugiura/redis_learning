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
     * Undocumented function
     *
     * @param string $sessionId
     * @return string
     */
    public function execute(string $sessionId): string
    {
        // isLoggedIn?
        if (!$sessionId) {
            throw new InvalidArgumentException('Failed: do not exist session_id');
        }

        // this session is called from user's browser.
        $userId = $this->redisSessionRepository->getUserIdBySession($sessionId);

        if (!$userId) {
            throw new InvalidArgumentException('Failed: do not exist user_id');
        }
        return $userId;
    }
}
