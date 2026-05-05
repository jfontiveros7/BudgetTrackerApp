<?php
// AI-powered Excel import endpoint
header("Content-Type: application/json");
session_start();

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

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["error" => "No file uploaded or upload error"]);
    exit;
}

$tmpPath = $_FILES['file']['tmp_name'];
$origName = $_FILES['file']['name'];

// Call Python AI script to process the spreadsheet
$command = escapeshellcmd("python -m agent_sdk.import_excel_ai " . escapeshellarg($tmpPath));
$output = shell_exec($command);

if (!$output) {
    http_response_code(500);
    echo json_encode(["error" => "AI import failed or no output"]);
    exit;
}

$result = json_decode($output, true);
if (!$result || !empty($result['error'])) {
    http_response_code(500);
    echo json_encode(["error" => $result['error'] ?? 'Unknown AI error', "details" => $output]);
    exit;
}

echo json_encode(["message" => "Import successful", "details" => $result]);
