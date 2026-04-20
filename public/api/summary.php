<?php
header("Content-Type: application/json");
session_start();
require_once "../../src/analytics.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$summary = getUserSummary((int) $_SESSION["user_id"]);

echo json_encode($summary);
