<?php
session_start();
define("BT_ALLOW_DB_DEGRADED", true);
require_once __DIR__ . "/../src/auth.php";
require_once __DIR__ . "/../src/purchases.php";

$error = "";
$email = trim((string) ($_SESSION["completed_purchase_email"] ?? ""));
$selectedPlan = normalizePlan($_GET["plan"] ?? "", "");
$completedPlan = normalizePlan($_SESSION["completed_purchase_plan"] ?? "", "");
$purchaseToken = trim((string) ($_POST["purchase_token"] ?? ($_GET["purchase_token"] ?? ($_SESSION["completed_purchase_token"] ?? ""))));
$purchaseEmail = trim((string) ($_SESSION["completed_purchase_email"] ?? ""));
$purchaseClaim = $purchaseToken !== "" ? btGetPurchaseClaimByToken($purchaseToken) : null;
if ($purchaseClaim) {
    $claimPlan = normalizePlan((string) ($purchaseClaim["plan"] ?? ""), "");
    if ($selectedPlan === "" && $claimPlan !== "") {
        $selectedPlan = $claimPlan;
    }
    if ($completedPlan === "" && $claimPlan !== "") {
        $completedPlan = $claimPlan;
        $_SESSION["completed_purchase_plan"] = $claimPlan;
    }
    if ($purchaseEmail === "" && !empty($purchaseClaim["stripe_customer_email"])) {
        $purchaseEmail = trim((string) $purchaseClaim["stripe_customer_email"]);
        $_SESSION["completed_purchase_email"] = $purchaseEmail;
        if ($email === "") {
            $email = $purchaseEmail;
        }
    }
}
$planLabels = [
    "starter" => "Starter",
    "growth" => "Growth",
    "scale" => "Scale",
];
$selectedPlanLabel = $planLabels[$selectedPlan] ?? null;
$completedPlanLabel = $planLabels[$completedPlan] ?? null;
$canCreateAccount = $completedPlan !== "";
$showForgotPassword = $completedPlan !== "";
$authAvailable = btDatabaseAvailable();
$authStatusMessage = btDatabaseStatusMessage();
$requestMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

