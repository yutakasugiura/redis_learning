
```mermaid
sequenceDiagram
    participant Domain
    participant Service
    participant Repository
    participant Redis
    Domain->>+Service: New user don not have session_id. 
    Service->>Service: Get user_id.
    Service->>Service: Create session_id.
    Service->>Repository: Save Session_id with user_id.
    Repository->>Redis: Save in Redis.
    Redis-->>Repository: result.
    Repository-->>Service: result.
    Service->>-Domain: return session_id.
    Domain->>+Service: User having session_id.
    Service->>Repository: Get user_id by session_id.
    Repository->>Redis: Get user_id by Session_id.
    Redis->>Repository: return user_id.
    Repository->>Service: return user_id.
    Service->>Service: try something.
    Service->>-Domain: return result.
```