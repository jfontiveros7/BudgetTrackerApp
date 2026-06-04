<?php
session_start();

require_once __DIR__ . "/../config/payments.php";
require_once __DIR__ . "/../config/stripe.php";
require_once __DIR__ . "/../src/purchases.php";

$plan = strtolower(trim($_GET["plan"] ?? ($_SESSION["pending_plan"] ?? "")));
$planLabels = [
    "starter" => "Monitor",
    "growth" => "Control",
    "scale" => "Command",
];
$planDisplayNames = [
    "starter" => "Starter",
    "growth" => "Growth",
    "scale" => "Scale",
];
$planPrices = [
    "starter" => "$5/mo",
    "growth" => "$10/mo",
    "scale" => "$20/mo",
];
$planLabel = $planLabels[$plan] ?? null;
$planDisplayName = $planDisplayNames[$plan] ?? "Plan";
$planPrice = $planPrices[$plan] ?? "";
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
$purchaseToken = $_SESSION["pending_purchase_token"] ?? "";
if (!is_string($purchaseToken) || $purchaseToken === "") {
    $purchaseToken = btCreatePurchaseClaim($plan) ?? "";
    if ($purchaseToken !== "") {
        $_SESSION["pending_purchase_token"] = $purchaseToken;
    }
}
$checkoutDestination = "";
$checkoutModeLabel = "";
$checkoutError = "";
$canceled = isset($_GET["canceled"]);

if (btStripeCheckoutReadyForPlan($plan)) {
    require_once __DIR__ . "/../vendor/autoload.php";

    \Stripe\Stripe::setApiKey($stripeSecretKey);

    $baseUrl = btResolveAppBaseUrl();
    $successUrl = $baseUrl . "/purchase_success.php?plan=" . rawurlencode($plan) . "&session_id={CHECKOUT_SESSION_ID}";
    $cancelUrl = $baseUrl . "/checkout.php?plan=" . rawurlencode($plan) . "&canceled=1";
    if ($purchaseToken !== "") {
        $successUrl .= "&purchase_token=" . rawurlencode($purchaseToken);
        $cancelUrl .= "&purchase_token=" . rawurlencode($purchaseToken);
    }

    try {
        $checkoutParams = [
            "mode" => "subscription",
            "line_items" => [[
                "price" => $stripePriceIds[$plan],
                "quantity" => 1,
            ]],
            "success_url" => $successUrl,
            "cancel_url" => $cancelUrl,
            "metadata" => [
                "plan" => $plan,
                "purchase_token" => $purchaseToken,
            ],
        ];
        if ($purchaseToken !== "") {
            $checkoutParams["client_reference_id"] = $purchaseToken;
        }

        $session = \Stripe\Checkout\Session::create($checkoutParams);

        $checkoutDestination = $session->url;
        $checkoutModeLabel = "Stripe Checkout";
        if ($purchaseToken !== "") {
            btStorePurchaseClaimStripeSession($purchaseToken, (string) $session->id);
        }
    } catch (\Throwable $e) {
        $checkoutError = $e->getMessage();
    }
}

if ($checkoutDestination === "" && $paymentLink !== "") {
    $checkoutDestination = $paymentLink;
    $checkoutModeLabel = "Stripe secure payment";
}

