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
      Note over Domain, Service: send: user_id & password
    Service->>Service: Auth user by user_id & password.
    Service->>Service: Create session_id.
    Service->>Service: Hash user_id by md5.
    Service->>Repository: Save Session_id with hashed_user_id.
    Repository->>Redis: Save in Redis.
     Note right of Redis: key: session_id<br>value: hased_user_Id <br>type: STRING<br> ttl: 60 * 60
    Redis-->>Repository: result.
    Repository-->>Service: result.
    Service->>-Domain: return session_id.

    Domain->>+Service: User having session_id.
      Note over Domain, Service: send: user_id and session_id
    Service->>Repository: Get hashed_user_id by session_id.
    Repository->>Redis: Get hashed_user_id by Session_id.
    alt exist session_id
      Redis->>Repository: return hashed_user_id.
      Repository->>Service: return hashed_user_id.
      Service->>Service: user_id is same to hased_user_id?
      Service->>Service: try something.
    end
    Service->>-Domain: return result.
```

## RFC6750　
 - OAuth2.0におけるtokenによる認証認可
 - Bearerスキームの定義
    - 仕様書
       - https://datatracker.ietf.org/doc/html/rfc6750
    - 仕様
       - tokenは任意のtoken68文字列
       - Authorization Header: Bearer Token は1つのみ含める
       - TLSの常時使用
       - tokenのTTLは60min以内
       - authorization codeのTTLは10min以内
       - tokenをURLに含めない
 - 実装は大変なので、Authleteなどの外部APIを活用する

| | | |
| -- | -- | -- |
| OAuth2.0 | RFC6749 |  |
| BearerTokenUsage | RFC6750 |認可サーバーが発行したtokenをリソースサーバに送るAPI仕様|
| OAuth1.0 | RFC5849 | OAuth2.0により廃止 |
| OpenIDConnect |  |  |
| JsonWebToken | RFC7519 |  |


```php
// 401 Unauthorized (Authorization Header is undefined)
WWW-Authenticate: Bearer realm="{string}"

// 401 Unauthorized (Token is invalid)
WWW-Authenticate: Bearer error="invalid_token"

// 400 Unauthorized (Invalid Request param)
WWW-Authenticate: Bearer error="invalid_request"

// 403 BadRequest (Request Param is invalid scope)
WWW-Authenticate: Bearer error="insufficient_scope"

```



```mermaid
sequenceDiagram
  participant Client
  participant Auth as Authorization Server
  participant DB as Authorization DB
  participant Resource as Resource Server

  Client ->>+ Auth: 認可リクエスト
  Auth ->>- Client: 認可ページ
  Client ->> Client: user / password 入力
  Client ->>+ Auth: [POST]承認
  Auth ->> DB: is correct?
  DB ->> Auth: return 200 OK
  Auth ->> Auth: 認可コード発行(-10min)
    note over Auth: ?code=Po90111akjh373raQQaa...
  Auth ->>- Client: redirect url with?code={code}
  Client ->>+ Auth: [POST]token発行
  Auth ->> Auth: token発行
    note over Auth: key: token, value: id
  Auth ->> DB: token保持 (Redisなど)
  Auth ->>- Client: return token
  Client ->>+ Resource: Request with Bearer {token}
  Resource ->> DB: is correct token?
  DB ->> Resource: return 200 OK
  Resource ->> Resource: do something
  Resource ->>- Client: return 200 OK with secure data
```