if ($requestMethod === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $postedPlan = strtolower(trim($_POST["plan"] ?? $selectedPlan));
    if (!isset($planLabels[$postedPlan])) {
        $postedPlan = "";
    }

    if (!$authAvailable) {
        $error = $authStatusMessage !== "" ? $authStatusMessage : "Sign in is temporarily unavailable.";
    } else {
        $user = loginUser($email, $password);

        if ($user) {
            session_regenerate_id(true);
            if ($completedPlan !== "" && ($postedPlan === "" || $postedPlan === $completedPlan)) {
                $claimedPurchase = btClaimPurchaseForUser((int) $user["id"], $email, $purchaseToken, $completedPlan);
                $activatedPlan = $completedPlan;
                if (!empty($claimedPurchase["ok"]) && !empty($claimedPurchase["plan"])) {
                    $activatedPlan = normalizePlan($claimedPurchase["plan"], $completedPlan);
                }
                updateUserPlan((int) $user["id"], $activatedPlan);
                unset($_SESSION["completed_purchase_plan"], $_SESSION["pending_plan"], $_SESSION["completed_purchase_token"], $_SESSION["pending_purchase_token"], $_SESSION["completed_purchase_email"]);
                $_SESSION["purchase_flash"] = $planLabels[$activatedPlan] . " access is now active on your account.";
                header("Location: dashboard.php");
                exit;
            }

            if ($postedPlan !== "") {
                $_SESSION["pending_plan"] = $postedPlan;
                header("Location: checkout.php?plan=" . urlencode($postedPlan));
                exit;
            }

            header("Location: dashboard.php");
            exit;
        }
        $error = "Invalid login credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login - Budget Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f9f8f6;
            --panel: #ffffff;
            --panel-soft: rgba(255, 255, 255, 0.72);
            --ink: #0a0a0b;
            --muted: #5b5b61;
            --line: rgba(10, 10, 11, 0.08);
            --accent: #0052ff;
            --accent-strong: #0040c5;
            --shadow: 0 24px 70px rgba(17, 24, 39, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Manrope", sans-serif;
            color: var(--ink);
            background: #f9f8f6;
        }

        h1,
        h2,
        h3 {
            font-family: "Playfair Display", serif;
            letter-spacing: -0.035em;
            line-height: 0.98;
        }

        .mono {
            font-family: "JetBrains Mono", monospace;
        }

        .shell {
            max-width: 80rem;
            margin: 0 auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .glass {
            background: rgba(249, 248, 246, 0.78);
            backdrop-filter: blur(14px);
        }

        .panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.88));
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
        }

        .panel-soft {
            background: var(--panel-soft);
            border: 1px solid rgba(10, 10, 11, 0.06);
        }

        .eyebrow {
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .cta-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 999px;
            background: #0052ff;
            color: #fff;
            font-weight: 500;
            transition: all 180ms ease;
        }

        .cta-primary:hover {
            background: #0040c5;
            transform: translateY(-2px);
        }

        .cta-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 999px;
            border: 1px solid rgba(10, 10, 11, 0.15);
            background: transparent;
            font-weight: 500;
            transition: all 180ms ease;
        }

        .cta-secondary:hover {
            transform: translateY(-2px);
            background: rgba(10, 10, 11, 0.03);
        }

        .field {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid rgba(10, 10, 11, 0.12);
            background: rgba(255, 255, 255, 0.82);
            color: var(--ink);
            padding: 0.9rem 1rem;
            outline: none;
            transition: border-color 180ms ease, box-shadow 180ms ease, background 180ms ease;
        }

        .field::placeholder {
            color: rgba(91, 91, 97, 0.7);
        }

        .field:focus {
            border-color: rgba(0, 82, 255, 0.55);
            box-shadow: 0 0 0 3px rgba(0, 82, 255, 0.14);
            background: #fff;
        }

        .field:disabled,
        .cta-primary:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
        }

        .hero-mesh {
            position: absolute;
            inset: 0;
            opacity: 0.04;
            pointer-events: none;
            background-image:
                linear-gradient(#0A0A0B 1px, transparent 1px),
                linear-gradient(90deg, #0A0A0B 1px, transparent 1px);
            background-size: 64px 64px;
        }
    </style>
</head>
<body>
    <header class="sticky top-0 z-30 border-b border-black/5 glass">
        <div class="shell py-4 flex items-center justify-between gap-6">
            <a href="landing.php#top" class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-[#0A0A0B] flex items-center justify-center">
                    <span class="block w-3 h-3 bg-[#0052FF] rounded-sm rotate-12"></span>
                </span>
                <span class="text-xl tracking-tight" style="font-family: 'Playfair Display', serif;">Budget Tracker</span>
            </a>
            <div class="hidden md:flex items-center gap-3">
                <a href="landing.php#pricing" class="cta-secondary px-4 py-2.5 text-sm">Pricing</a>
                <a href="landing.php#faq" class="cta-secondary px-4 py-2.5 text-sm">FAQ</a>
            </div>
        </div>
    </header>

    <main class="relative overflow-hidden py-10 md:py-16">
        <div class="hero-mesh"></div>
        <div class="shell relative">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                <section class="lg:col-span-6">
                    <div class="flex items-center gap-3 mb-7">
                        <span class="eyebrow text-[var(--accent)]">Client Access · Purchase First</span>
                        <span class="h-px flex-1 bg-black/10 max-w-[140px]"></span>
                    </div>
                    <h1 class="text-5xl md:text-7xl lg:text-[72px] max-w-4xl">
                        Client login with the <span class="italic text-black/55">same calm control</span> as the landing page.
                    </h1>
                    <p class="mt-6 text-lg md:text-xl leading-relaxed text-black/68 max-w-2xl">
                        Sign in after checkout, activate the plan you purchased, and get back into Budget Tracker without friction.
                    </p>

                    <div class="mt-10 grid sm:grid-cols-3 gap-4">
                        <div class="panel-soft rounded-3xl p-5">
                            <p class="eyebrow text-[var(--accent)]">01</p>
                            <h2 class="text-2xl mt-3">Choose a plan</h2>
                            <p class="text-sm text-black/62 leading-6 mt-3">Start from pricing, complete checkout, and come back here with the same email you want on the account.</p>
                        </div>
                        <div class="panel-soft rounded-3xl p-5">
                            <p class="eyebrow text-[var(--accent)]">02</p>
                            <h2 class="text-2xl mt-3">Create or sign in</h2>
                            <p class="text-sm text-black/62 leading-6 mt-3">If you already paid, sign in below or create an account and we attach the plan automatically.</p>
                        </div>
                        <div class="panel-soft rounded-3xl p-5">
                            <p class="eyebrow text-[var(--accent)]">03</p>
                            <h2 class="text-2xl mt-3">Keep going</h2>
                            <p class="text-sm text-black/62 leading-6 mt-3">Once activated, your access follows you any time you sign in again from this page.</p>
                        </div>
                    </div>
                </section>

                <section class="lg:col-span-6">
                    <div class="panel rounded-[28px] p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-9 h-9 rounded-xl bg-[#0A0A0B] flex items-center justify-center">
                                <span class="block w-3 h-3 bg-[#0052FF] rounded-sm rotate-12"></span>
                            </span>
                            <div>
                                <p class="eyebrow text-[var(--accent)]">Client Login</p>
                                <p class="text-sm text-black/60 mt-1">Sign in if you already completed checkout.</p>
                            </div>
                        </div>

                        <?php if (!$authAvailable && $authStatusMessage !== ""): ?>
                            <div class="mb-4 rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                                <?php echo htmlspecialchars($authStatusMessage); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($completedPlanLabel !== null): ?>
                            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                                Your <strong><?php echo htmlspecialchars($completedPlanLabel); ?></strong> payment is complete. Sign in to activate it on your account.
                            </div>
                        <?php elseif ($selectedPlanLabel !== null): ?>
                            <div class="mb-4 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                                You selected the <strong><?php echo htmlspecialchars($selectedPlanLabel); ?></strong> plan. Sign in to continue your purchase flow.
                            </div>
                        <?php else: ?>
                            <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                                Purchase a plan first, then return here to register or sign in.
                            </div>
                        <?php endif; ?>

                        <?php if ($error !== ""): ?>
                            <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="plan" value="<?php echo htmlspecialchars($selectedPlan); ?>">
                            <input type="hidden" name="purchase_token" value="<?php echo htmlspecialchars($purchaseToken); ?>">
                            <div>
                                <label class="mono text-[10px] uppercase tracking-[0.22em] text-black/55">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    value="<?php echo htmlspecialchars($email); ?>"
                                    required
                                    <?php echo !$authAvailable ? "disabled" : ""; ?>
                                    class="field mt-2"
                                    placeholder="you@company.com"
                                >
                                <?php if ($purchaseEmail !== ""): ?>
                                    <p class="mt-2 text-xs text-black/45">Payment email on file: <?php echo htmlspecialchars($purchaseEmail); ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="mono text-[10px] uppercase tracking-[0.22em] text-black/55">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    required
                                    <?php echo !$authAvailable ? "disabled" : ""; ?>
                                    class="field mt-2"
                                    placeholder="Enter your password"
                                >
                            </div>
                            <button
                                type="submit"
                                <?php echo !$authAvailable ? "disabled" : ""; ?>
                                class="cta-primary w-full px-5 py-3 text-sm"
                            >
                                <?php echo $authAvailable ? "Sign In" : "Temporarily Unavailable"; ?>
                            </button>
                            <?php if ($showForgotPassword && $authAvailable): ?>
                                <div class="text-right">
                                    <a href="forgot_password.php" class="text-sm text-[var(--accent)] hover:text-[var(--accent-strong)]">Forgot password?</a>
                                </div>
                            <?php endif; ?>
                        </form>

                        <div class="mt-6 rounded-3xl bg-[#0A0A0B] text-white p-5">
                            <p class="eyebrow text-[#7aa2ff]">Need an account?</p>
                            <?php if ($canCreateAccount): ?>
                                <p class="mt-3 text-sm text-white/74 leading-6">
                                    Your purchase is ready to attach. Create the account with the same email you used for checkout.
                                </p>
                                <a href="register.php?plan=<?php echo urlencode($completedPlan); ?><?php echo $purchaseToken !== "" ? "&purchase_token=" . urlencode($purchaseToken) : ""; ?>" class="cta-primary mt-5 px-5 py-3 text-sm">
                                    Create Account
                                </a>
                            <?php else: ?>
                                <p class="mt-3 text-sm text-white/74 leading-6">
                                    Account creation opens after a completed purchase so we know which plan to activate for you.
                                </p>
                                <a href="landing.php#pricing" class="cta-secondary mt-5 px-5 py-3 text-sm text-white border-white/20 bg-white/5">
                                    View Pricing
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
