// --- Bulk Actions for Transactions (admin only) ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user_role"]) && $_SESSION["user_role"] === 'admin' && isset($_GET["action"])) {
    require_once "../../src/transactions.php";
    $input = json_decode(file_get_contents("php://input"), true);
    // Bulk delete transactions
    if ($_GET["action"] === "bulk_delete") {
        $ids = $input["ids"] ?? [];
        if (is_array($ids) && count($ids) > 0) {
            global $conn;
            $in = implode(',', array_fill(0, count($ids), '?'));
            $types = str_repeat('i', count($ids));
            $stmt = $conn->prepare("DELETE FROM transactions WHERE id IN ($in)");
            $stmt->bind_param($types, ...$ids);
            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Failed to delete transactions."]);
            }
        } else {
            http_response_code(422);
            echo json_encode(["error" => "Missing transaction ids."]);
        }
        exit;
    }
    // Bulk export transactions (CSV)
    if ($_GET["action"] === "bulk_export") {
        global $conn;
        $result = $conn->query("SELECT id, user_id, category, description, amount, type, created_at FROM transactions ORDER BY id ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transactions_export.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, array_keys($rows[0] ?? []));
        foreach ($rows as $r) fputcsv($out, $r);
        fclose($out);
        exit;
    }
}
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
