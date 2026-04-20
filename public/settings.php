<?php
session_start();

require_once "../src/alert_preferences.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = (int) $_SESSION["user_id"];
$userEmail = $_SESSION["user_email"] ?? "";
$isAdmin = ($userEmail === "admin@example.com");
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (($_POST["action"] ?? "") === "save_preferences") {
        $preferences = [
            "overspending_risk" => isset($_POST["overspending_risk"]),
            "forecast" => isset($_POST["forecast"]),
            "budget_threshold" => isset($_POST["budget_threshold"]),
            "subscription_review" => isset($_POST["subscription_review"]),
            "coach_recommendation" => isset($_POST["coach_recommendation"]),
        ];
        saveUserAlertPreferences($userId, $preferences);
        $successMessage = "Alert preferences updated.";
    }

    if (($_POST["action"] ?? "") === "save_ai_settings") {
        $aiSettings = [
            "coach_score_default_visible" => isset($_POST["coach_score_default_visible"]),
            "weekly_digest_enabled" => isset($_POST["weekly_digest_enabled"]),
            "notification_cadence" => trim((string) ($_POST["notification_cadence"] ?? "weekly")),
        ];
        saveUserAiSettings($userId, $aiSettings);
        $successMessage = "AI preferences updated.";
    }

    if (($_POST["action"] ?? "") === "restore_dismissed") {
        restoreUserDismissedAlerts($userId);
        $successMessage = "Dismissed alerts restored.";
    }
}

