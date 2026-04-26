<?php
session_start();

require_once __DIR__ . "/../config/payments.php";
require_once __DIR__ . "/../config/stripe.php";

$plan = strtolower(trim($_GET["plan"] ?? ($_SESSION["pending_plan"] ?? "")));
$planLabels = [
    "starter" => "Starter",
    "growth" => "Growth",
    "scale" => "Scale",
];
$planLabel = $planLabels[$plan] ?? null;
$paymentLink = $paymentLinks[$plan] ?? "";
$planPriceConfigLabels = [
    "starter" => '$starterStripePriceId',
    "growth" => '$growthStripePriceId',
    "scale" => '$scaleStripePriceId',
];
$planPriceConfigLabel = $planPriceConfigLabels[$plan] ?? '$stripePriceId';

if ($planLabel === null) {
    header("Location: landing.php#pricing");
    exit;
}

$_SESSION["pending_plan"] = $plan;

if (btStripeCheckoutReadyForPlan($plan)) {
    require_once __DIR__ . "/../vendor/autoload.php";

    \Stripe\Stripe::setApiKey($stripeSecretKey);

    $baseUrl = btResolveAppBaseUrl();
    $successUrl = $baseUrl . "/purchase_success.php?plan=" . rawurlencode($plan) . "&session_id={CHECKOUT_SESSION_ID}";
    $cancelUrl = $baseUrl . "/checkout.php?plan=" . rawurlencode($plan) . "&canceled=1";

    try {
        $session = \Stripe\Checkout\Session::create([
            "mode" => "subscription",
            "line_items" => [[
                "price" => $stripePriceIds[$plan],
                "quantity" => 1,
            ]],
            "success_url" => $successUrl,
            "cancel_url" => $cancelUrl,
            "metadata" => [
                "plan" => $plan,
            ],
        ]);

        header("Location: " . $session->url);
        exit;
    } catch (\Throwable $e) {
        $checkoutError = $e->getMessage();
    }
}

if ($paymentLink !== "") {
    header("Location: " . $paymentLink);
    exit;
}

$checkoutError = $checkoutError ?? "";
$canceled = isset($_GET["canceled"]);
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
        <h1 class="mt-3 text-3xl font-semibold">Finish the <?php echo htmlspecialchars($planLabel); ?> checkout setup</h1>
        <p class="mt-4 text-slate-300">This flow can use either Stripe Checkout Sessions or Stripe Payment Links. Right now, this plan does not have a complete Stripe checkout configuration.</p>

        <?php if ($canceled): ?>
            <div class="mt-6 rounded-xl border border-amber-500/30 bg-amber-500/10 p-5 text-sm text-amber-100">
                Checkout was canceled before payment completed.
            </div>
        <?php endif; ?>

        <?php if ($checkoutError !== ""): ?>
            <div class="mt-6 rounded-xl border border-rose-500/30 bg-rose-500/10 p-5 text-sm text-rose-100">
                Stripe Checkout could not start: <?php echo htmlspecialchars($checkoutError); ?>
            </div>
        <?php endif; ?>

        <div class="mt-6 rounded-xl border border-slate-800 bg-slate-950/60 p-5">
            <p class="text-sm text-slate-300">Stripe Checkout Sessions option:</p>
            <p class="mt-2 text-sm text-slate-400">Install Composer dependencies, set <code>BT_STRIPE_SECRET_KEY</code>, and add the <code><?php echo htmlspecialchars($planPriceConfigLabel); ?></code> value in <code>config/stripe.local.php</code>. Successful payments redirect to <code>purchase_success.php?plan=<?php echo urlencode($plan); ?></code>.</p>
        </div>

        <div class="mt-4 rounded-xl border border-slate-800 bg-slate-950/60 p-5">
            <p class="text-sm text-slate-300">Stripe Payment Link fallback:</p>
            <p class="mt-2 text-sm text-slate-400">Paste the hosted payment link into <code>config/payments.local.php</code> using the <code>$<?php echo htmlspecialchars($plan); ?>PaymentLink</code> variable for this plan.</p>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <a href="landing.php#pricing" class="inline-flex items-center justify-center rounded-lg bg-slate-800 px-4 py-3 text-sm text-slate-200 transition hover:bg-slate-700">Back to Pricing</a>
            <a href="login.php?plan=<?php echo urlencode($plan); ?>" class="inline-flex items-center justify-center rounded-lg border border-slate-700 px-4 py-3 text-sm text-slate-200 transition hover:bg-slate-800">Already have an account?</a>
        </div>
    </div>
</body>
</html>
