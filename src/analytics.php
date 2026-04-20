<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/schema_support.php';

function getUserSummary($userId) {
    global $conn;

    $stmt = $conn->prepare(
        "SELECT
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) AS total_income,
            COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS total_expense
         FROM transactions
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    $summary = $result->fetch_assoc();

    $summary["total_income"] = (float) $summary["total_income"];
    $summary["total_expense"] = (float) $summary["total_expense"];
    $summary["net"] = $summary["total_income"] - $summary["total_expense"];

    return $summary;
}

function getUserChartData($userId) {
    global $conn;

    $hasTransactionDate = btHasTransactionDate();
    $dateExpr = $hasTransactionDate ? "transaction_date" : "DATE(created_at)";
    $rangeExpr = $hasTransactionDate
        ? "transaction_date >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)"
        : "created_at >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)";

    $labels = [];
    $income = [];
    $expense = [];
    $dailyData = [];

    for ($i = 29; $i >= 0; $i--) {
        $date = date("Y-m-d", strtotime("-$i days"));
        $labels[] = date("M j", strtotime($date));
        $dailyData[$date] = [
            "income" => 0,
            "expense" => 0,
        ];
    }

    $stmt = $conn->prepare(
        "SELECT $dateExpr AS transaction_date, type, SUM(amount) AS total
         FROM transactions
         WHERE user_id = ? AND $rangeExpr
         GROUP BY $dateExpr, type
         ORDER BY transaction_date ASC"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $date = $row["transaction_date"];
        if (isset($dailyData[$date])) {
            $dailyData[$date][$row["type"]] = (float) $row["total"];
        }
    }

    foreach ($dailyData as $day) {
        $income[] = $day["income"];
        $expense[] = $day["expense"];
    }

    return [
        "labels" => $labels,
        "income" => $income,
        "expense" => $expense,
    ];
}
?>
