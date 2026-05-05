<?php
header("Content-Type: application/json");
session_start();
require_once "../../src/categories.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}


// Category CRUD for admin
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user_role"]) && $_SESSION["user_role"] === 'admin') {
    require_once "../../src/categories.php";
    $input = json_decode(file_get_contents("php://input"), true);
    // Create category
    if (isset($_GET["action"]) && $_GET["action"] === "create") {
        $name = trim($input["name"] ?? "");
        $type = in_array($input["type"] ?? '', ['income','expense']) ? $input["type"] : 'expense';
        $icon = trim($input["icon"] ?? "");
        $color = trim($input["color"] ?? "");
        if ($name) {
            $id = createCategory($name, $type, $icon, $color);
            if ($id) echo json_encode(["success" => true, "id" => $id]);
            else { http_response_code(400); echo json_encode(["error" => "Failed to create category."]); }
        } else {
            http_response_code(422); echo json_encode(["error" => "Missing name."]);
        }
        exit;
    }
    // Update category
    if (isset($_GET["action"]) && $_GET["action"] === "update") {
        $id = (int) ($input["id"] ?? 0);
        $name = trim($input["name"] ?? "");
        $type = in_array($input["type"] ?? '', ['income','expense']) ? $input["type"] : 'expense';
        $icon = trim($input["icon"] ?? "");
        $color = trim($input["color"] ?? "");
        if ($id && $name) {
            $ok = updateCategory($id, $name, $type, $icon, $color);
            if ($ok) echo json_encode(["success" => true]);
            else { http_response_code(400); echo json_encode(["error" => "Failed to update category."]); }
        } else {
            http_response_code(422); echo json_encode(["error" => "Missing id or name."]);
        }
        exit;
    }
    // Delete category
    if (isset($_GET["action"]) && $_GET["action"] === "delete") {
        $id = (int) ($input["id"] ?? 0);
        if ($id) {
            $ok = deleteCategory($id);
            if ($ok) echo json_encode(["success" => true]);
            else { http_response_code(400); echo json_encode(["error" => "Failed to delete category."]); }
        } else {
            http_response_code(422); echo json_encode(["error" => "Missing id."]);
        }
        exit;
    }
}

$type = isset($_GET["type"]) ? $_GET["type"] : null;
$categories = getCategories($type);
echo json_encode(["categories" => $categories]);
