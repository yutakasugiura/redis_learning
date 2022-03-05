# フレームワークを使わずに色々理解したい

## 目的

- フレームワークを使っていると、内部実装に対する理解が浅くなる
- なので、原理原則を知るために、フレームワークではなくライブラリーのみを使用して挙動を理解したい
- 理解したい項目は下記
  - Redis
  - Session
  - Cookie
  - autoload

## Redis

- 前提
  - compoer require predis/presdis
  - brew install redis // local に redis を install
  - redis-server // local で redis を起動
  - redis-cli // redis の内部に入る
    - 127.0.0.1:6379 > //ポートの割り当てが出る

## Autoload

- 前提
  - require を使いたくない
- 設定
  - composer install
  - composer dump-autoload // create autoload.php

```composer.json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

## interephence
