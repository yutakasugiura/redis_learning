# フレームワークを使わずに色々理解したい

## 目的

- フレームワークを使っていると、内部実装に対する理解が浅くなる
- なので、原理原則を知るために、フレームワークではなくライブラリーのみを使用して挙動を理解したい
- 理解したい項目は下記
  - Redis
  - RFC6750
  - autoload
  - middleware

## Redis

<img src="static/img/redis_img.png">

 - Redisを使ってログインSessionを管理する簡易実装

```
// env生成
cp .example.env .env

// Redisを起動
redis-server
redis-cli // 操作したい時（ポート確認）

// Redisにデータを出し入れする
php App/Domain/UnderstandRedisDomain.php
```

### SequenceDiagram with Redis

```mermaid
sequenceDiagram
    participant Domain
    participant Service
    participant Repository
    participant Redis
    Domain->>+Service: New user don not have session_id. 
    Service->>Service: Auth user_id.
    Service->>Service: Create session_id.
    Service->>Service: Hash user_id by md5.
    Service->>Repository: Save Session_id with hashed_user_id.
    Repository->>Redis: Save in Redis.
     Note right of Redis: key: session_id<br>value: hased_user_Id <br>type: STRING<br> ttl: 60 * 60
    Redis-->>Repository: result.
    Repository-->>Service: result.
    Service->>-Domain: return session_id.

    Domain->>+Service: User having session_id.
    Service->>Repository: Get hashed_user_id by session_id.
    Repository->>Redis: Get hashed_user_id by Session_id.
    Redis->>Repository: return hashed_user_id.
    Repository->>Service: return hashed_user_id.
    Service->>Service: user_id is same to hased_user_id?
    Service->>Service: Get User Object by user_id.
    Service->>Service: try something.
    Service->>-Domain: return result.
```