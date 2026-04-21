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

    if (($_POST["action"] ?? "") === "reset_defaults") {
        resetUserAlertPreferencesToDefaults($userId);
        resetUserAiSettingsToDefaults($userId);
        restoreUserDismissedAlerts($userId);
        $successMessage = "All settings were reset to default.";
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
    <title>Settings - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .setting-toggle {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .setting-toggle-ui {
            position: relative;
            display: inline-flex;
            align-items: center;
            width: 3.25rem;
            height: 1.8rem;
            border-radius: 9999px;
            background: rgb(51 65 85);
            transition: background-color 150ms ease;
            flex-shrink: 0;
        }

        .setting-toggle-ui::after {
            content: "";
            position: absolute;
            left: 0.25rem;
            width: 1.3rem;
            height: 1.3rem;
            border-radius: 9999px;
            background: rgb(226 232 240);
            transition: transform 150ms ease;
        }

        .setting-toggle:checked + .setting-toggle-ui {
            background: rgb(16 185 129);
        }

        .setting-toggle:checked + .setting-toggle-ui::after {
            transform: translateX(1.45rem);
        }

        .setting-toggle:focus-visible + .setting-toggle-ui {
            outline: 2px solid rgb(52 211 153);
            outline-offset: 2px;
        }

        .save-toast {
            animation: toast-in 180ms ease-out;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex">
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col p-6">
        <h1 class="text-xl font-semibold mb-8">Budget Tracker App</h1>
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
        <div class="app-status-strip">
            <span class="app-status-pill">preferences: account level</span>
            <span class="app-status-pill">alerts: customizable</span>
            <span class="app-status-pill">ai profile: active</span>
        </div>
        <div class="max-w-4xl">
            <div class="mb-8">
                <p class="app-kicker">Operations / Settings</p>
                <h2 class="text-3xl font-semibold">Settings</h2>
                <p class="text-slate-400 text-sm mt-2">Control which dashboard alerts you see across your account and restore alerts you previously dismissed.</p>
            </div>

            <?php if ($successMessage !== ""): ?>
                <div class="save-toast mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-200 shadow-lg shadow-emerald-950/30">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mb-8">
                <div class="flex flex-col gap-2 mb-6">
                    <h3 class="text-xl font-semibold">AI & Alerts</h3>
                    <p class="text-slate-400 text-sm">Choose how visible AI coaching should be by default and how often the app should prepare proactive summaries for you.</p>
                </div>

                <form method="POST" class="space-y-6 mb-8">
                    <input type="hidden" name="action" value="save_ai_settings">

                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-slate-200">AI Defaults</h4>
                            <p class="text-sm text-slate-400 mt-1">Control how visible AI guidance should be when you open the app.</p>
                        </div>

                        <div class="space-y-4">
                            <label class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-900/80 px-4 py-4 text-sm text-slate-300 transition hover:border-slate-700 hover:bg-slate-900">
                                <span class="flex items-start gap-3">
                                    <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/10 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">AI</span>
                                    <span>
                                        <span class="block font-medium text-slate-100">Show Coach Score by Default</span>
                                        <span class="block text-slate-400 mt-1">Show your AI financial control score automatically when you open the dashboard.</span>
                                    </span>
                                </span>
                                <span class="inline-flex items-center">
                                    <input type="checkbox" name="coach_score_default_visible" <?php echo !empty($aiSettings["coach_score_default_visible"]) ? "checked" : ""; ?> class="setting-toggle">
                                    <span class="setting-toggle-ui" aria-hidden="true"></span>
                                </span>
                            </label>

                            <label class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-900/80 px-4 py-4 text-sm text-slate-300 transition hover:border-slate-700 hover:bg-slate-900">
                                <span class="flex items-start gap-3">
                                    <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-500/10 text-xs font-semibold uppercase tracking-[0.2em] text-sky-300">DG</span>
                                    <span>
                                        <span class="block font-medium text-slate-100">Enable Weekly Digest</span>
                                        <span class="block text-slate-400 mt-1">Receive a weekly AI-generated summary of your spending, trends, and recommendations.</span>
                                    </span>
                                </span>
                                <span class="inline-flex items-center">
                                    <input type="checkbox" name="weekly_digest_enabled" <?php echo !empty($aiSettings["weekly_digest_enabled"]) ? "checked" : ""; ?> class="setting-toggle">
                                    <span class="setting-toggle-ui" aria-hidden="true"></span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-slate-200">Digest Timing</h4>
                            <p class="text-sm text-slate-400 mt-1">Choose how often future AI digests and proactive reminders should appear in your account workflow.</p>
                        </div>

                        <div class="rounded-lg border border-slate-800 bg-slate-900/80 px-4 py-4 text-sm text-slate-300">
                            <label for="notification_cadence" class="block font-medium text-slate-100">Notification Cadence</label>
                            <p class="text-slate-400 mt-1">Choose how often AI insights and smart reminders should appear in your experience.</p>
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
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="settings-submit inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 disabled:bg-emerald-700 disabled:text-slate-300 disabled:cursor-not-allowed text-slate-950 font-medium px-5 py-2.5 text-sm transition">
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

                    <label class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300 transition hover:border-slate-700 hover:bg-slate-900/80">
                        <span class="flex items-start gap-3">
                            <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-rose-500/10 text-xs font-semibold uppercase tracking-[0.2em] text-rose-300">RS</span>
                            <span>
                                <span class="block font-medium text-slate-100">Overspending Risk</span>
                                <span class="block text-slate-400 mt-1">Warn me when my spending trend suggests I may go off track this month.</span>
                            </span>
                        </span>
                        <span class="inline-flex items-center">
                            <input type="checkbox" name="overspending_risk" <?php echo !empty($preferences["overspending_risk"]) ? "checked" : ""; ?> class="setting-toggle">
                            <span class="setting-toggle-ui" aria-hidden="true"></span>
                        </span>
                    </label>

                    <label class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300 transition hover:border-slate-700 hover:bg-slate-900/80">
                        <span class="flex items-start gap-3">
                            <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/10 text-xs font-semibold uppercase tracking-[0.2em] text-amber-300">FC</span>
                            <span>
                                <span class="block font-medium text-slate-100">Forecast Alerts</span>
                                <span class="block text-slate-400 mt-1">Alert me when my projected month-end balance turns negative or worsens noticeably.</span>
                            </span>
                        </span>
                        <span class="inline-flex items-center">
                            <input type="checkbox" name="forecast" <?php echo !empty($preferences["forecast"]) ? "checked" : ""; ?> class="setting-toggle">
                            <span class="setting-toggle-ui" aria-hidden="true"></span>
                        </span>
                    </label>

                    <label class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300 transition hover:border-slate-700 hover:bg-slate-900/80">
                        <span class="flex items-start gap-3">
                            <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-500/10 text-xs font-semibold uppercase tracking-[0.2em] text-sky-300">BD</span>
                            <span>
                                <span class="block font-medium text-slate-100">Budget Thresholds</span>
                                <span class="block text-slate-400 mt-1">Remind me when a category budget reaches warning or over-budget levels.</span>
                            </span>
                        </span>
                        <span class="inline-flex items-center">
                            <input type="checkbox" name="budget_threshold" <?php echo !empty($preferences["budget_threshold"]) ? "checked" : ""; ?> class="setting-toggle">
                            <span class="setting-toggle-ui" aria-hidden="true"></span>
                        </span>
                    </label>

                    <label class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300 transition hover:border-slate-700 hover:bg-slate-900/80">
                        <span class="flex items-start gap-3">
                            <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-fuchsia-500/10 text-xs font-semibold uppercase tracking-[0.2em] text-fuchsia-300">SB</span>
                            <span>
                                <span class="block font-medium text-slate-100">Subscription Review</span>
                                <span class="block text-slate-400 mt-1">Prompt me to review recurring charges that are costing more than expected.</span>
                            </span>
                        </span>
                        <span class="inline-flex items-center">
                            <input type="checkbox" name="subscription_review" <?php echo !empty($preferences["subscription_review"]) ? "checked" : ""; ?> class="setting-toggle">
                            <span class="setting-toggle-ui" aria-hidden="true"></span>
                        </span>
                    </label>

                    <label class="flex items-center justify-between gap-4 rounded-lg border border-slate-800 bg-slate-950/60 px-4 py-4 text-sm text-slate-300 transition hover:border-slate-700 hover:bg-slate-900/80">
                        <span class="flex items-start gap-3">
                            <span class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/10 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">AI</span>
                            <span>
                                <span class="block font-medium text-slate-100">Coach Recommendation</span>
                                <span class="block text-slate-400 mt-1">Show the most useful next step from Budget Coach directly in your dashboard alerts.</span>
                            </span>
                        </span>
                        <span class="inline-flex items-center">
                            <input type="checkbox" name="coach_recommendation" <?php echo !empty($preferences["coach_recommendation"]) ? "checked" : ""; ?> class="setting-toggle">
                            <span class="setting-toggle-ui" aria-hidden="true"></span>
                        </span>
                    </label>

                    <div class="pt-2">
                        <button type="submit" class="settings-submit inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 disabled:bg-emerald-700 disabled:text-slate-300 disabled:cursor-not-allowed text-slate-950 font-medium px-5 py-2.5 text-sm transition">
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
                        <button type="submit" class="settings-submit inline-flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 disabled:bg-slate-700 disabled:text-slate-400 disabled:cursor-not-allowed text-slate-200 font-medium px-5 py-2.5 text-sm transition">
                            Restore Dismissed Alerts
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mt-8">
                <div class="flex flex-col gap-2 mb-6">
                    <h3 class="text-xl font-semibold">Reset to Defaults</h3>
                    <p class="text-slate-400 text-sm">Restore AI settings, alert preferences, and dismissed alerts back to the default experience.</p>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                    <p class="text-sm text-slate-300">
                        This will turn settings back to their original defaults and make dismissed alerts visible again.
                    </p>
                    <form method="POST" class="mt-4" onsubmit="return confirm('Reset all AI and alert settings back to defaults?');">
                        <input type="hidden" name="action" value="reset_defaults">
                        <button type="submit" class="settings-submit inline-flex items-center justify-center rounded-lg bg-rose-500 hover:bg-rose-400 disabled:bg-rose-800 disabled:text-slate-300 disabled:cursor-not-allowed text-slate-950 font-medium px-5 py-2.5 text-sm transition">
                            Reset All Settings to Default
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.querySelectorAll('form').forEach((form) => {
            form.addEventListener('submit', () => {
                const submitButton = form.querySelector('.settings-submit');
                if (!submitButton) {
                    return;
                }

                submitButton.disabled = true;
                const originalLabel = submitButton.textContent.trim();
                submitButton.dataset.originalLabel = originalLabel;
                submitButton.textContent = originalLabel.startsWith('Restore') ? 'Restoring...' : 'Saving...';
            });
        });
    </script>
</body>
</html>
