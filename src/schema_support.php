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
        return true;
    }

    $afterColumn = btColumnExists("users", "role") ? "role" : (btColumnExists("users", "password") ? "password" : "email");

    return $conn->query(
        "ALTER TABLE users
         ADD COLUMN selected_plan ENUM('starter','growth') NOT NULL DEFAULT 'growth'
         AFTER {$afterColumn}"
    ) === true;
}
