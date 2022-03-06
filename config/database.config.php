<?php

/**
 * Read Env 
 */
$dotenv = \Dotenv\Dotenv::createImmutable('./');
$dotenv->load();

/**
 * Settings for Redis
 */

 return [
    'redis' => [
        "schema" => $_ENV['REDIS_SCHEMA'],
        "host" => $_ENV['REDIS_SCHEMA'],
        "port" => $_ENV['REDIS_SCHEMA'],
        "ttl" => $_ENV['REDIS_TTL']
    ]
 ];