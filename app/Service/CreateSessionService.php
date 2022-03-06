<?php

namespace App\Service;

use App\Repository\RedisSessionRepository;
use App\Exceptions\InvalidArgumentException;

class CreateSessionService
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
     *
     * @param string $loginUserId
     * @return string
     */
    public function execute(string $loginUserId): string
    {
        // isLoggedIn?
        if (!$loginUserId) {
            throw new InvalidArgumentException('Failed: do not exist user_id');
        }
        // userId is binded by sessionId.
        $newSessionId = uniqid('yuses_');
        $this->redisSessionRepository->saveSession($newSessionId, $loginUserId);

        return $newSessionId;
    }
}
