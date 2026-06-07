<?php
require_once __DIR__ . "/../../src/resend.php";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") !== "POST") {
    btJsonResponse([
        "ok" => false,
        "error" => "Method not allowed.",
    ], 405);
}

$lead = btNormalizeLeadPayload(btReadJsonInput());
$validationError = btValidateLeadPayload($lead);
if ($validationError !== "") {
    btJsonResponse([
        "ok" => false,
        "error" => $validationError,
    ], 400);
}

$result = btSendLeadEmail($lead);
btJsonResponse([
    "ok" => $result["ok"],
    "error" => $result["error"] ?? null,
    "message" => $result["ok"] ? "Thanks. We will reply within one business day." : null,
    "resend" => $result["resend"] ?? null,
], $result["status"]);
?>
