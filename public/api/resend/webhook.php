<?php
require_once __DIR__ . "/../../../src/resend.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
    btJsonResponse([
        "ok" => false,
        "error" => "Method not allowed.",
    ], 405);
}

$rawPayload = file_get_contents("php://input") ?: "";
$headers = function_exists("getallheaders")
    ? array_change_key_case(getallheaders(), CASE_LOWER)
    : [];

$result = btHandleResendWebhook($rawPayload, $headers);
btJsonResponse([
    "ok" => $result["ok"],
    "error" => $result["error"] ?? null,
], $result["status"]);
?>
