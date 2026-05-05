<?php
header("Content-Type: application/json");
session_start();
require_once "../../src/analytics.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = (int) $_SESSION["user_id"];

if (isset($_GET["action"]) && $_GET["action"] === "summary") {
    $summary = getUserSummary($user_id);
    echo json_encode($summary);
    exit;
}

if (isset($_GET["action"]) && $_GET["action"] === "chart") {
    $chart = getUserChartData($user_id);
    echo json_encode($chart);
    exit;
}

http_response_code(400);
echo json_encode(["error" => "Invalid action"]);
