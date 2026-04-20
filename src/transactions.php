<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/categories.php';
require_once __DIR__ . '/schema_support.php';

function addTransaction($user_id, $category, $description, $amount, $type, $transactionDate = null) {
    global $conn;

    $transactionDate = $transactionDate ?: date("Y-m-d");
    $hasCategoryId = btHasV2Categories();
    $hasTransactionDate = btHasTransactionDate();

    if ($hasCategoryId && $hasTransactionDate) {
        $categoryId = getOrCreateCategoryId($category, $type);
        $stmt = $conn->prepare(
            "INSERT INTO transactions (user_id, category, category_id, description, amount, type, transaction_date)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isisdss", $user_id, $category, $categoryId, $description, $amount, $type, $transactionDate);
        return $stmt->execute();
    }

    if ($hasTransactionDate) {
        $stmt = $conn->prepare(
            "INSERT INTO transactions (user_id, category, description, amount, type, transaction_date)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("issdss", $user_id, $category, $description, $amount, $type, $transactionDate);
        return $stmt->execute();
    }

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, category, description, amount, type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $user_id, $category, $description, $amount, $type);
    return $stmt->execute();
}

function getRecentTransactions($user_id, $limit = 50) {
    global $conn;

    $limit = max(1, min(100, (int) $limit));
    $hasCategoryId = btHasV2Categories();
    $hasTransactionDate = btHasTransactionDate();
    $dateColumn = $hasTransactionDate ? "t.transaction_date" : "DATE(t.created_at)";
    $categorySelect = $hasCategoryId ? "COALESCE(c.name, t.category) AS category_name" : "t.category AS category_name";
    $categoryJoin = $hasCategoryId ? "LEFT JOIN categories c ON t.category_id = c.id" : "";
    $sql = "
        SELECT
            t.id,
            t.description,
            t.amount,
            t.type,
            t.created_at,
            $dateColumn AS display_date,
            $categorySelect
        FROM transactions t
        $categoryJoin
        WHERE t.user_id = ?
        ORDER BY " . ($hasTransactionDate ? "t.transaction_date" : "t.created_at") . " DESC, t.id DESC
        LIMIT $limit
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}
?>
