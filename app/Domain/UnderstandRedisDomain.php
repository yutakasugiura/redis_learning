<?php

namespace App\Domain;

require("vendor/autoload.php");

use App\Service\SessionService;

/**
 * Execute Command
 *  - php App/Domain/UnderstandRedisDomain.php
 */
final class UnderstandRedisDomain
{
    protected SessionService $sessionService;
    
    // private const LOGIN_USER_ID = '';
    private const LOGIN_USER_ID = 'yusugi_';

    public function __construct(SessionService $sessionService = null)
    {
        $this->sessionService = $sessionService ?? new SessionService();
    }

    public function execute(): void
    {
        $loginUserId = self::LOGIN_USER_ID;
        try {
            $this->sessionService->execute($loginUserId);
            echo 'Success to Authorizaiton';
        } catch(\Exception $e) {
            echo '====[ERROR]=====';
            echo $e->getMessage();
        }
    }
}

// Dependency Injection
$UnderstandRedisDomain = new UnderstandRedisDomain();
$UnderstandRedisDomain->execute();