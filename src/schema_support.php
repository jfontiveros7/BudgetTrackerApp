<?php
require_once __DIR__ . '/../config/database.php';

function btTableExists($tableName) {
    global $conn;

    $sql = "
        SELECT COUNT(*) AS c
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
          AND table_name = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tableName);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return (int) ($row["c"] ?? 0) > 0;
}

function btColumnExists($tableName, $columnName) {
    global $conn;

    $sql = "
        SELECT COUNT(*) AS c
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = ?
          AND column_name = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $tableName, $columnName);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return (int) ($row["c"] ?? 0) > 0;
}

function btColumnType($tableName, $columnName) {
    global $conn;

    $sql = "
        SELECT column_type
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = ?
          AND column_name = ?
        LIMIT 1
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $tableName, $columnName);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return strtolower((string) ($row["column_type"] ?? ""));
}

function btHasV2Categories() {
    return btTableExists("categories") && btColumnExists("transactions", "category_id");
}

function btHasTransactionDate() {
    return btColumnExists("transactions", "transaction_date");
}

function btEnsureUsersRoleColumn() {
    global $conn;

    if (btColumnExists("users", "role")) {
        return true;
    }

    $afterColumn = btColumnExists("users", "password") ? "password" : "email";

    return $conn->query(
        "ALTER TABLE users
         ADD COLUMN role ENUM('admin','user') NOT NULL DEFAULT 'user'
         AFTER {$afterColumn}"
    ) === true;
}

function btEnsureUsersPlanColumn() {
    global $conn;

    btEnsureUsersRoleColumn();

    if (btColumnExists("users", "selected_plan")) {
        $columnType = btColumnType("users", "selected_plan");
        if (str_contains($columnType, "'scale'")) {
            return true;
        }

        return $conn->query(
            "ALTER TABLE users
             MODIFY COLUMN selected_plan ENUM('starter','growth','scale') NOT NULL DEFAULT 'growth'"
        ) === true;
    }

    $afterColumn = btColumnExists("users", "role") ? "role" : (btColumnExists("users", "password") ? "password" : "email");

    return $conn->query(
        "ALTER TABLE users
         ADD COLUMN selected_plan ENUM('starter','growth','scale') NOT NULL DEFAULT 'growth'
         AFTER {$afterColumn}"
    ) === true;
}

function btEnsurePurchaseClaimsTable() {
    global $conn;

    $createSql = "
        CREATE TABLE IF NOT EXISTS purchase_claims (
            id INT AUTO_INCREMENT PRIMARY KEY,
            claim_token VARCHAR(64) NOT NULL UNIQUE,
            plan ENUM('starter','growth','scale') NOT NULL,
            stripe_checkout_session_id VARCHAR(255) DEFAULT NULL UNIQUE,
            stripe_customer_email VARCHAR(255) DEFAULT NULL,
            payment_status ENUM('initiated','paid','claimed') NOT NULL DEFAULT 'initiated',
            claimed_user_id INT DEFAULT NULL,
            metadata_json TEXT DEFAULT NULL,
            paid_at DATETIME DEFAULT NULL,
            claimed_at DATETIME DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_purchase_claims_user
                FOREIGN KEY (claimed_user_id) REFERENCES users(id)
                ON DELETE SET NULL
        )
    ";

    if ($conn->query($createSql) !== true) {
        return false;
    }

    if (!btColumnExists("purchase_claims", "stripe_customer_email")) {
        if ($conn->query("ALTER TABLE purchase_claims ADD COLUMN stripe_customer_email VARCHAR(255) DEFAULT NULL AFTER stripe_checkout_session_id") !== true) {
            return false;
        }
    }

    if (!btColumnExists("purchase_claims", "payment_status")) {
        if ($conn->query("ALTER TABLE purchase_claims ADD COLUMN payment_status ENUM('initiated','paid','claimed') NOT NULL DEFAULT 'initiated' AFTER stripe_customer_email") !== true) {
            return false;
        }
    } else {
        $paymentStatusType = btColumnType("purchase_claims", "payment_status");
        if (!str_contains($paymentStatusType, "'claimed'")) {
            if ($conn->query("ALTER TABLE purchase_claims MODIFY COLUMN payment_status ENUM('initiated','paid','claimed') NOT NULL DEFAULT 'initiated'") !== true) {
                return false;
            }
        }
    }

    if (!btColumnExists("purchase_claims", "claimed_user_id")) {
        if ($conn->query("ALTER TABLE purchase_claims ADD COLUMN claimed_user_id INT DEFAULT NULL AFTER payment_status") !== true) {
            return false;
        }
    }

    if (!btColumnExists("purchase_claims", "metadata_json")) {
        if ($conn->query("ALTER TABLE purchase_claims ADD COLUMN metadata_json TEXT DEFAULT NULL AFTER claimed_user_id") !== true) {
            return false;
        }
    }

    if (!btColumnExists("purchase_claims", "paid_at")) {
        if ($conn->query("ALTER TABLE purchase_claims ADD COLUMN paid_at DATETIME DEFAULT NULL AFTER metadata_json") !== true) {
            return false;
        }
    }

    if (!btColumnExists("purchase_claims", "claimed_at")) {
        if ($conn->query("ALTER TABLE purchase_claims ADD COLUMN claimed_at DATETIME DEFAULT NULL AFTER paid_at") !== true) {
            return false;
        }
    }

    return true;
}
