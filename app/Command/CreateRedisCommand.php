<?php

namespace App\Command\CreateRedisCommand;

require("vendor/autoload.php");

use App\Service\SessionService;
use Exception;

/**
 * Execute Command
 *  - php App/Command/CreateRedisCommand.php
 */
final class CreateRedisCommand
{
    protected SessionService $sessionService;
    
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
            echo $e->getMessage();
        }
    }
}

// Dependency Injection
$createRedisCommand = new CreateRedisCommand();
$createRedisCommand->execute();