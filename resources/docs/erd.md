# System ERD

```mermaid
erDiagram
    USERS ||--o{ SALES : "created_by? (not modeled)"
    CUSTOMERS ||--o{ SALES : has
    CUSTOMERS ||--o{ TANK_MOVEMENTS : has
    CUSTOMERS ||--o{ TANK_DELIVERIES : has

    DRIVERS ||--o{ TANK_DELIVERIES : handles
    DRIVERS ||--o{ TANK_MOVEMENTS : involved_in

    TANKS ||--o{ TANK_MOVEMENTS : generates
    TANKS ||--o{ TANK_DELIVERIES : included
    TANKS }o--o{ SALES : via_sale_tanks

    SALES ||--o{ SALE_TANKS : contains
    SALES ||--o{ TANK_DELIVERIES : fulfilled_by

    CUSTOMERS {
        bigint id PK
        string name
        string first_name
        string middle_name
        string last_name
        string email NULL
        string phone NULL
        string address NULL
        string contact_number NULL
        text description NULL
        string dropoff_location NULL
        int reorder_point DEFAULT 0
        timestamps
    }

    DRIVERS {
        bigint id PK
        string first_name
        string last_name
        string contact_info NULL
        string license NULL
        timestamps
    }

    TANKS {
        bigint id PK
        string serial_code UNIQUE
        string status "filled|empty|with_customer"
        string brand NULL
        string valve_type NULL
        string size NULL
        integer amount NULL
        boolean is_hidden NULL
        timestamps
    }

    SALES {
        bigint id PK
        bigint customer_id FK "-> CUSTOMERS.id"
        bigint tank_id FK "-> TANKS.id (legacy)"
        integer quantity DEFAULT 1
        decimal price(10,2)
        enum payment_method "cash|gcash|credit_card"
        enum status "pending|completed"
        enum transaction_type "walk_in|delivery" DEFAULT walk_in
        timestamps
    }

    SALE_TANKS {
        bigint id PK
        bigint sale_id FK "-> SALES.id"
        bigint tank_id FK "-> TANKS.id"
        timestamps
        UNIQUE(sale_id, tank_id)
    }

    TANK_MOVEMENTS {
        bigint id PK
        bigint tank_id FK "-> TANKS.id"
        string previous_status NULL
        string new_status
        bigint customer_id FK NULL "-> CUSTOMERS.id"
        bigint driver_id FK NULL "-> DRIVERS.id"
        timestamp created_at
    }

    TANK_DELIVERIES {
        bigint id PK
        bigint tank_id FK NULL "-> TANKS.id (legacy)"
        bigint sale_id FK NULL "-> SALES.id"
        bigint customer_id FK "-> CUSTOMERS.id"
        bigint driver_id FK NULL "-> DRIVERS.id"
        timestamp date_delivered NULL
        timestamps
    }

    USERS {
        bigint id PK
        string name
        string email UNIQUE
        string password
        string role NULL
        timestamps
    }
```

Notes:
- Sales can include multiple tanks via `sale_tanks`. `sales.tank_id` remains for backward compatibility.
- Tank deliveries normally reference a `sale_id`; `tank_id` is kept for legacy single‑tank deliveries.
- Only delivery-type sales show up for recording deliveries; walk-ins directly transfer tank status to the customer when completed.
- `customers.email` is nullable to support individual customers without email.
