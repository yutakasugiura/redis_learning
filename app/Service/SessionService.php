<?php

namespace App\Service;

require("vendor/autoload.php");

use App\Repository\RedisSessionRepository;

class SessionService
{
    protected RedisSessionRepository $redisSessionRepository;

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
            echo 'Failed Authorization';
            return false;
        }
        // userId is binded by sessionId.
        $newSessionId = uniqid('yuses_');
        $this->redisSessionRepository->saveSession($newSessionId, $loginUserId);

        // this session is called from user's browser.
        $userId = $this->redisSessionRepository->getUserIdBySession($newSessionId);

        if ($userId) {
            echo 'Success to Authorizaiton';
            return true;
        }
        echo 'Failed Authorization';
        return false;
    }
}
