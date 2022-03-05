<?php

namespace App\Command\CreateRedisCommand;

require("vendor/autoload.php");

use App\Service\SessionService;

/**
 * Execute Command
 *  - php App/Command/CreateRedisCommand.php
 */
final class CreateRedisCommand
{
    protected SessionService $sessionService;

    public function __construct(SessionService $sessionService = null)
    {
        $this->sessionService = $sessionService ?? new SessionService();
    }

    public function execute(): void
    {
        $loginUserId = 'test_yusugiura';
        $this->sessionService->execute($loginUserId);
    }
}

// Dependency Injection
$createRedisCommand = new CreateRedisCommand();
$createRedisCommand->execute();