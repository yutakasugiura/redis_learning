<?php

namespace App\Domain;

require("vendor/autoload.php");

use App\Service\CreateSessionService;
use App\Service\GetSessionService;

/**
 * Execute Command
 *  - php App/Domain/UnderstandRedisDomain.php
 */
final class UnderstandRedisDomain
{
    protected CreateSessionService $createSessionService;
    protected GetSessionService $getSessionService;
    
    // private const LOGIN_USER_ID = '';
    private const LOGIN_USER_ID = 'yusugi_';

    public function __construct(
        CreateSessionService $createSessionService = null,
        GetSessionService $getSessionService = null
    ){
        $this->createSessionService = $createSessionService ?? new CreateSessionService();
        $this->getSessionService = $getSessionService ?? new GetSessionService();
    }

    public function execute(): void
    {
        $session = $this->createSession();
        $userId = $this->getSession($session);

        echo 'SUCCESS! USER::' . $userId . "\n";;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function createSession(): string
    {
        $loginUserId = self::LOGIN_USER_ID;
        try {
            $sessionId = $this->createSessionService->execute($loginUserId);
            echo 'SUCCESS! Create Session' . "\n";
            return $sessionId;
        } catch(\Exception $e) {
            echo '====[ERROR]=====';
            echo $e->getMessage();
        }
    }

    /**
     * Undocumented function
     *
     * @param string $sessionId
     * @return string
     */
    private function getSession(string $sessionId): string
    {
        $loginUserId = self::LOGIN_USER_ID;
        try {
            $userId = $this->getSessionService->execute($sessionId, $loginUserId);
            echo 'SUCCESS! Authorization' . "\n";
            return $userId;
        } catch(\Exception $e) {
            echo '====[ERROR]=====';
            echo $e->getMessage();
        }
    }
}

// Dependency Injection
$UnderstandRedisDomain = new UnderstandRedisDomain();
$UnderstandRedisDomain->execute();