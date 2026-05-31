<?php
session_start();
require_once __DIR__ . "/../src/auth.php";

$plan = normalizePlan($_GET["plan"] ?? ($_SESSION["pending_plan"] ?? ""), "");
$planLabels = [
    "starter" => "Monitor",
    "growth" => "Control",
    "scale" => "Command",
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
    <title>Purchase Complete - Driftwise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,500;6..72,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --bg: #f7f1e8;
            --surface: #fffaf4;
            --line: rgba(102, 82, 61, 0.14);
            --text: #231912;
            --muted: #6e6053;
            --accent: #0c7a70;
            --accent-strong: #0a655d;
            --shadow: 0 18px 42px rgba(93, 64, 30, 0.08);
        }
        body { font-family: "Space Grotesk", sans-serif; background: linear-gradient(180deg, #fffdf9 0%, var(--bg) 46%, #efe3d3 100%); color: var(--text); }
        h1 { font-family: "Newsreader", serif; letter-spacing: -0.03em; line-height: 0.98; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-2xl rounded-3xl border p-8 shadow-2xl" style="border-color: rgba(102, 82, 61, 0.14); background: linear-gradient(180deg, rgba(255,251,245,0.95), rgba(251,245,236,0.96)); box-shadow: var(--shadow);">
        <p class="text-xs uppercase tracking-[0.2em]" style="color: var(--accent);">Payment Complete</p>
        <h1 class="mt-3 text-3xl font-semibold"><?php echo htmlspecialchars($planLabel); ?> is ready</h1>
        <p class="mt-4" style="color: var(--muted);">Your payment went through. Create an account now to activate your plan, or sign in if you already have one.</p>

        <div class="mt-6 rounded-2xl border p-5" style="border-color: rgba(102, 82, 61, 0.08); background: rgba(255,255,255,0.68);">
            <p class="text-sm" style="color: var(--text);">Next step:</p>
            <p class="mt-2 text-sm" style="color: var(--muted);">Use the same email you want associated with your Driftwise access. We will attach your <strong><?php echo htmlspecialchars($planLabel); ?></strong> plan when you register or sign in from this page.</p>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <a href="register.php?plan=<?php echo urlencode($plan); ?>" class="inline-flex items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition" style="background: var(--accent);" onmouseover="this.style.background='var(--accent-strong)'" onmouseout="this.style.background='var(--accent)'">Create Account</a>
            <a href="login.php?plan=<?php echo urlencode($plan); ?>" class="inline-flex items-center justify-center rounded-lg border px-4 py-3 text-sm transition" style="border-color: rgba(105,84,63,0.16); color: var(--text); background: rgba(255,255,255,0.55);" onmouseover="this.style.background='rgba(12,122,112,0.10)'; this.style.borderColor='rgba(12,122,112,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.55)'; this.style.borderColor='rgba(105,84,63,0.16)'">I Already Have An Account</a>
        </div>
    </div>
</body>
</html>
