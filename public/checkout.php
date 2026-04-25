<?php
session_start();

require_once __DIR__ . "/../config/payments.php";

$plan = strtolower(trim($_GET["plan"] ?? ($_SESSION["pending_plan"] ?? "")));
$planLabels = [
    "starter" => "Starter",
    "growth" => "Growth",
];
$planLabel = $planLabels[$plan] ?? null;
$paymentLink = $paymentLinks[$plan] ?? "";

if ($planLabel === null) {
    header("Location: landing.php#pricing");
    exit;
}

$_SESSION["pending_plan"] = $plan;

if ($paymentLink !== "") {
    header("Location: " . $paymentLink);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Setup - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-2xl rounded-2xl border border-slate-800 bg-slate-900 p-8 shadow-2xl">
        <p class="text-xs uppercase tracking-[0.2em] text-amber-300">Stripe Setup Needed</p>
        <h1 class="mt-3 text-3xl font-semibold">Connect the <?php echo htmlspecialchars($planLabel); ?> checkout link</h1>
        <p class="mt-4 text-slate-300">This flow is ready for Stripe Payment Links, but the link for the <?php echo htmlspecialchars($planLabel); ?> plan has not been configured yet.</p>

        <div class="mt-6 rounded-xl border border-slate-800 bg-slate-950/60 p-5">
            <p class="text-sm text-slate-300">Next step:</p>
            <p class="mt-2 text-sm text-slate-400">Copy <code>config/payments.local.php.example</code> to <code>config/payments.local.php</code>, then paste your Stripe Payment Link for this plan. After payment, send buyers to <code>purchase_success.php?plan=<?php echo urlencode($plan); ?></code> so they can create or connect an account.</p>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <a href="landing.php#pricing" class="inline-flex items-center justify-center rounded-lg bg-slate-800 px-4 py-3 text-sm text-slate-200 transition hover:bg-slate-700">Back to Pricing</a>
            <a href="login.php?plan=<?php echo urlencode($plan); ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-700 px-4 py-3 text-sm text-slate-200 transition hover:bg-slate-800">Already have an account?</a>
        </div>
    </div>
</body>
</html>
