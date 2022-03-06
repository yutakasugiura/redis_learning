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
     * Undocumented function
     *
     * @param string|null $loginUserId
     * @return string
     */
    public function execute(?string $loginUserId): string
    {
        // isLoggedIn?
        if (!$loginUserId) {
            throw new InvalidArgumentException('Failed: do not exist user_id');
        }
        
        // hash user_id
        $hasedUserId = hash('md5', $loginUserId);

        // userId is binded by sessionId.
        $newSessionId = 'hpx_' . substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz0123456789"), 0, 30);
        $this->redisSessionRepository->saveSession($newSessionId, $hasedUserId);

        return $newSessionId;
    }
}
