<?php
require_once __DIR__ . '/../config/database.php';

function getBudgetAgentReport($userId) {
    global $conn;

    $summary = [
        "income_30" => 0.0,
        "expense_30" => 0.0,
        "expense_7" => 0.0,
        "expense_prev_7" => 0.0,
        "transaction_count_30" => 0,
    ];

    $stmt = $conn->prepare(
        "SELECT
            COALESCE(SUM(CASE WHEN type = 'income' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN amount ELSE 0 END), 0) AS income_30,
            COALESCE(SUM(CASE WHEN type = 'expense' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN amount ELSE 0 END), 0) AS expense_30,
            COALESCE(SUM(CASE WHEN type = 'expense' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN amount ELSE 0 END), 0) AS expense_7,
            COALESCE(SUM(CASE WHEN type = 'expense' AND created_at < DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) THEN amount ELSE 0 END), 0) AS expense_prev_7,
            COUNT(CASE WHEN created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 END) AS transaction_count_30
         FROM transactions
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $summary = [
            "income_30" => (float) $result["income_30"],
            "expense_30" => (float) $result["expense_30"],
            "expense_7" => (float) $result["expense_7"],
            "expense_prev_7" => (float) $result["expense_prev_7"],
            "transaction_count_30" => (int) $result["transaction_count_30"],
        ];
    }

    $topCategory = null;
    $stmt = $conn->prepare(
        "SELECT category, SUM(amount) AS total
         FROM transactions
         WHERE user_id = ? AND type = 'expense' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
         GROUP BY category
         ORDER BY total DESC
         LIMIT 1"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $topCategory = $stmt->get_result()->fetch_assoc() ?: null;

    $recentCount = 0;
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS c
         FROM transactions
         WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 3 DAY)"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $recentCountResult = $stmt->get_result()->fetch_assoc();
    if ($recentCountResult) {
        $recentCount = (int) $recentCountResult["c"];
    }

    $insights = [];
    $actions = [];
    $net30 = $summary["income_30"] - $summary["expense_30"];

    if ($summary["transaction_count_30"] === 0) {
        $insights[] = "You do not have enough recent activity yet. Add a few transactions so the coach can spot patterns.";
        $actions[] = "Add your first few income and expense entries to unlock better suggestions.";
    } else {
        if ($net30 >= 0) {
            $insights[] = "You are cash-flow positive over the last 30 days with a net of $" . number_format($net30, 2) . ".";
        } else {
            $insights[] = "You are running a negative balance over the last 30 days by $" . number_format(abs($net30), 2) . ".";
            $actions[] = "Reduce one repeating expense category this week to close the gap faster.";
        }

        if ($topCategory && (float) $topCategory["total"] > 0) {
            $insights[] = "Your largest expense category this month is " . $topCategory["category"] . " at $" . number_format((float) $topCategory["total"], 2) . ".";
            $actions[] = "Review your " . $topCategory["category"] . " spending for one easy cut or limit.";
        }

        if ($summary["expense_prev_7"] > 0) {
            $change = (($summary["expense_7"] - $summary["expense_prev_7"]) / $summary["expense_prev_7"]) * 100;
            if ($change >= 15) {
                $insights[] = "Your expenses are up " . number_format($change, 1) . "% compared with the previous 7-day period.";
                $actions[] = "Set a short-term spending target for the next 7 days to reverse the trend.";
            } elseif ($change <= -15) {
                $insights[] = "Your expenses are down " . number_format(abs($change), 1) . "% compared with the previous 7-day period.";
            }
        }

        if ($recentCount === 0) {
            $actions[] = "You have not logged anything in the last 3 days. Record recent activity to keep insights accurate.";
        }
    }

    if (empty($actions)) {
        $actions[] = "Keep logging transactions consistently so the coach can detect stronger patterns.";
    }

    $healthScore = 50;
    if ($summary["transaction_count_30"] > 0) {
        $healthScore += $net30 >= 0 ? 20 : -15;
        $healthScore += $summary["expense_7"] <= max($summary["expense_prev_7"], 1) ? 10 : -10;
        $healthScore += $recentCount > 0 ? 10 : -5;
    }
    $healthScore = max(0, min(100, $healthScore));

    return [
        "score" => $healthScore,
        "headline" => $healthScore >= 70 ? "Your budget is in a healthy zone." : ($healthScore >= 45 ? "Your budget looks stable, but there is room to improve." : "Your budget needs attention right now."),
        "insights" => array_slice($insights, 0, 3),
        "actions" => array_slice($actions, 0, 3),
    ];
}
