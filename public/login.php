<?php
session_start();
require_once __DIR__ . "/../src/auth.php";

$error = "";
$email = "";
$selectedPlan = normalizePlan($_GET["plan"] ?? "", "");
$completedPlan = normalizePlan($_SESSION["completed_purchase_plan"] ?? "", "");
$planLabels = [
    "starter" => "Starter",
    "growth" => "Growth",
    "scale" => "Scale",
];
$selectedPlanLabel = $planLabels[$selectedPlan] ?? null;
$completedPlanLabel = $planLabels[$completedPlan] ?? null;
$canCreateAccount = $completedPlan !== "";
$showForgotPassword = $completedPlan !== "";


if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $postedPlan = strtolower(trim($_POST["plan"] ?? $selectedPlan));
    if (!isset($planLabels[$postedPlan])) {
        $postedPlan = "";
    }

    $user = loginUser($email, $password);

    if ($user) {
        session_regenerate_id(true);
        if ($completedPlan !== "" && ($postedPlan === "" || $postedPlan === $completedPlan)) {
            updateUserPlan((int) $user["id"], $completedPlan);
            unset($_SESSION["completed_purchase_plan"], $_SESSION["pending_plan"]);
            $_SESSION["purchase_flash"] = $planLabels[$completedPlan] . " access is now active on your account.";
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
    } else {
        $error = "Invalid login credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
        <h1 class="text-2xl font-semibold mb-2 text-center">Budget Tracker App</h1>
        <p class="text-sm text-slate-400 mb-6 text-center">Sign in to manage your finances.</p>

        <?php if ($completedPlanLabel !== null): ?>
            <div class="mb-4 rounded border border-emerald-500/30 bg-emerald-500/10 px-3 py-2 text-sm text-emerald-100">
                Your <strong><?php echo htmlspecialchars($completedPlanLabel); ?></strong> payment is complete. Sign in to activate it on your account.
            </div>
        <?php elseif ($selectedPlanLabel !== null): ?>
            <div class="mb-4 rounded border border-amber-500/30 bg-amber-500/10 px-3 py-2 text-sm text-amber-100">
                You selected the <strong><?php echo htmlspecialchars($selectedPlanLabel); ?></strong> plan. Sign in to continue your purchase flow.
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded border border-rose-500 bg-rose-950/40 text-rose-200 px-3 py-2 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="hidden" name="plan" value="<?php echo htmlspecialchars($selectedPlan); ?>">
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required
                       class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <button
                class="w-full mt-2 inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-medium py-2 text-sm transition">
                Sign In
            </button>
            <?php if ($showForgotPassword): ?>
                <div class="text-right">
                    <a href="forgot_password.php" class="text-sm text-sky-300 hover:text-sky-200">Forgot password?</a>
                </div>
            <?php endif; ?>
        </form>
        <?php if ($canCreateAccount): ?>
            <p class="mt-6 text-center text-sm text-slate-400">
                Need an account?
                <a href="register.php?plan=<?php echo urlencode($completedPlan); ?>" class="text-emerald-400 hover:text-emerald-300">Create one</a>
            </p>
        <?php else: ?>
            <p class="mt-6 text-center text-sm text-slate-400">
                Account creation becomes available after payment is complete.
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
