<?php
session_start();
require_once __DIR__ . "/../src/auth.php";

$plan = normalizePlan($_GET["plan"] ?? ($_SESSION["pending_plan"] ?? ""), "");
$planLabels = [
    "starter" => "Starter",
    "growth" => "Growth",
];
$planLabel = $planLabels[$plan] ?? null;

if ($planLabel === null) {
    header("Location: landing.php#pricing");
    exit;
}

if (isset($_SESSION["user_id"])) {
    updateUserPlan((int) $_SESSION["user_id"], $plan);
    unset($_SESSION["pending_plan"], $_SESSION["completed_purchase_plan"]);
    $_SESSION["purchase_flash"] = $planLabel . " access is now active on your account.";
    header("Location: dashboard.php");
    exit;
}

$_SESSION["completed_purchase_plan"] = $plan;
unset($_SESSION["pending_plan"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Complete - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-2xl rounded-2xl border border-slate-800 bg-slate-900 p-8 shadow-2xl">
        <p class="text-xs uppercase tracking-[0.2em] text-emerald-300">Payment Complete</p>
        <h1 class="mt-3 text-3xl font-semibold"><?php echo htmlspecialchars($planLabel); ?> is ready</h1>
        <p class="mt-4 text-slate-300">Your payment went through. Create an account now to activate your plan, or sign in if you already have one.</p>

        <div class="mt-6 rounded-xl border border-slate-800 bg-slate-950/60 p-5">
            <p class="text-sm text-slate-300">Next step:</p>
            <p class="mt-2 text-sm text-slate-400">Use the same email you want associated with your Budget Tracker App access. We will attach your <strong><?php echo htmlspecialchars($planLabel); ?></strong> plan when you register or sign in from this page.</p>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <a href="register.php?plan=<?php echo urlencode($plan); ?>" class="inline-flex items-center justify-center rounded-lg bg-emerald-500 px-4 py-3 text-sm font-medium text-slate-950 transition hover:bg-emerald-400">Create Account</a>
            <a href="login.php?plan=<?php echo urlencode($plan); ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-700 px-4 py-3 text-sm text-slate-200 transition hover:bg-slate-800">I Already Have An Account</a>
        </div>
    </div>
</body>
</html>
