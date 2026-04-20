<?php
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

$input = json_decode(file_get_contents("php://input"), true);
$message = trim($input["message"] ?? "");

if ($message === "") {
    http_response_code(422);
    echo json_encode(["error" => "Message is required"]);
    exit;
}

$projectRoot = realpath(__DIR__ . "/../..");
if ($projectRoot === false) {
    http_response_code(500);
    echo json_encode(["error" => "Project root not found"]);
    exit;
}

$previousCwd = getcwd();
if ($previousCwd === false || !@chdir($projectRoot)) {
    http_response_code(500);
    echo json_encode(["error" => "Could not switch to project root"]);
    exit;
}

$command = "python -m agent_sdk.main --json " . escapeshellarg($message) . " 2>&1";
$stdout = shell_exec($command);

if ($previousCwd !== false) {
    @chdir($previousCwd);
}

$stdout = trim((string) $stdout);
$stderr = "";

if ($stdout === "") {
    http_response_code(500);
    echo json_encode([
        "error" => "Agent SDK did not return a response",
        "details" => $stderr !== "" ? $stderr : null,
    ]);
    exit;
}

$lines = preg_split("/\r\n|\n|\r/", $stdout);
$jsonLine = null;
foreach ($lines as $line) {
    $candidate = trim($line);
    if ($candidate !== "" && str_starts_with($candidate, "{")) {
        $jsonLine = $candidate;
        break;
    }
}

if ($jsonLine === null) {
    http_response_code(500);
    echo json_encode([
        "error" => "Agent SDK response could not be parsed",
        "raw" => $stdout,
        "details" => $stderr !== "" ? $stderr : null,
    ]);
    exit;
}

$decoded = json_decode($jsonLine, true);
if (is_array($decoded) && isset($decoded["output"])) {
    echo json_encode([
        "reply" => $decoded["output"],
        "mode" => "agent_sdk",
    ]);
    exit;
}

http_response_code(500);
echo json_encode([
    "error" => "Agent SDK response could not be parsed",
    "raw" => $stdout,
    "details" => $stderr !== "" ? $stderr : null,
]);
