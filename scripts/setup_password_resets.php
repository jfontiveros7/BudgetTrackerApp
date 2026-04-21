<?php
require_once __DIR__ . '/../config/database.php';

$tableSql = "
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash VARCHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_password_resets_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
)
";

if (!$conn->query($tableSql)) {
    fwrite(STDERR, "Table error: " . $conn->error . PHP_EOL);
    exit(1);
}

$indexStatements = [
    "CREATE INDEX idx_password_resets_user_id ON password_resets(user_id)",
    "CREATE INDEX idx_password_resets_expires_at ON password_resets(expires_at)",
];

foreach ($indexStatements as $sql) {
    if (!$conn->query($sql)) {
        if (stripos($conn->error, "Duplicate key name") === false) {
            fwrite(STDERR, "Index error: " . $conn->error . PHP_EOL);
            exit(1);
        }
    }
}

echo "password_resets table and indexes are ready." . PHP_EOL;
