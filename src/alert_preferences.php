<?php
require_once __DIR__ . '/../config/database.php';

function btDefaultAlertPreferences() {
    return [
        "overspending_risk" => true,
        "forecast" => true,
        "budget_threshold" => true,
        "subscription_review" => true,
        "coach_recommendation" => true,
    ];
}

function btEnsureAlertPreferenceTables() {
    global $conn;

    $conn->query(
        "CREATE TABLE IF NOT EXISTS user_alert_preferences (
            user_id INT NOT NULL,
            alert_type VARCHAR(100) NOT NULL,
            is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id, alert_type),
            CONSTRAINT fk_user_alert_preferences_user
                FOREIGN KEY (user_id) REFERENCES users(id)
                ON DELETE CASCADE
        )"
    );

    $conn->query(
        "CREATE TABLE IF NOT EXISTS user_alert_dismissals (
            user_id INT NOT NULL,
            alert_id VARCHAR(64) NOT NULL,
            dismissed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id, alert_id),
            CONSTRAINT fk_user_alert_dismissals_user
                FOREIGN KEY (user_id) REFERENCES users(id)
                ON DELETE CASCADE
        )"
    );

    $conn->query(
        "CREATE TABLE IF NOT EXISTS user_ai_settings (
            user_id INT NOT NULL PRIMARY KEY,
            coach_score_default_visible BOOLEAN NOT NULL DEFAULT FALSE,
            weekly_digest_enabled BOOLEAN NOT NULL DEFAULT TRUE,
            notification_cadence VARCHAR(30) NOT NULL DEFAULT 'weekly',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_user_ai_settings_user
                FOREIGN KEY (user_id) REFERENCES users(id)
                ON DELETE CASCADE
        )"
    );
}

function btDefaultAiSettings() {
    return [
        "coach_score_default_visible" => false,
        "weekly_digest_enabled" => true,
        "notification_cadence" => "weekly",
    ];
}

function getUserAlertPreferences($userId) {
    global $conn;

    btEnsureAlertPreferenceTables();
    $preferences = btDefaultAlertPreferences();

    $stmt = $conn->prepare(
        "SELECT alert_type, is_enabled
         FROM user_alert_preferences
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $preferences[$row["alert_type"]] = (bool) $row["is_enabled"];
    }

    return $preferences;
}

function saveUserAlertPreferences($userId, $preferences) {
    global $conn;

    btEnsureAlertPreferenceTables();
    $allowed = array_keys(btDefaultAlertPreferences());
    $stmt = $conn->prepare(
        "INSERT INTO user_alert_preferences (user_id, alert_type, is_enabled)
         VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE is_enabled = VALUES(is_enabled)"
    );

    foreach ($allowed as $alertType) {
        if (!array_key_exists($alertType, $preferences)) {
            continue;
        }
        $isEnabled = $preferences[$alertType] ? 1 : 0;
        $stmt->bind_param("isi", $userId, $alertType, $isEnabled);
        $stmt->execute();
    }

    return getUserAlertPreferences($userId);
}

function getUserDismissedAlertIds($userId) {
    global $conn;

    btEnsureAlertPreferenceTables();
    $dismissed = [];

    $stmt = $conn->prepare(
        "SELECT alert_id
         FROM user_alert_dismissals
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $dismissed[] = $row["alert_id"];
    }

    return $dismissed;
}

function dismissUserAlert($userId, $alertId) {
    global $conn;

    btEnsureAlertPreferenceTables();
    $stmt = $conn->prepare(
        "INSERT IGNORE INTO user_alert_dismissals (user_id, alert_id)
         VALUES (?, ?)"
    );
    $stmt->bind_param("is", $userId, $alertId);
    $stmt->execute();
}

function restoreUserDismissedAlerts($userId) {
    global $conn;

    btEnsureAlertPreferenceTables();
    $stmt = $conn->prepare(
        "DELETE FROM user_alert_dismissals
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

function getUserAiSettings($userId) {
    global $conn;

    btEnsureAlertPreferenceTables();
    $settings = btDefaultAiSettings();

    $stmt = $conn->prepare(
        "SELECT coach_score_default_visible, weekly_digest_enabled, notification_cadence
         FROM user_ai_settings
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        $settings["coach_score_default_visible"] = (bool) $row["coach_score_default_visible"];
        $settings["weekly_digest_enabled"] = (bool) $row["weekly_digest_enabled"];
        $settings["notification_cadence"] = (string) ($row["notification_cadence"] ?: "weekly");
    }

    return $settings;
}

function saveUserAiSettings($userId, $settings) {
    global $conn;

    btEnsureAlertPreferenceTables();
    $current = getUserAiSettings($userId);
    $merged = array_merge($current, $settings);
    $allowedCadences = ["important_only", "weekly", "month_end"];
    if (!in_array($merged["notification_cadence"], $allowedCadences, true)) {
        $merged["notification_cadence"] = "weekly";
    }

    $coachVisible = $merged["coach_score_default_visible"] ? 1 : 0;
    $weeklyDigest = $merged["weekly_digest_enabled"] ? 1 : 0;
    $cadence = $merged["notification_cadence"];

    $stmt = $conn->prepare(
        "INSERT INTO user_ai_settings (
            user_id, coach_score_default_visible, weekly_digest_enabled, notification_cadence
         )
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            coach_score_default_visible = VALUES(coach_score_default_visible),
            weekly_digest_enabled = VALUES(weekly_digest_enabled),
            notification_cadence = VALUES(notification_cadence)"
    );
    $stmt->bind_param("iiis", $userId, $coachVisible, $weeklyDigest, $cadence);
    $stmt->execute();

    return getUserAiSettings($userId);
}
