<?php
header("Content-Type: application/json");
session_start();
require_once "../../src/transactions.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = (int) $_SESSION["user_id"];
echo json_encode(getRecentTransactions($user_id, 50));
