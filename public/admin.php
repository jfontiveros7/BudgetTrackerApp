<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user_email"])) {
    header("Location: login.php");
    exit;
}

$userEmail = $_SESSION["user_email"];

$isAdmin = ($userEmail === "admin@example.com");
if (!$isAdmin) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

$totalUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()["c"] ?? 0;
$totalTransactions = $conn->query("SELECT COUNT(*) AS c FROM transactions")->fetch_assoc()["c"] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex">
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col p-6">
        <h1 class="text-xl font-semibold mb-8">Budget Tracker App</h1>
        <nav class="space-y-2">
            <a href="dashboard.php" class="block px-3 py-2 rounded hover:bg-slate-800">Dashboard</a>
            <a href="add_transaction.php" class="block px-3 py-2 rounded hover:bg-slate-800">Add Transaction</a>
            <a href="settings.php" class="block px-3 py-2 rounded hover:bg-slate-800">Settings</a>
            <a href="admin.php" class="block px-3 py-2 rounded bg-slate-800 text-slate-100">Admin</a>
        </nav>
        <div class="mt-auto pt-6 border-t border-slate-800">
            <p class="text-sm text-slate-400">Admin Access</p>
            <p class="text-sm font-medium mt-1"><?php echo htmlspecialchars($userEmail); ?></p>
            <a href="logout.php" class="text-sm text-red-400 hover:text-red-300 mt-3 inline-block">Logout</a>
        </div>
    </aside>

    <main class="flex-1 p-10">
        <div class="app-status-strip">
            <span class="app-status-pill">admin access: enabled</span>
            <span class="app-status-pill">instance: production view</span>
            <span class="app-status-pill">metrics: live aggregate</span>
        </div>
        <div class="mb-10">
            <p class="app-kicker">Operations / Admin</p>
            <h1 class="text-3xl font-semibold">Admin Dashboard</h1>
            <p class="text-slate-400 text-sm mt-2">High-level visibility into your Budget Tracker App instance.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 max-w-4xl">
            <div class="metric-card bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-xl">
                <p class="text-sm text-slate-400">Total Users</p>
                <p class="text-3xl font-semibold mt-2"><?php echo (int) $totalUsers; ?></p>
            </div>
            <div class="metric-card bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-xl">
                <p class="text-sm text-slate-400">Total Transactions</p>
                <p class="text-3xl font-semibold mt-2"><?php echo (int) $totalTransactions; ?></p>
            </div>
        </div>

        <a href="dashboard.php" class="inline-flex items-center justify-center rounded-lg bg-slate-800 px-4 py-2 text-sm text-slate-200 transition hover:bg-slate-700">
            Back to Dashboard
        </a>
    </main>
</body>
</html>