$retryUrl = "checkout.php?plan=" . urlencode($plan);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($planDisplayName); ?> Checkout - Budget Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f9f8f6;
            --panel: #ffffff;
            --line: rgba(10, 10, 11, 0.08);
            --text: #0a0a0b;
            --muted: #5b5b61;
            --accent: #0052ff;
            --accent-strong: #0040c5;
            --shadow: 0 24px 70px rgba(17, 24, 39, 0.08);
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Manrope", sans-serif; background: #f9f8f6; color: var(--text); }
        h1, h2, h3 { font-family: "Playfair Display", serif; letter-spacing: -0.035em; line-height: 0.98; }
        .mono { font-family: "JetBrains Mono", monospace; }
        .glass { background: rgba(249, 248, 246, 0.78); backdrop-filter: blur(14px); }
        .panel { background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.88)); border: 1px solid var(--line); box-shadow: var(--shadow); }
        .cta-primary { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 999px; background: var(--accent); color: #fff; font-weight: 500; transition: all 180ms ease; }
        .cta-primary:hover { background: var(--accent-strong); transform: translateY(-2px); }
        .cta-secondary { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 999px; border: 1px solid rgba(10, 10, 11, 0.15); background: transparent; color: var(--text); font-weight: 500; transition: all 180ms ease; }
        .cta-secondary:hover { transform: translateY(-2px); background: rgba(10, 10, 11, 0.03); }
        .hero-mesh { position: absolute; inset: 0; opacity: 0.04; pointer-events: none; background-image: linear-gradient(#0A0A0B 1px, transparent 1px), linear-gradient(90deg, #0A0A0B 1px, transparent 1px); background-size: 64px 64px; }
        .spinner { width: 2.5rem; height: 2.5rem; border-radius: 999px; border: 3px solid rgba(0, 82, 255, 0.16); border-top-color: var(--accent); animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <header class="sticky top-0 z-30 border-b border-black/5 glass">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-4 md:px-10">
            <a href="landing.php#top" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#0A0A0B]">
                    <span class="block h-3 w-3 rotate-12 rounded-sm bg-[#0052FF]"></span>
                </span>
                <span class="text-xl tracking-tight" style="font-family: 'Playfair Display', serif;">Budget Tracker</span>
            </a>
            <div class="hidden md:flex items-center gap-3">
                <a href="landing.php#pricing" class="cta-secondary px-4 py-2.5 text-sm">Pricing</a>
                <a href="login.php?plan=<?php echo urlencode($plan); ?>" class="cta-secondary px-4 py-2.5 text-sm">Client Login</a>
            </div>
        </div>
    </header>

    <main class="relative min-h-screen overflow-hidden py-10 md:py-16">
        <div class="hero-mesh"></div>
        <div class="relative mx-auto max-w-7xl px-6 md:px-10">
            <div class="grid items-start gap-8 lg:grid-cols-12 lg:gap-10">
                <section class="lg:col-span-6">
                    <div class="mb-7 flex items-center gap-3">
                        <span class="mono text-[11px] font-semibold uppercase tracking-[0.22em] text-[#0052FF]">Secure Checkout · <?php echo htmlspecialchars($planDisplayName); ?></span>
                        <span class="h-px max-w-[140px] flex-1 bg-black/10"></span>
                    </div>
                    <h1 class="max-w-4xl text-5xl md:text-7xl lg:text-[72px]">
                        Complete <?php echo htmlspecialchars($planDisplayName); ?> with a <span class="italic text-black/55">clean handoff</span>.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-black/68 md:text-xl">
                        We keep the context here, pass the payment to Stripe securely, and bring you right back to activate your access after purchase.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl border border-black/6 bg-white/70 p-5">
                            <p class="mono text-[11px] font-semibold uppercase tracking-[0.2em] text-[#0052FF]">Plan</p>
                            <h2 class="mt-3 text-2xl"><?php echo htmlspecialchars($planDisplayName); ?></h2>
                            <p class="mt-3 text-sm leading-6 text-black/62"><?php echo htmlspecialchars($planPrice); ?> with a secure Stripe checkout and instant return to your account flow.</p>
                        </div>
                        <div class="rounded-3xl border border-black/6 bg-white/70 p-5">
                            <p class="mono text-[11px] font-semibold uppercase tracking-[0.2em] text-[#0052FF]">Why Stripe</p>
                            <h2 class="mt-3 text-2xl">Protected</h2>
                            <p class="mt-3 text-sm leading-6 text-black/62">Card entry, receipts, and subscription handling stay inside Stripe's hosted checkout.</p>
                        </div>
                        <div class="rounded-3xl border border-black/6 bg-white/70 p-5">
                            <p class="mono text-[11px] font-semibold uppercase tracking-[0.2em] text-[#0052FF]">After payment</p>
                            <h2 class="mt-3 text-2xl">Activate fast</h2>
                            <p class="mt-3 text-sm leading-6 text-black/62">We return you to the app to sign in or create the account that should own this plan.</p>
                        </div>
                    </div>
                </section>

                <section class="lg:col-span-6">
                    <div class="panel rounded-[28px] p-6 md:p-8">
                        <?php if ($checkoutDestination !== "" && !$canceled): ?>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="spinner" aria-hidden="true"></div>
                                <div>
                                    <p class="mono text-[11px] font-semibold uppercase tracking-[0.22em] text-[#0052FF]">Preparing checkout</p>
                                    <p class="mt-1 text-sm text-black/60"><?php echo htmlspecialchars($checkoutModeLabel); ?> is ready. You will be redirected securely in a moment.</p>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-black/6 bg-[#F9F8F6] p-5">
                                <p class="text-sm text-black/62">Selected plan</p>
                                <div class="mt-2 flex items-end justify-between gap-4">
                                    <h2 class="text-3xl"><?php echo htmlspecialchars($planDisplayName); ?></h2>
                                    <span class="mono text-sm text-black/55"><?php echo htmlspecialchars($planPrice); ?></span>
                                </div>
                                <p class="mt-4 text-sm leading-6 text-black/62">If the redirect does not happen automatically, use the button below to continue securely to Stripe.</p>
                            </div>

                            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                                <a href="<?php echo htmlspecialchars($checkoutDestination); ?>" class="cta-primary px-5 py-3 text-sm">Continue to Stripe</a>
                                <a href="landing.php#pricing" class="cta-secondary px-5 py-3 text-sm">Back to Pricing</a>
                            </div>

                            <p class="mt-4 text-xs text-black/45">You are leaving Budget Tracker briefly to complete payment on Stripe and will return here when finished.</p>
                        <?php else: ?>
                            <div class="flex items-center gap-3 mb-6">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#0A0A0B]">
                                    <span class="block h-3 w-3 rotate-12 rounded-sm bg-[#0052FF]"></span>
                                </span>
                                <div>
                                    <p class="mono text-[11px] font-semibold uppercase tracking-[0.22em] text-[#0052FF]"><?php echo $canceled ? "Checkout paused" : "Checkout setup"; ?></p>
                                    <p class="mt-1 text-sm text-black/60"><?php echo $canceled ? "Your payment was canceled before completion." : "This plan still needs checkout configuration."; ?></p>
                                </div>
                            </div>

                            <?php if ($canceled): ?>
                                <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                                    Checkout was canceled before payment completed. You can retry whenever you're ready.
                                </div>
                            <?php endif; ?>

                            <?php if ($checkoutError !== ""): ?>
                                <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                                    Stripe Checkout could not start: <?php echo htmlspecialchars($checkoutError); ?>
                                </div>
                            <?php endif; ?>

                            <div class="rounded-2xl border border-black/6 bg-[#F9F8F6] p-5">
                                <p class="text-sm text-black/72">Stripe Checkout Sessions option</p>
                                <p class="mt-2 text-sm leading-6 text-black/62">Set <code>BT_STRIPE_SECRET_KEY</code> and add the <code><?php echo htmlspecialchars($planPriceConfigLabel); ?></code> value in <code>config/stripe.local.php</code>. Successful payments redirect to <code>purchase_success.php?plan=<?php echo urlencode($plan); ?></code>.</p>
                            </div>

                            <div class="mt-4 rounded-2xl border border-black/6 bg-[#F9F8F6] p-5">
                                <p class="text-sm text-black/72">Stripe Payment Link fallback</p>
                                <p class="mt-2 text-sm leading-6 text-black/62">Paste the hosted payment link into <code>config/payments.local.php</code> using the <code>$<?php echo htmlspecialchars($plan); ?>PaymentLink</code> variable for this plan.</p>
                            </div>

                            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                                <a href="<?php echo htmlspecialchars($retryUrl); ?>" class="cta-primary px-5 py-3 text-sm"><?php echo $canceled ? "Retry Checkout" : "Reload Checkout"; ?></a>
                                <a href="landing.php#pricing" class="cta-secondary px-5 py-3 text-sm">Back to Pricing</a>
                                <a href="login.php?plan=<?php echo urlencode($plan); ?>" class="cta-secondary px-5 py-3 text-sm">Already Have An Account</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <?php if ($checkoutDestination !== "" && !$canceled): ?>
        <script>
            window.setTimeout(function () {
                window.location.href = <?php echo json_encode($checkoutDestination); ?>;
            }, 1200);
        </script>
    <?php endif; ?>
</body>
</html>
