<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/schema_support.php';

function btAgentDateExpr() {
    return btHasTransactionDate() ? "transaction_date" : "DATE(created_at)";
}

function btClampScore($value, $min = 0.0, $max = 100.0) {
    return max($min, min($max, $value));
}

function btHeadlineForScore($score) {
    if ($score < 20) {
        return "Critical overspending";
    }
    if ($score < 40) {
        return "Needs improvement";
    }
    if ($score < 60) {
        return "Stable but inefficient";
    }
    if ($score < 80) {
        return "Good habits forming";
    }
    return "Excellent financial control";
}

function btBuildDashboardAlert($type, $level, $title, $message, $action) {
    return [
        "id" => md5($type . "|" . $level . "|" . $title . "|" . $message . "|" . $action),
        "type" => $type,
        "level" => $level,
        "title" => $title,
        "message" => $message,
        "action" => $action,
    ];
}

function getDashboardAlerts($userId) {
    global $conn;

    $dateExpr = btAgentDateExpr();
    $alerts = [];
    $snapshot = getBudgetSignalSnapshot($userId);
    $report = getBudgetAgentReport($userId);

    if ($snapshot["overspending_risk"] === "high") {
        $alerts[] = btBuildDashboardAlert(
            "overspending_risk",
            "critical",
            "Critical Overspending Risk",
            "Your current spending pattern is putting this month under pressure.",
            "Review your biggest expense categories and pause non-essential spending today."
        );
    } elseif ($snapshot["overspending_risk"] === "medium") {
        $alerts[] = btBuildDashboardAlert(
            "overspending_risk",
            "warning",
            "Overspending Warning",
            "Your budgets or recent spending trend show a moderate risk of going over target.",
            "Tighten one category this week to keep the month on track."
        );
    }

    if ($snapshot["forecast_eom_balance"] < 0) {
        $alerts[] = btBuildDashboardAlert(
            "forecast",
            "critical",
            "Negative Month-End Forecast",
            "At your current pace, you are projected to end the month at $" . number_format(abs($snapshot["forecast_eom_balance"]), 2) . " below zero.",
            "Cut one flexible expense category or add income before month-end."
        );
    }

    if (!empty($snapshot["subscriptions"])) {
        $topSubscription = $snapshot["subscriptions"][0];
        if ((float) $topSubscription["total"] >= 25) {
            $alerts[] = btBuildDashboardAlert(
                "subscription_review",
                "info",
                "Subscription Review",
                $topSubscription["category"] . " is one of your larger recurring costs at $" . number_format((float) $topSubscription["total"], 2) . ".",
                "Check whether this subscription is still worth keeping this month."
            );
        }
    }

    if (!empty($report["actions"][0])) {
        $alerts[] = btBuildDashboardAlert(
            "coach_recommendation",
            "info",
            "Coach Recommendation",
            $report["actions"][0],
            "Use Budget Coach for a more detailed plan if you want next steps."
        );
    }

    $stmt = $conn->prepare(
        "SELECT
            b.category,
            b.amount,
            COALESCE(SUM(
                CASE
                    WHEN t.type = 'expense' AND " . ($dateExpr === "transaction_date" ? "t.transaction_date" : "DATE(t.created_at)") . " >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                    THEN t.amount
                    ELSE 0
                END
            ), 0) AS spent
         FROM budgets b
         LEFT JOIN transactions t
            ON t.user_id = b.user_id
           AND t.category = b.category
         WHERE b.user_id = ?
         GROUP BY b.id, b.category, b.amount
         ORDER BY spent DESC"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $budgetResult = $stmt->get_result();
    while ($row = $budgetResult->fetch_assoc()) {
        $limit = (float) ($row["amount"] ?? 0);
        if ($limit <= 0) {
            continue;
        }

        $spent = (float) ($row["spent"] ?? 0);
        $used = ($spent / $limit) * 100;
        if ($used >= 80) {
            $alerts[] = btBuildDashboardAlert(
                "budget_threshold",
                $used >= 100 ? "critical" : "warning",
                $row["category"] . " Budget Alert",
                "You have used " . number_format($used, 1) . "% of your " . $row["category"] . " budget.",
                $used >= 100
                    ? "Freeze spending in this category or raise the budget if it is a planned increase."
                    : "Slow down spending in this category before it goes over budget."
            );
            break;
        }
    }

    return array_slice($alerts, 0, 4);
}

function getBudgetSignalSnapshot($userId) {
    global $conn;
    $dateExpr = btAgentDateExpr();

    $snapshot = [
        "income_30d" => 0.0,
        "expenses_30d" => 0.0,
        "net" => 0.0,
        "top_categories" => [],
        "subscriptions" => [],
        "overspending_risk" => "low",
        "forecast_eom_balance" => 0.0,
    ];

    $stmt = $conn->prepare(
        "SELECT
            COALESCE(SUM(CASE WHEN type = 'income' AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN amount ELSE 0 END), 0) AS income_30d,
            COALESCE(SUM(CASE WHEN type = 'expense' AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN amount ELSE 0 END), 0) AS expenses_30d
         FROM transactions
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $income = (float) ($result["income_30d"] ?? 0);
    $expenses = (float) ($result["expenses_30d"] ?? 0);
    $snapshot["income_30d"] = $income;
    $snapshot["expenses_30d"] = $expenses;
    $snapshot["net"] = $income - $expenses;

    $topCategories = [];
    $stmt = $conn->prepare(
        "SELECT category, SUM(amount) AS total
         FROM transactions
         WHERE user_id = ? AND type = 'expense' AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
         GROUP BY category
         ORDER BY total DESC
         LIMIT 3"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $categoryResult = $stmt->get_result();
    while ($row = $categoryResult->fetch_assoc()) {
        $topCategories[] = [
            "category" => $row["category"],
            "total" => (float) $row["total"],
        ];
    }
    $snapshot["top_categories"] = $topCategories;

    $subscriptions = [];
    $stmt = $conn->prepare(
        "SELECT category, SUM(amount) AS total
         FROM transactions
         WHERE user_id = ?
           AND type = 'expense'
           AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
           AND (
               LOWER(category) LIKE '%subscription%'
               OR LOWER(category) LIKE '%netflix%'
               OR LOWER(category) LIKE '%spotify%'
               OR LOWER(category) LIKE '%membership%'
               OR LOWER(category) LIKE '%software%'
           )
         GROUP BY category
         ORDER BY total DESC"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $subscriptionResult = $stmt->get_result();
    while ($row = $subscriptionResult->fetch_assoc()) {
        $subscriptions[] = [
            "category" => $row["category"],
            "total" => (float) $row["total"],
        ];
    }
    $snapshot["subscriptions"] = $subscriptions;

    $highestBudgetUse = 0.0;
    $stmt = $conn->prepare(
        "SELECT
            b.category,
            b.amount,
            COALESCE(SUM(
                CASE
                    WHEN t.type = 'expense' AND " . ($dateExpr === "transaction_date" ? "t.transaction_date" : "DATE(t.created_at)") . " >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                    THEN t.amount
                    ELSE 0
                END
            ), 0) AS spent
         FROM budgets b
         LEFT JOIN transactions t
            ON t.user_id = b.user_id
           AND t.category = b.category
         WHERE b.user_id = ?
         GROUP BY b.id, b.category, b.amount"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $budgetResult = $stmt->get_result();
    while ($row = $budgetResult->fetch_assoc()) {
        $budgetAmount = (float) ($row["amount"] ?? 0);
        $spent = (float) ($row["spent"] ?? 0);
        if ($budgetAmount > 0) {
            $highestBudgetUse = max($highestBudgetUse, ($spent / $budgetAmount) * 100);
        }
    }

    if ($snapshot["net"] < 0 || $highestBudgetUse >= 100) {
        $snapshot["overspending_risk"] = "high";
    } elseif ($highestBudgetUse >= 80 || ($income > 0 && $expenses >= $income * 0.9)) {
        $snapshot["overspending_risk"] = "medium";
    }

    $today = new DateTimeImmutable("today");
    $dayOfMonth = max(1, (int) $today->format("j"));
    $daysInMonth = (int) $today->format("t");
    $projectedIncome = $income > 0 ? ($income / $dayOfMonth) * $daysInMonth : 0.0;
    $projectedExpenses = $expenses > 0 ? ($expenses / $dayOfMonth) * $daysInMonth : 0.0;
    $snapshot["forecast_eom_balance"] = round($projectedIncome - $projectedExpenses, 2);

    return $snapshot;
}

function getBudgetAgentReport($userId) {
    global $conn;
    $dateExpr = btAgentDateExpr();

    $summary = [
        "income_30" => 0.0,
        "expense_30" => 0.0,
        "expense_7" => 0.0,
        "expense_prev_7" => 0.0,
        "transaction_count_30" => 0,
    ];

    $stmt = $conn->prepare(
        "SELECT
            COALESCE(SUM(CASE WHEN type = 'income' AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN amount ELSE 0 END), 0) AS income_30,
            COALESCE(SUM(CASE WHEN type = 'expense' AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN amount ELSE 0 END), 0) AS expense_30,
            COALESCE(SUM(CASE WHEN type = 'expense' AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN amount ELSE 0 END), 0) AS expense_7,
            COALESCE(SUM(CASE WHEN type = 'expense' AND $dateExpr < DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) THEN amount ELSE 0 END), 0) AS expense_prev_7,
            COUNT(CASE WHEN $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 END) AS transaction_count_30
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
         WHERE user_id = ? AND type = 'expense' AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
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
         WHERE user_id = ? AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $recentCountResult = $stmt->get_result()->fetch_assoc();
    if ($recentCountResult) {
        $recentCount = (int) $recentCountResult["c"];
    }

    $weeklyCategoryShift = null;
    $stmt = $conn->prepare(
        "SELECT
            category,
            COALESCE(SUM(CASE WHEN $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN amount ELSE 0 END), 0) AS current_7,
            COALESCE(SUM(CASE WHEN $dateExpr < DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) THEN amount ELSE 0 END), 0) AS prev_7
         FROM transactions
         WHERE user_id = ?
           AND type = 'expense'
           AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
         GROUP BY category"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $weeklyCategoryResult = $stmt->get_result();
    while ($row = $weeklyCategoryResult->fetch_assoc()) {
        $prev7 = (float) ($row["prev_7"] ?? 0);
        if ($prev7 <= 0) {
            continue;
        }

        $current7 = (float) ($row["current_7"] ?? 0);
        $change = (($current7 - $prev7) / $prev7) * 100;
        if ($weeklyCategoryShift === null || abs($change) > abs($weeklyCategoryShift["change_pct"])) {
            $weeklyCategoryShift = [
                "category" => $row["category"],
                "current_7" => $current7,
                "prev_7" => $prev7,
                "change_pct" => $change,
            ];
        }
    }

    $subscriptionShift = null;
    $stmt = $conn->prepare(
        "SELECT
            category,
            COALESCE(SUM(CASE WHEN $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN amount ELSE 0 END), 0) AS current_30,
            COALESCE(SUM(CASE WHEN $dateExpr < DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 60 DAY) THEN amount ELSE 0 END), 0) AS prev_30
         FROM transactions
         WHERE user_id = ?
           AND type = 'expense'
           AND (
               LOWER(category) LIKE '%subscription%'
               OR LOWER(category) LIKE '%netflix%'
               OR LOWER(category) LIKE '%spotify%'
               OR LOWER(category) LIKE '%membership%'
               OR LOWER(category) LIKE '%software%'
           )
           AND $dateExpr >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)
         GROUP BY category"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $subscriptionShiftResult = $stmt->get_result();
    while ($row = $subscriptionShiftResult->fetch_assoc()) {
        $current30 = (float) ($row["current_30"] ?? 0);
        $prev30 = (float) ($row["prev_30"] ?? 0);
        $delta = $current30 - $prev30;
        if ($delta <= 0) {
            continue;
        }

        if ($subscriptionShift === null || $delta > $subscriptionShift["delta"]) {
            $subscriptionShift = [
                "category" => $row["category"],
                "current_30" => $current30,
                "prev_30" => $prev30,
                "delta" => $delta,
            ];
        }
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

        if ($weeklyCategoryShift && abs($weeklyCategoryShift["change_pct"]) >= 15) {
            $direction = $weeklyCategoryShift["change_pct"] < 0 ? "less" : "more";
            $insights[] = "You spent " . number_format(abs($weeklyCategoryShift["change_pct"]), 1) . "% " . $direction . " on " . $weeklyCategoryShift["category"] . " over the last 7 days.";
        }

        if ($subscriptionShift && $subscriptionShift["delta"] >= 1) {
            $insights[] = "Subscription spending is up $" . number_format($subscriptionShift["delta"], 2) . " over the last 30 days, with the biggest increase in " . $subscriptionShift["category"] . ".";
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

    $budgetUseValues = [];
    $stmt = $conn->prepare(
        "SELECT
            b.amount,
            COALESCE(SUM(
                CASE
                    WHEN t.type = 'expense' AND " . ($dateExpr === "transaction_date" ? "t.transaction_date" : "DATE(t.created_at)") . " >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                    THEN t.amount
                    ELSE 0
                END
            ), 0) AS spent
         FROM budgets b
         LEFT JOIN transactions t
            ON t.user_id = b.user_id
           AND t.category = b.category
         WHERE b.user_id = ?
         GROUP BY b.id, b.amount"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $budgetScoreResult = $stmt->get_result();
    while ($row = $budgetScoreResult->fetch_assoc()) {
        $limit = (float) ($row["amount"] ?? 0);
        if ($limit <= 0) {
            continue;
        }
        $budgetUseValues[] = ((float) ($row["spent"] ?? 0) / $limit) * 100;
    }

    if (empty($budgetUseValues)) {
        $budgetAdherenceScore = 60.0;
    } else {
        $averageUse = array_sum($budgetUseValues) / count($budgetUseValues);
        if ($averageUse <= 80) {
            $budgetAdherenceScore = 100.0;
        } elseif ($averageUse >= 130) {
            $budgetAdherenceScore = 0.0;
        } else {
            $budgetAdherenceScore = btClampScore(100 - (($averageUse - 80) / 50) * 100);
        }
    }

    if ($weeklyCategoryShift === null) {
        $categoryVolatilityScore = 70.0;
    } else {
        $categoryVolatilityScore = btClampScore(100 - min(abs($weeklyCategoryShift["change_pct"]), 100));
    }

    if ($subscriptionShift === null) {
        $subscriptionCreepScore = 85.0;
    } else {
        $subscriptionCreepScore = btClampScore(100 - min(($subscriptionShift["delta"] / 25) * 100, 100));
    }

    if ($summary["income_30"] > 0) {
        $savingsRate = $net30 / $summary["income_30"];
        if ($savingsRate >= 0.2) {
            $savingsRateScore = 100.0;
        } elseif ($savingsRate <= -0.2) {
            $savingsRateScore = 0.0;
        } else {
            $savingsRateScore = btClampScore((($savingsRate + 0.2) / 0.4) * 100);
        }

        $burnRatio = $summary["expense_30"] / max($summary["income_30"], 0.01);
        if ($burnRatio <= 0.7) {
            $burnRateScore = 100.0;
        } elseif ($burnRatio >= 1.2) {
            $burnRateScore = 0.0;
        } else {
            $burnRateScore = btClampScore(100 - (($burnRatio - 0.7) / 0.5) * 100);
        }
    } else {
        $savingsRateScore = $summary["expense_30"] > 0 ? 0.0 : 50.0;
        $burnRateScore = $summary["expense_30"] > 0 ? 0.0 : 50.0;
    }

    $healthScore =
        ($budgetAdherenceScore * 0.30) +
        ($burnRateScore * 0.25) +
        ($savingsRateScore * 0.20) +
        ($categoryVolatilityScore * 0.15) +
        ($subscriptionCreepScore * 0.10);
    $healthScore = (int) round(btClampScore($healthScore));

    return [
        "score" => $healthScore,
        "headline" => btHeadlineForScore($healthScore),
        "insights" => array_slice($insights, 0, 3),
        "actions" => array_slice($actions, 0, 3),
        "score_breakdown" => [
            "budget_adherence" => (int) round($budgetAdherenceScore),
            "category_volatility" => (int) round($categoryVolatilityScore),
            "subscription_creep" => (int) round($subscriptionCreepScore),
            "savings_rate" => (int) round($savingsRateScore),
            "burn_rate_vs_income" => (int) round($burnRateScore),
        ],
    ];
}
