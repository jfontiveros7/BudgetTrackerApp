<?php
header("Content-Type: application/json");
session_start();

require_once "../../src/alert_preferences.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$userId = (int) $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo json_encode([
        "preferences" => getUserAlertPreferences($userId),
        "dismissed_alerts" => getUserDismissedAlertIds($userId),
        "ai_settings" => getUserAiSettings($userId),
    ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$action = $input["action"] ?? "";

if ($action === "save_preferences") {
    $preferences = $input["preferences"] ?? [];
    if (!is_array($preferences)) {
        http_response_code(422);
        echo json_encode(["error" => "Preferences must be an object"]);
        exit;
    }

    echo json_encode([
        "preferences" => saveUserAlertPreferences($userId, $preferences),
        "dismissed_alerts" => getUserDismissedAlertIds($userId),
        "ai_settings" => getUserAiSettings($userId),
    ]);
    exit;
}

if ($action === "save_ai_settings") {
    $settings = $input["ai_settings"] ?? [];
    if (!is_array($settings)) {
        http_response_code(422);
        echo json_encode(["error" => "AI settings must be an object"]);
        exit;
    }

    echo json_encode([
        "preferences" => getUserAlertPreferences($userId),
        "dismissed_alerts" => getUserDismissedAlertIds($userId),
        "ai_settings" => saveUserAiSettings($userId, $settings),
    ]);
    exit;
}

if ($action === "dismiss_alert") {
    $alertId = trim((string) ($input["alert_id"] ?? ""));
    if ($alertId === "") {
        http_response_code(422);
        echo json_encode(["error" => "Alert id is required"]);
        exit;
    }

    dismissUserAlert($userId, $alertId);
    echo json_encode([
        "dismissed_alerts" => getUserDismissedAlertIds($userId),
    ]);
    exit;
}

if ($action === "restore_dismissed") {
    restoreUserDismissedAlerts($userId);
    echo json_encode([
        "dismissed_alerts" => [],
    ]);
    exit;
}

http_response_code(422);
echo json_encode(["error" => "Unknown action"]);
