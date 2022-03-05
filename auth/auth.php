<?php

namespace App\Auth\AuthSession;

require("vendor/autoload.php");

use Predis\Client as RedisClient;

class AuthSession
{
    /**
     * [MEMO]
     *  - Sessionはどこに保存される？（Serverで保持する場合）
     *    - phpinfo();で定義をみれる
     *    - デフォルトはphp.conf php.iniで設定可能
     *    - session_save_path($path)で保存先を定義可
     *  - Redisを使うので、session()に関する定義は不要
     */
    public function __construct()
    {
        // session_save_path('./auth/session'); // 実行場所からのパス
        // session_start();
        date_default_timezone_set("Asia/Tokyo");
    }

    public function execute(): bool
    {
        // this session is saved in user's browser.
        $session = $this->setSession();

        // this session is called from user's browser.
        $userId = $this->getSession($session);

        if ($userId) {
            echo 'Success to Authorizaiton';
            return true;
        }
        echo 'Failed Authorization';
    }

    public function setSession(): string
    {
        $sessionId = uniqid('yuses_');
        $redis = $this->callRedisClient();
        $redis->set($sessionId, 'yusugiura20');
        $redis->expire($sessionId, 3600 * 1); // 1min
        return $sessionId;
    }

    public function getSession(string $session): string
    {
        $redis = $this->callRedisClient();
        $res = $redis->get($session);

        echo 'Key For Redis: ' . $session;
        echo 'Get UserId From Redis: ' . $res;

        return true;
    }

    /**
     * Redisへの接続
     *  - 前提：ローカルにRedisサーバーを起動（brew install redis, ）
     */
    private function callRedisClient()
    {
        // tcpの指定がない場合でも:6379に設定される
        return new RedisClient([
            'schema' => 'tcp',
            'host' => '127.0.0.1',
            'port' => '6379'
        ]);
    }
}

$AuthSession = new AuthSession();
$AuthSession->execute();
