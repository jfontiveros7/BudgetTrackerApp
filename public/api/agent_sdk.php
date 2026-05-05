<?php
header("Content-Type: application/json");
session_start();

if (!isset($_SESSION["user_id"], $_SESSION["user_role"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$userRole = $_SESSION["user_role"];
$action = $_GET["action"] ?? null;
$input = json_decode(file_get_contents("php://input"), true);
if (!is_array($input)) {
    $input = [];
}

function requireAdminRole($userRole) {
    if ($userRole !== "admin") {
        http_response_code(403);
        echo json_encode(["error" => "Forbidden"]);
        exit;
    }
}

function handleUserAdminAction($action, $input) {
    require_once __DIR__ . "/../../src/auth.php";
    global $conn;

    if ($action === "create_user") {
        $name = trim($input["name"] ?? "");
        $email = trim($input["email"] ?? "");
        $password = $input["password"] ?? "";
        $role = in_array($input["role"] ?? "", ["admin", "user"], true) ? $input["role"] : "user";

        if ($name === "" || $email === "" || $password === "") {
            http_response_code(422);
            echo json_encode(["error" => "Missing required fields."]);
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed, $role);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "user_id" => $conn->insert_id]);
            exit;
        }

        http_response_code(400);
        echo json_encode(["error" => "Failed to create user. Email may already exist."]);
        exit;
    }

    if ($action === "update_user") {
        $id = (int) ($input["id"] ?? 0);
        $name = trim($input["name"] ?? "");
        $email = trim($input["email"] ?? "");
        $role = in_array($input["role"] ?? "", ["admin", "user"], true) ? $input["role"] : "user";

        if ($id <= 0 || $name === "" || $email === "") {
            http_response_code(422);
            echo json_encode(["error" => "Missing required fields."]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
            exit;
        }

        http_response_code(400);
        echo json_encode(["error" => "Failed to update user."]);
        exit;
    }

    if ($action === "deactivate_user") {
        $id = (int) ($input["id"] ?? 0);
        if ($id <= 0) {
            http_response_code(422);
            echo json_encode(["error" => "Missing user id."]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE users SET role = 'user', email = CONCAT(email, '.deactivated.', UNIX_TIMESTAMP()) WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
            exit;
        }

        http_response_code(400);
        echo json_encode(["error" => "Failed to deactivate user."]);
        exit;
    }

    if ($action === "reset_user_password") {
        $id = (int) ($input["id"] ?? 0);
        $newPassword = $input["password"] ?? "";

        if ($id <= 0 || $newPassword === "") {
            http_response_code(422);
            echo json_encode(["error" => "Missing user id or password."]);
            exit;
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
            exit;
        }

        http_response_code(400);
        echo json_encode(["error" => "Failed to reset password."]);
        exit;
    }

    if ($action === "bulk_delete_users") {
        $ids = $input["ids"] ?? [];
        if (!is_array($ids) || count($ids) === 0) {
            http_response_code(422);
            echo json_encode(["error" => "Missing user ids."]);
            exit;
        }

        $in = implode(",", array_fill(0, count($ids), "?"));
        $types = str_repeat("i", count($ids));
        $stmt = $conn->prepare("DELETE FROM users WHERE id IN ($in)");
        $stmt->bind_param($types, ...$ids);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
            exit;
        }

        http_response_code(400);
        echo json_encode(["error" => "Failed to delete users."]);
        exit;
    }

    if ($action === "bulk_export_users") {
        $result = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY id ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        header("Content-Type: text/csv");
        header('Content-Disposition: attachment; filename="users_export.csv"');
        $out = fopen("php://output", "w");
        if (!empty($rows)) {
            fputcsv($out, array_keys($rows[0]));
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
        }
        fclose($out);
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && $action === "list_users") {
    requireAdminRole($userRole);
    require_once __DIR__ . "/../../src/auth.php";
    global $conn;

    $result = $conn->query("SELECT id, name, email, role FROM users ORDER BY id ASC");
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode(["users" => $users]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "reset_demo") {
    require_once __DIR__ . "/../../config/database.php";

    $sqlFile = realpath(__DIR__ . "/../../sql/demo_seed.sql");
    if (!$sqlFile || !file_exists($sqlFile)) {
        http_response_code(500);
        echo json_encode(["error" => "Demo seed SQL file not found."]);
        exit;
    }

    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        http_response_code(500);
        echo json_encode(["error" => "Could not read demo seed SQL file."]);
        exit;
    }

    $queries = array_filter(array_map("trim", explode(";", $sql)), static function ($query) {
        return $query !== "" && strpos($query, "--") !== 0 && strpos($query, "/*") !== 0;
    });

    $success = true;
    $error = null;
    foreach ($queries as $query) {
        if ($query === "" || stripos($query, "USE ") === 0) {
            continue;
        }

        if (!$conn->query($query)) {
            $success = false;
            $error = $conn->error;
            break;
        }
    }

    if ($success) {
        echo json_encode(["message" => "Demo data loaded successfully."]);
        exit;
    }

    http_response_code(500);
    echo json_encode(["error" => "Failed to load demo data.", "details" => $error ?? "Unknown error"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && in_array($action, [
    "create_user",
    "update_user",
    "deactivate_user",
    "reset_user_password",
    "bulk_delete_users",
    "bulk_export_users",
], true)) {
    requireAdminRole($userRole);
    handleUserAdminAction($action, $input);
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

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
if ($stdout === "") {
    http_response_code(500);
    echo json_encode([
        "error" => "Agent SDK did not return a response",
        "details" => null,
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
        "details" => null,
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
    "details" => null,
]);
