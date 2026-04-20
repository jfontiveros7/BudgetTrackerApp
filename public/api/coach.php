<?php
header("Content-Type: application/json");
session_start();
require_once "../../src/ai_coach.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$message = trim($input["message"] ?? "");

if ($message === "") {
    http_response_code(422);
    echo json_encode(["error" => "Message is required"]);
    exit;
}

$result = askBudgetCoach((int) $_SESSION["user_id"], $message);
echo json_encode($result);
