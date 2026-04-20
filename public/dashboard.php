<?php
session_start();
if (!isset($_SESSION["user_id"], $_SESSION["user_email"])) {
    header("Location: login.php");
    exit;
}
$userId = (int) $_SESSION["user_id"];
$userName = $_SESSION["user_name"] ?? "";
$userEmail = $_SESSION["user_email"];
$isAdmin = ($userEmail === "admin@example.com");
require_once "../src/analytics.php";
require_once "../src/agent.php";
require_once "../src/transactions.php";

$summary = getUserSummary($userId);
$chartData = getUserChartData($userId);
$agentReport = getBudgetAgentReport($userId);
$signalSnapshot = getBudgetSignalSnapshot($userId);
$dashboardAlerts = getDashboardAlerts($userId);
$recentTransactions = getRecentTransactions($userId, 50);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Badget Tracker App by Konticode Labs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex">
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col p-6">
        <h1 class="text-xl font-semibold mb-8">Badget Tracker App by Konticode Labs</h1>

        <nav class="space-y-2">
            <a href="dashboard.php" class="block px-3 py-2 rounded bg-slate-800 text-slate-100">Dashboard</a>
            <a href="add_transaction.php" class="block px-3 py-2 rounded hover:bg-slate-800">Add Transaction</a>
            <a href="settings.php" class="block px-3 py-2 rounded hover:bg-slate-800">Settings</a>
            <?php if ($isAdmin): ?>
                <a href="admin.php" class="block px-3 py-2 rounded hover:bg-slate-800">Admin</a>
            <?php endif; ?>
        </nav>

        <div class="mt-auto pt-6 border-t border-slate-800">
            <p class="text-sm text-slate-400">Logged in as</p>
            <p class="text-sm font-medium"><?php echo htmlspecialchars($userEmail); ?></p>
            <a href="logout.php" class="text-sm text-red-400 hover:text-red-300 mt-3 inline-block">Logout</a>
        </div>
    </aside>

    <main class="flex-1 p-10">
        <div class="flex flex-col gap-3 mb-10 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-semibold">Dashboard</h2>
                <p class="text-slate-400 text-sm mt-2">
                    Welcome back, <?php echo htmlspecialchars($userName ?: $userEmail); ?>
                </p>
            </div>
            <a href="add_transaction.php" class="inline-flex items-center justify-center rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-slate-950 transition hover:bg-emerald-400">
                Add Transaction
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-xl">
                <p class="text-sm text-slate-400">Total Income</p>
                <p class="text-3xl font-semibold text-emerald-400 mt-2">
                    $<?php echo number_format($summary["total_income"], 2); ?>
                </p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-xl">
                <p class="text-sm text-slate-400">Total Expenses</p>
                <p class="text-3xl font-semibold text-rose-400 mt-2">
                    $<?php echo number_format($summary["total_expense"], 2); ?>
                </p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-xl">
                <p class="text-sm text-slate-400">Net Balance</p>
                <p class="text-3xl font-semibold mt-2 <?php echo $summary["net"] >= 0 ? 'text-emerald-400' : 'text-rose-400'; ?>">
                    $<?php echo number_format($summary["net"], 2); ?>
                </p>
            </div>
        </div>

        <?php if (!empty($dashboardAlerts)): ?>
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mb-10">
                <div class="flex flex-col gap-2 mb-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Alerts</h3>
                        <p class="text-slate-400 text-sm mt-2">Live dashboard alerts based on your budgets, forecast, and recent spending behavior.</p>
                    </div>
                    <button
                        id="alertPreferencesToggle"
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-950 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800"
                        aria-expanded="false"
                        aria-controls="alertPreferencesPanel"
                    >
                        Alert Preferences
                    </button>
                </div>

                <div id="alertPreferencesPanel" class="hidden rounded-xl border border-slate-800 bg-slate-950/60 p-5 mb-6">
                    <h4 class="text-sm font-semibold text-slate-200">Choose Which Alerts To Show</h4>
                    <p class="text-sm text-slate-400 mt-1">Your choices are saved in this browser so the dashboard stays calm and relevant.</p>

                    <div class="grid grid-cols-1 gap-3 mt-4 md:grid-cols-2 xl:grid-cols-5">
                        <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-900/80 px-4 py-3 text-sm text-slate-300">
                            <input type="checkbox" data-alert-pref="overspending_risk" class="alert-pref-checkbox h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                            Overspending Risk
                        </label>
                        <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-900/80 px-4 py-3 text-sm text-slate-300">
                            <input type="checkbox" data-alert-pref="forecast" class="alert-pref-checkbox h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                            Forecast
                        </label>
                        <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-900/80 px-4 py-3 text-sm text-slate-300">
                            <input type="checkbox" data-alert-pref="budget_threshold" class="alert-pref-checkbox h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                            Budget Thresholds
                        </label>
                        <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-900/80 px-4 py-3 text-sm text-slate-300">
                            <input type="checkbox" data-alert-pref="subscription_review" class="alert-pref-checkbox h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                            Subscription Review
                        </label>
                        <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-900/80 px-4 py-3 text-sm text-slate-300">
                            <input type="checkbox" data-alert-pref="coach_recommendation" class="alert-pref-checkbox h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                            Coach Recommendation
                        </label>
                    </div>

                    <div class="mt-4">
                        <button
                            id="resetDismissedAlerts"
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800"
                        >
                            Restore Dismissed Alerts
                        </button>
                    </div>
                </div>

                <div id="dashboardAlertsGrid" class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <?php foreach ($dashboardAlerts as $alert): ?>
                        <div
                            class="dashboard-alert-card rounded-xl border px-5 py-4 <?php echo $alert["level"] === "critical" ? "border-rose-500/30 bg-rose-500/10" : ($alert["level"] === "warning" ? "border-amber-500/30 bg-amber-500/10" : "border-sky-500/30 bg-sky-500/10"); ?>"
                            data-alert-id="<?php echo htmlspecialchars($alert["id"]); ?>"
                            data-alert-type="<?php echo htmlspecialchars($alert["type"]); ?>"
                        >
                            <div class="flex items-center justify-between gap-3">
                                <h4 class="text-sm font-semibold <?php echo $alert["level"] === "critical" ? "text-rose-300" : ($alert["level"] === "warning" ? "text-amber-300" : "text-sky-300"); ?>">
                                    <?php echo htmlspecialchars($alert["title"]); ?>
                                </h4>
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full border px-2 py-1 text-[11px] uppercase tracking-[0.2em] <?php echo $alert["level"] === "critical" ? "border-rose-500/30 text-rose-200" : ($alert["level"] === "warning" ? "border-amber-500/30 text-amber-200" : "border-sky-500/30 text-sky-200"); ?>">
                                        <?php echo htmlspecialchars($alert["level"]); ?>
                                    </span>
                                    <button
                                        type="button"
                                        class="dismiss-alert inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-950 px-2 py-1 text-xs font-medium text-slate-300 transition hover:bg-slate-800"
                                        data-dismiss-alert="<?php echo htmlspecialchars($alert["id"]); ?>"
                                    >
                                        Dismiss
                                    </button>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-slate-200"><?php echo htmlspecialchars($alert["message"]); ?></p>
                            <p class="mt-3 text-sm text-slate-400"><?php echo htmlspecialchars($alert["action"]); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="alertsEmptyState" class="hidden rounded-xl border border-dashed border-slate-700 bg-slate-950/50 px-4 py-10 text-center mt-4">
                    <p class="text-sm text-slate-400">No alerts match your current preferences right now.</p>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mb-10">
            <h3 class="text-xl font-semibold mb-4">Income vs Expense (Last 30 Days)</h3>
            <canvas id="incomeExpenseChart" height="120"></canvas>
        </div>

        <div class="mb-10">
            <button
                id="coachScoreToggle"
                type="button"
                class="inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800"
                aria-expanded="false"
                aria-controls="coachScoreSection"
            >
                Show Coach Score
            </button>
        </div>

        <div id="coachScoreSection" class="hidden bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mb-10">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-400">AI Budget Coach</p>
                    <h3 class="text-xl font-semibold mt-2"><?php echo htmlspecialchars($agentReport["headline"]); ?></h3>
                    <p class="text-slate-400 text-sm mt-2">A smart summary based on your recent spending, income, and transaction habits.</p>
                </div>
                <div class="rounded-2xl border border-slate-700 bg-slate-950 px-5 py-4 text-center min-w-32">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Score</p>
                    <p class="mt-2 text-3xl font-semibold <?php echo $agentReport["score"] >= 70 ? "text-emerald-400" : ($agentReport["score"] >= 45 ? "text-amber-400" : "text-rose-400"); ?>">
                        <?php echo (int) $agentReport["score"]; ?>
                    </p>
                </div>
            </div>

            <?php $scoreBreakdown = $agentReport["score_breakdown"] ?? []; ?>
            <?php if (!empty($scoreBreakdown)): ?>
                <div class="mt-8 rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                    <div class="flex flex-col gap-2 mb-5 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-200">Why This Score?</h4>
                            <p class="text-sm text-slate-400 mt-1">A breakdown of the habits and signals shaping your financial control score.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                        <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Budget Adherence</p>
                            <p class="mt-2 text-2xl font-semibold <?php echo $scoreBreakdown["budget_adherence"] >= 80 ? "text-emerald-400" : ($scoreBreakdown["budget_adherence"] >= 50 ? "text-amber-400" : "text-rose-400"); ?>">
                                <?php echo (int) $scoreBreakdown["budget_adherence"]; ?>
                            </p>
                        </div>
                        <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Category Volatility</p>
                            <p class="mt-2 text-2xl font-semibold <?php echo $scoreBreakdown["category_volatility"] >= 80 ? "text-emerald-400" : ($scoreBreakdown["category_volatility"] >= 50 ? "text-amber-400" : "text-rose-400"); ?>">
                                <?php echo (int) $scoreBreakdown["category_volatility"]; ?>
                            </p>
                        </div>
                        <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Subscription Creep</p>
                            <p class="mt-2 text-2xl font-semibold <?php echo $scoreBreakdown["subscription_creep"] >= 80 ? "text-emerald-400" : ($scoreBreakdown["subscription_creep"] >= 50 ? "text-amber-400" : "text-rose-400"); ?>">
                                <?php echo (int) $scoreBreakdown["subscription_creep"]; ?>
                            </p>
                        </div>
                        <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Savings Rate</p>
                            <p class="mt-2 text-2xl font-semibold <?php echo $scoreBreakdown["savings_rate"] >= 80 ? "text-emerald-400" : ($scoreBreakdown["savings_rate"] >= 50 ? "text-amber-400" : "text-rose-400"); ?>">
                                <?php echo (int) $scoreBreakdown["savings_rate"]; ?>
                            </p>
                        </div>
                        <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Burn Rate vs Income</p>
                            <p class="mt-2 text-2xl font-semibold <?php echo $scoreBreakdown["burn_rate_vs_income"] >= 80 ? "text-emerald-400" : ($scoreBreakdown["burn_rate_vs_income"] >= 50 ? "text-amber-400" : "text-rose-400"); ?>">
                                <?php echo (int) $scoreBreakdown["burn_rate_vs_income"]; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                    <h4 class="text-sm font-semibold text-slate-200 mb-4">Insights</h4>
                    <div class="space-y-3">
                        <?php foreach ($agentReport["insights"] as $insight): ?>
                            <div class="rounded-lg bg-slate-900/80 px-4 py-3 text-sm text-slate-300">
                                <?php echo htmlspecialchars($insight); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                    <h4 class="text-sm font-semibold text-slate-200 mb-4">Recommended Actions</h4>
                    <div class="space-y-3">
                        <?php foreach ($agentReport["actions"] as $action): ?>
                            <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/5 px-4 py-3 text-sm text-slate-300">
                                <?php echo htmlspecialchars($action); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                <div class="flex flex-col gap-2 mb-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-200">Financial Snapshot</h4>
                        <p class="text-sm text-slate-400 mt-1">Live signals the agent uses for insights, warnings, and suggestions.</p>
                    </div>
                    <span class="rounded-full border px-3 py-1 text-xs <?php echo $signalSnapshot["overspending_risk"] === "high" ? "border-rose-500/30 bg-rose-500/10 text-rose-300" : ($signalSnapshot["overspending_risk"] === "medium" ? "border-amber-500/30 bg-amber-500/10 text-amber-300" : "border-emerald-500/30 bg-emerald-500/10 text-emerald-300"); ?>">
                        Overspending Risk: <?php echo ucfirst($signalSnapshot["overspending_risk"]); ?>
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Income (30d)</p>
                        <p class="mt-2 text-2xl font-semibold text-emerald-400">$<?php echo number_format($signalSnapshot["income_30d"], 2); ?></p>
                    </div>
                    <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Expenses (30d)</p>
                        <p class="mt-2 text-2xl font-semibold text-rose-400">$<?php echo number_format($signalSnapshot["expenses_30d"], 2); ?></p>
                    </div>
                    <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Net</p>
                        <p class="mt-2 text-2xl font-semibold <?php echo $signalSnapshot["net"] >= 0 ? "text-emerald-400" : "text-rose-400"; ?>">
                            $<?php echo number_format($signalSnapshot["net"], 2); ?>
                        </p>
                    </div>
                    <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Forecast EOM Balance</p>
                        <p class="mt-2 text-2xl font-semibold <?php echo $signalSnapshot["forecast_eom_balance"] >= 0 ? "text-emerald-400" : "text-rose-400"; ?>">
                            $<?php echo number_format($signalSnapshot["forecast_eom_balance"], 2); ?>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-2">
                    <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-3">Top Categories</p>
                        <?php if (empty($signalSnapshot["top_categories"])): ?>
                            <p class="text-sm text-slate-400">No expense categories detected yet.</p>
                        <?php else: ?>
                            <div class="space-y-2">
                                <?php foreach ($signalSnapshot["top_categories"] as $category): ?>
                                    <div class="flex items-center justify-between rounded-lg bg-slate-950/70 px-3 py-2 text-sm">
                                        <span class="text-slate-300"><?php echo htmlspecialchars($category["category"]); ?></span>
                                        <span class="font-medium text-slate-100">$<?php echo number_format((float) $category["total"], 2); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-3">Subscriptions</p>
                        <?php if (empty($signalSnapshot["subscriptions"])): ?>
                            <p class="text-sm text-slate-400">No subscriptions detected from the recent transaction data.</p>
                        <?php else: ?>
                            <div class="space-y-2">
                                <?php foreach ($signalSnapshot["subscriptions"] as $subscription): ?>
                                    <div class="flex items-center justify-between rounded-lg bg-slate-950/70 px-3 py-2 text-sm">
                                        <span class="text-slate-300"><?php echo htmlspecialchars($subscription["category"]); ?></span>
                                        <span class="font-medium text-slate-100">$<?php echo number_format((float) $subscription["total"], 2); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mb-10">
            <div class="flex flex-col gap-3 mb-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-xl font-semibold">Ask Budget Coach</h3>
                    <p class="text-slate-400 text-sm mt-2">Ask questions about spending, savings, cash flow, or where to improve next.</p>
                </div>
                <span class="rounded-full border border-slate-700 bg-slate-950 px-3 py-1 text-xs text-slate-400">
                    AI features by Konticode Labs, available when OpenAI is configured
                </span>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                <div id="coachMessages" class="space-y-3 mb-4">
                    <div class="rounded-lg border border-slate-800 bg-slate-900 px-4 py-3 text-sm text-slate-300">
                        Ask something like "What category is draining my money the fastest?", "How much can I safely save this month?", or "Which subscriptions should I cancel first?"
                    </div>
                </div>

                <form id="coachForm" class="flex flex-col gap-3 md:flex-row">
                    <input
                        id="coachInput"
                        type="text"
                        placeholder="Ask Budget Coach a question..."
                        class="flex-1 rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-emerald-500 px-5 py-2 text-sm font-medium text-slate-950 transition hover:bg-emerald-400"
                    >
                        Ask
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
            <div class="flex flex-col gap-3 mb-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-xl font-semibold">Recent Transactions</h3>
                    <p class="text-slate-400 text-sm mt-2">View your latest financial activity.</p>
                </div>
                <a href="add_transaction.php" class="inline-block rounded-lg bg-slate-800 px-4 py-2 text-sm text-slate-200 transition hover:bg-slate-700">
                    Add Another
                </a>
            </div>

            <?php if (empty($recentTransactions)): ?>
                <div class="rounded-xl border border-dashed border-slate-700 bg-slate-950/50 px-4 py-10 text-center">
                    <p class="text-sm text-slate-400">No transactions yet. Add your first one to start tracking your budget.</p>
                </div>
            <?php else: ?>
                <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900 shadow-2xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-800/60 backdrop-blur sticky top-0 z-10">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold text-slate-300">Date</th>
                                    <th class="px-5 py-3 text-left font-semibold text-slate-300">Category</th>
                                    <th class="px-5 py-3 text-left font-semibold text-slate-300">Description</th>
                                    <th class="px-5 py-3 text-right font-semibold text-slate-300">Amount</th>
                                    <th class="px-5 py-3 text-center font-semibold text-slate-300">Type</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-800">
                                <?php foreach ($recentTransactions as $row): ?>
                                    <tr class="hover:bg-slate-800/40 transition-colors">
                                        <td class="px-5 py-4 text-slate-300">
                                            <?php echo date("M d, Y", strtotime($row["display_date"])); ?>
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center gap-2">
                                                <span class="h-2 w-2 rounded-full <?php echo $row["type"] === "income" ? "bg-emerald-400" : "bg-rose-400"; ?>"></span>
                                                <span class="font-medium text-slate-200">
                                                    <?php echo htmlspecialchars($row["category_name"]); ?>
                                                </span>
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-slate-400">
                                            <?php echo htmlspecialchars($row["description"] ?: "No description"); ?>
                                        </td>

                                        <td class="px-5 py-4 text-right font-semibold <?php echo $row["type"] === "income" ? "text-emerald-400" : "text-rose-400"; ?>">
                                            $<?php echo number_format((float) $row["amount"], 2); ?>
                                        </td>

                                        <td class="px-5 py-4 text-center">
                                            <span class="px-3 py-1 text-xs font-medium rounded-full border <?php echo $row["type"] === "income" ? "bg-emerald-500/15 text-emerald-400 border-emerald-500/30" : "bg-rose-500/15 text-rose-400 border-rose-500/30"; ?>">
                                                <?php echo ucfirst($row["type"]); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        const chartLabels = <?php echo json_encode($chartData["labels"]); ?>;
        const incomeData = <?php echo json_encode($chartData["income"]); ?>;
        const expenseData = <?php echo json_encode($chartData["expense"]); ?>;

        new Chart(document.getElementById('incomeExpenseChart'), {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Income',
                        data: incomeData,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        tension: 0.4,
                        borderWidth: 2
                    },
                    {
                        label: 'Expense',
                        data: expenseData,
                        borderColor: 'rgb(248, 113, 113)',
                        backgroundColor: 'rgba(248, 113, 113, 0.2)',
                        tension: 0.4,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                plugins: {
                    legend: { labels: { color: '#e5e7eb' } }
                },
                scales: {
                    x: { ticks: { color: '#9ca3af' }, grid: { color: '#1f2937' } },
                    y: { ticks: { color: '#9ca3af' }, grid: { color: '#1f2937' } }
                }
            }
        });

        const coachForm = document.getElementById('coachForm');
        const coachInput = document.getElementById('coachInput');
        const coachMessages = document.getElementById('coachMessages');
        const coachScoreToggle = document.getElementById('coachScoreToggle');
        const coachScoreSection = document.getElementById('coachScoreSection');
        const alertPreferencesToggle = document.getElementById('alertPreferencesToggle');
        const alertPreferencesPanel = document.getElementById('alertPreferencesPanel');
        const alertCards = Array.from(document.querySelectorAll('.dashboard-alert-card'));
        const alertPrefCheckboxes = Array.from(document.querySelectorAll('.alert-pref-checkbox'));
        const resetDismissedAlertsButton = document.getElementById('resetDismissedAlerts');
        const alertsEmptyState = document.getElementById('alertsEmptyState');

        const defaultAlertPreferences = {
            overspending_risk: true,
            forecast: true,
            budget_threshold: true,
            subscription_review: true,
            coach_recommendation: true,
        };
        let alertPreferencesState = { ...defaultAlertPreferences };
        let dismissedAlertsState = [];
        let aiSettingsState = {
            coach_score_default_visible: false,
            weekly_digest_enabled: true,
            notification_cadence: 'weekly',
        };

        async function loadAlertSettings() {
            const response = await fetch('api/alert_preferences.php');
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || 'Could not load alert settings.');
            }

            alertPreferencesState = { ...defaultAlertPreferences, ...(data.preferences || {}) };
            dismissedAlertsState = Array.isArray(data.dismissed_alerts) ? data.dismissed_alerts : [];
            aiSettingsState = { ...aiSettingsState, ...(data.ai_settings || {}) };
        }

        async function saveAlertPreferences(preferences) {
            const response = await fetch('api/alert_preferences.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'save_preferences',
                    preferences,
                }),
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || 'Could not save alert preferences.');
            }

            alertPreferencesState = { ...defaultAlertPreferences, ...(data.preferences || {}) };
            dismissedAlertsState = Array.isArray(data.dismissed_alerts) ? data.dismissed_alerts : dismissedAlertsState;
            aiSettingsState = { ...aiSettingsState, ...(data.ai_settings || {}) };
        }

        async function dismissAlert(alertId) {
            const response = await fetch('api/alert_preferences.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'dismiss_alert',
                    alert_id: alertId,
                }),
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || 'Could not dismiss alert.');
            }

            dismissedAlertsState = Array.isArray(data.dismissed_alerts) ? data.dismissed_alerts : dismissedAlertsState;
        }

        async function restoreDismissedAlerts() {
            const response = await fetch('api/alert_preferences.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'restore_dismissed',
                }),
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || 'Could not restore dismissed alerts.');
            }

            dismissedAlertsState = Array.isArray(data.dismissed_alerts) ? data.dismissed_alerts : [];
        }

        async function saveAiSettings(settings) {
            const response = await fetch('api/alert_preferences.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'save_ai_settings',
                    ai_settings: settings,
                }),
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || 'Could not save AI settings.');
            }

            aiSettingsState = { ...aiSettingsState, ...(data.ai_settings || {}) };
        }

        function applyAlertVisibility() {
            if (!alertCards.length) {
                return;
            }

            const dismissed = new Set(dismissedAlertsState);
            let visibleCount = 0;

            alertCards.forEach((card) => {
                const type = card.dataset.alertType;
                const id = card.dataset.alertId;
                const isEnabled = alertPreferencesState[type] !== false;
                const isDismissed = dismissed.has(id);
                const shouldShow = isEnabled && !isDismissed;
                card.classList.toggle('hidden', !shouldShow);
                if (shouldShow) {
                    visibleCount += 1;
                }
            });

            alertsEmptyState?.classList.toggle('hidden', visibleCount > 0);
        }

        function syncAlertPreferenceInputs() {
            alertPrefCheckboxes.forEach((checkbox) => {
                checkbox.checked = alertPreferencesState[checkbox.dataset.alertPref] !== false;
            });
        }

        function setCoachScoreVisibility(isVisible) {
            coachScoreSection.classList.toggle('hidden', !isVisible);
            coachScoreToggle.setAttribute('aria-expanded', isVisible ? 'true' : 'false');
            coachScoreToggle.textContent = isVisible ? 'Hide Coach Score' : 'Show Coach Score';
        }

        coachScoreToggle.addEventListener('click', async () => {
            const isVisible = !coachScoreSection.classList.contains('hidden');
            const nextVisible = !isVisible;
            setCoachScoreVisibility(nextVisible);
            try {
                await saveAiSettings({
                    coach_score_default_visible: nextVisible,
                });
            } catch (error) {
                appendCoachMessage('Coach visibility could not be saved right now.', 'assistant');
            }
        });

        if (alertPreferencesToggle && alertPreferencesPanel) {
            alertPreferencesToggle.addEventListener('click', () => {
                const isExpanded = alertPreferencesToggle.getAttribute('aria-expanded') === 'true';
                alertPreferencesToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                alertPreferencesPanel.classList.toggle('hidden', isExpanded);
            });
        }

        alertPrefCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', async () => {
                const nextPreferences = { ...alertPreferencesState, [checkbox.dataset.alertPref]: checkbox.checked };
                alertPreferencesState = nextPreferences;
                applyAlertVisibility();
                try {
                    await saveAlertPreferences(nextPreferences);
                    syncAlertPreferenceInputs();
                    applyAlertVisibility();
                } catch (error) {
                    appendCoachMessage('Alert preferences could not be saved right now.', 'assistant');
                }
            });
        });

        document.querySelectorAll('.dismiss-alert').forEach((button) => {
            button.addEventListener('click', async () => {
                const alertId = button.dataset.dismissAlert;
                dismissedAlertsState = Array.from(new Set([...dismissedAlertsState, alertId]));
                applyAlertVisibility();
                try {
                    await dismissAlert(alertId);
                    applyAlertVisibility();
                } catch (error) {
                    appendCoachMessage('This alert could not be dismissed right now.', 'assistant');
                }
            });
        });

        resetDismissedAlertsButton?.addEventListener('click', async () => {
            dismissedAlertsState = [];
            applyAlertVisibility();
            try {
                await restoreDismissedAlerts();
                applyAlertVisibility();
            } catch (error) {
                appendCoachMessage('Dismissed alerts could not be restored right now.', 'assistant');
            }
        });

        function appendCoachMessage(text, role) {
            const message = document.createElement('div');
            message.className = role === 'user'
                ? 'rounded-lg border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-slate-200'
                : 'rounded-lg border border-slate-800 bg-slate-900 px-4 py-3 text-sm text-slate-300';
            message.textContent = text;
            coachMessages.appendChild(message);
        }

        (async () => {
            try {
                await loadAlertSettings();
            } catch (error) {
                appendCoachMessage('Account-level alert settings could not be loaded, so local defaults are being used.', 'assistant');
            }
            setCoachScoreVisibility(Boolean(aiSettingsState.coach_score_default_visible));
            syncAlertPreferenceInputs();
            applyAlertVisibility();
        })();

        async function requestCoachReply(url, message) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message })
            });

            const rawText = await response.text();
            let data = null;

            try {
                data = JSON.parse(rawText);
            } catch (error) {
                throw new Error(`Non-JSON response from ${url}: ${rawText.slice(0, 200)}`);
            }

            return { response, data };
        }

        coachForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const message = coachInput.value.trim();
            if (!message) {
                return;
            }

            appendCoachMessage(message, 'user');
            coachInput.value = '';

            const loading = document.createElement('div');
            loading.className = 'rounded-lg border border-slate-800 bg-slate-900 px-4 py-3 text-sm text-slate-400';
            loading.textContent = 'Budget Coach is thinking...';
            coachMessages.appendChild(loading);

            try {
                let coachResult;

                try {
                    coachResult = await requestCoachReply('api/agent_sdk.php', message);
                } catch (error) {
                    coachResult = null;
                }

                if (!coachResult || !coachResult.response.ok || !coachResult.data.reply) {
                    coachResult = await requestCoachReply('api/coach.php', message);
                }

                loading.remove();

                if (!coachResult.response.ok) {
                    appendCoachMessage(coachResult.data.error || 'Something went wrong while contacting Budget Coach.', 'assistant');
                    return;
                }

                appendCoachMessage(coachResult.data.reply || 'No reply was generated.', 'assistant');
            } catch (error) {
                loading.remove();
                appendCoachMessage('Budget Coach could not be reached right now.', 'assistant');
            }
        });
    </script>
</body>
</html>
