<?php
session_start();
require_once __DIR__ . "/../src/auth.php";
require_once __DIR__ . "/../config/stripe.php";
require_once __DIR__ . "/../src/purchases.php";

$plan = normalizePlan($_GET["plan"] ?? ($_SESSION["pending_plan"] ?? ""), "");
$purchaseToken = trim((string) ($_GET["purchase_token"] ?? ($_SESSION["pending_purchase_token"] ?? "")));
$stripeSessionId = trim((string) ($_GET["session_id"] ?? ""));
$planLabels = [
    "starter" => "Starter",
    "growth" => "Growth",
    "scale" => "Scale",
];
$planLabel = $planLabels[$plan] ?? null;
$purchaseEmail = "";

if ($planLabel === null) {
    header("Location: landing.php#pricing");
    exit;
}

if ($purchaseToken !== "") {
    $_SESSION["pending_purchase_token"] = $purchaseToken;
}

if ($purchaseToken === "" && $stripeSessionId !== "") {
    $claimBySession = btGetPurchaseClaimByStripeSessionId($stripeSessionId);
    if ($claimBySession && !empty($claimBySession["claim_token"])) {
        $purchaseToken = (string) $claimBySession["claim_token"];
        $_SESSION["pending_purchase_token"] = $purchaseToken;
    }
}

if ($purchaseToken !== "") {
    $purchaseClaim = btGetPurchaseClaimByToken($purchaseToken);
    if ($purchaseClaim && ($purchaseClaim["plan"] ?? "") !== "") {
        $plan = normalizePlan($purchaseClaim["plan"], $plan);
        $planLabel = $planLabels[$plan] ?? $planLabel;
    }
}

if ($stripeSessionId !== "" && btStripeCheckoutReadyForPlan($plan)) {
    require_once __DIR__ . "/../vendor/autoload.php";

    try {
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        $checkoutSession = \Stripe\Checkout\Session::retrieve($stripeSessionId);
        $sessionPlan = normalizePlan((string) ($checkoutSession->metadata->plan ?? ""), $plan);
        $purchaseEmail = trim((string) ($checkoutSession->customer_details->email ?? $checkoutSession->customer_email ?? ""));
        $stripePaymentStatus = (string) ($checkoutSession->payment_status ?? "");
        $stripeStatus = (string) ($checkoutSession->status ?? "");
        if ($stripeStatus === "complete" && $stripePaymentStatus !== "unpaid") {
            btMarkPurchaseClaimPaid(
                $purchaseToken,
                $sessionPlan,
                $stripeSessionId,
                $purchaseEmail,
                [
                    "stripe_session_id" => $stripeSessionId,
                    "stripe_payment_status" => $stripePaymentStatus,
                    "stripe_status" => $stripeStatus,
                ]
            );
            $plan = $sessionPlan;
            $planLabel = $planLabels[$plan] ?? $planLabel;
        }
    } catch (\Throwable $e) {
        error_log("Budget Tracker purchase success retrieval failed: " . $e->getMessage());
    }
}

$purchaseClaim = $purchaseToken !== "" ? btGetPurchaseClaimByToken($purchaseToken) : null;
if ($purchaseEmail === "" && $purchaseClaim) {
    $purchaseEmail = trim((string) ($purchaseClaim["stripe_customer_email"] ?? ""));
}

if (isset($_SESSION["user_id"])) {
    $claimed = btClaimPurchaseForUser((int) $_SESSION["user_id"], (string) ($_SESSION["user_email"] ?? ""), $purchaseToken, $plan);
    if (!empty($claimed["ok"]) && !empty($claimed["plan"])) {
        $plan = normalizePlan($claimed["plan"], $plan);
        $planLabel = $planLabels[$plan] ?? $planLabel;
    }
    updateUserPlan((int) $_SESSION["user_id"], $plan);
    unset($_SESSION["pending_plan"], $_SESSION["completed_purchase_plan"], $_SESSION["pending_purchase_token"], $_SESSION["completed_purchase_token"], $_SESSION["completed_purchase_email"]);
    $_SESSION["purchase_flash"] = $planLabel . " access is now active on your account.";
    header("Location: dashboard.php");
    exit;
}

$_SESSION["completed_purchase_plan"] = $plan;
$_SESSION["completed_purchase_token"] = $purchaseToken;
$_SESSION["completed_purchase_email"] = $purchaseEmail;
unset($_SESSION["pending_plan"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Complete - Budget Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
    <style>
        :root {
            --bg: #f9f8f6;
            --line: rgba(10, 10, 11, 0.08);
            --text: #0a0a0b;
            --muted: #5b5b61;
            --accent: #0052ff;
            --accent-strong: #0040c5;
            --shadow: 0 24px 70px rgba(17, 24, 39, 0.08);
        }
        body { font-family: "Manrope", sans-serif; background: var(--bg); color: var(--text); }
        h1 { font-family: "Playfair Display", serif; letter-spacing: -0.035em; line-height: 0.98; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-2xl rounded-3xl border p-8 shadow-2xl" style="border-color: var(--line); background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.88)); box-shadow: var(--shadow);">
        <p class="text-xs uppercase tracking-[0.2em]" style="color: var(--accent);">Payment Complete</p>
        <h1 class="mt-3 text-3xl font-semibold"><?php echo htmlspecialchars($planLabel); ?> is ready</h1>
        <p class="mt-4" style="color: var(--muted);">Your payment went through. Create an account now to activate your plan, or sign in if you already have one.</p>

        <div class="mt-6 rounded-2xl border p-5" style="border-color: rgba(10, 10, 11, 0.08); background: rgba(255,255,255,0.68);">
            <p class="text-sm" style="color: var(--text);">Next step:</p>
            <p class="mt-2 text-sm" style="color: var(--muted);">Use the same email you want associated with your Budget Tracker access. We will attach your <strong><?php echo htmlspecialchars($planLabel); ?></strong> plan when you register or sign in from this page.</p>
            <?php if ($purchaseEmail !== ""): ?>
                <p class="mt-3 text-sm" style="color: var(--muted);">Payment email on file: <strong style="color: var(--text);"><?php echo htmlspecialchars($purchaseEmail); ?></strong></p>
            <?php endif; ?>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <a href="register.php?plan=<?php echo urlencode($plan); ?><?php echo $purchaseToken !== "" ? "&purchase_token=" . urlencode($purchaseToken) : ""; ?>" class="inline-flex items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition" style="background: var(--accent);" onmouseover="this.style.background='var(--accent-strong)'" onmouseout="this.style.background='var(--accent)'">Create Account</a>
            <a href="login.php?plan=<?php echo urlencode($plan); ?><?php echo $purchaseToken !== "" ? "&purchase_token=" . urlencode($purchaseToken) : ""; ?>" class="inline-flex items-center justify-center rounded-lg border px-4 py-3 text-sm transition" style="border-color: rgba(10, 10, 11, 0.16); color: var(--text); background: rgba(255,255,255,0.55);">I Already Have An Account</a>
        </div>
    </div>
</body>
</html>