$preferences = getUserAlertPreferences($userId);
$dismissedAlerts = getUserDismissedAlertIds($userId);
$aiSettings = getUserAiSettings($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Badget Tracker App by Konticode Labs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex">
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col p-6">
        <h1 class="text-xl font-semibold mb-8">Badget Tracker App by Konticode Labs</h1>
        <nav class="space-y-2">
            <a href="dashboard.php" class="block px-3 py-2 rounded hover:bg-slate-800">Dashboard</a>
            <a href="add_transaction.php" class="block px-3 py-2 rounded hover:bg-slate-800">Add Transaction</a>
            <a href="settings.php" class="block px-3 py-2 rounded bg-slate-800 text-slate-100">Settings</a>
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
        <div class="max-w-4xl">
            <div class="mb-8">
                <h2 class="text-3xl font-semibold">Settings</h2>
                <p class="text-slate-400 text-sm mt-2">Control which dashboard alerts you see across your account and restore alerts you previously dismissed.</p>
            </div>

            <?php if ($successMessage !== ""): ?>
                <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-200">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mb-8">
                <div class="flex flex-col gap-2 mb-6">
                    <h3 class="text-xl font-semibold">AI & Alerts</h3>
                    <p class="text-slate-400 text-sm">Choose how visible AI coaching should be by default and how often the app should prepare proactive summaries for you.</p>
                </div>

                <form method="POST" class="space-y-4 mb-8">
                    <input type="hidden" name="action" value="save_ai_settings">

                    <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300">
                        <input type="checkbox" name="coach_score_default_visible" <?php echo !empty($aiSettings["coach_score_default_visible"]) ? "checked" : ""; ?> class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                        <span>
                            <span class="block font-medium text-slate-100">Show Coach Score by Default</span>
                            <span class="block text-slate-400 mt-1">Make the AI Budget Coach score card visible automatically when you open the dashboard.</span>
                        </span>
                    </label>

                    <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300">
                        <input type="checkbox" name="weekly_digest_enabled" <?php echo !empty($aiSettings["weekly_digest_enabled"]) ? "checked" : ""; ?> class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                        <span>
                            <span class="block font-medium text-slate-100">Enable Weekly Digest</span>
                            <span class="block text-slate-400 mt-1">Save your preference for AI-generated summaries like weekly reviews and proactive account check-ins.</span>
                        </span>
                    </label>

                    <div class="rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300">
                        <label for="notification_cadence" class="block font-medium text-slate-100">Notification Cadence</label>
                        <p class="text-slate-400 mt-1">Choose how often future AI digests and smart reminders should surface in your account workflow.</p>
                        <select
                            id="notification_cadence"
                            name="notification_cadence"
                            class="mt-4 w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                            <option value="important_only" <?php echo ($aiSettings["notification_cadence"] ?? "") === "important_only" ? "selected" : ""; ?>>Important Only</option>
                            <option value="weekly" <?php echo ($aiSettings["notification_cadence"] ?? "") === "weekly" ? "selected" : ""; ?>>Weekly</option>
                            <option value="month_end" <?php echo ($aiSettings["notification_cadence"] ?? "") === "month_end" ? "selected" : ""; ?>>Month End</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-medium px-5 py-2.5 text-sm transition">
                            Save AI Settings
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mb-8">
                <div class="flex flex-col gap-2 mb-6">
                    <h3 class="text-xl font-semibold">Alert Preferences</h3>
                    <p class="text-slate-400 text-sm">Choose which alert types should appear on your dashboard. These settings are saved to your account.</p>
                </div>

                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="save_preferences">

                    <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300">
                        <input type="checkbox" name="overspending_risk" <?php echo !empty($preferences["overspending_risk"]) ? "checked" : ""; ?> class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                        <span>
                            <span class="block font-medium text-slate-100">Overspending Risk</span>
                            <span class="block text-slate-400 mt-1">Warnings when your recent spending pattern starts putting the month at risk.</span>
                        </span>
                    </label>

                    <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300">
                        <input type="checkbox" name="forecast" <?php echo !empty($preferences["forecast"]) ? "checked" : ""; ?> class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                        <span>
                            <span class="block font-medium text-slate-100">Forecast Alerts</span>
                            <span class="block text-slate-400 mt-1">Alerts when your projected month-end balance turns negative or trends sharply worse.</span>
                        </span>
                    </label>

                    <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300">
                        <input type="checkbox" name="budget_threshold" <?php echo !empty($preferences["budget_threshold"]) ? "checked" : ""; ?> class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                        <span>
                            <span class="block font-medium text-slate-100">Budget Thresholds</span>
                            <span class="block text-slate-400 mt-1">Reminders when a category budget crosses warning or over-budget levels.</span>
                        </span>
                    </label>

                    <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300">
                        <input type="checkbox" name="subscription_review" <?php echo !empty($preferences["subscription_review"]) ? "checked" : ""; ?> class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                        <span>
                            <span class="block font-medium text-slate-100">Subscription Review</span>
                            <span class="block text-slate-400 mt-1">Prompts to revisit recurring costs that are standing out this month.</span>
                        </span>
                    </label>

                    <label class="flex items-center gap-3 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300">
                        <input type="checkbox" name="coach_recommendation" <?php echo !empty($preferences["coach_recommendation"]) ? "checked" : ""; ?> class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-emerald-500 focus:ring-emerald-500">
                        <span>
                            <span class="block font-medium text-slate-100">Coach Recommendation</span>
                            <span class="block text-slate-400 mt-1">Surface the top action from Budget Coach as part of your dashboard alerts.</span>
                        </span>
                    </label>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-medium px-5 py-2.5 text-sm transition">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
                <div class="flex flex-col gap-2 mb-6">
                    <h3 class="text-xl font-semibold">Dismissed Alerts</h3>
                    <p class="text-slate-400 text-sm">You currently have <?php echo count($dismissedAlerts); ?> dismissed alert<?php echo count($dismissedAlerts) === 1 ? "" : "s"; ?> saved on your account.</p>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                    <p class="text-sm text-slate-300">
                        Restoring dismissed alerts will make hidden alerts visible again the next time they match your current preferences.
                    </p>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="action" value="restore_dismissed">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium px-5 py-2.5 text-sm transition">
                            Restore Dismissed Alerts
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
