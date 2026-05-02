<?php
session_start();
define("BT_ALLOW_DB_DEGRADED", true);
require_once __DIR__ . "/../src/auth.php";


if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$name = "";
$email = "";
$selectedPlan = normalizePlan($_POST["plan"] ?? ($_GET["plan"] ?? ($_SESSION["completed_purchase_plan"] ?? "")), "");
$completedPlan = normalizePlan($_SESSION["completed_purchase_plan"] ?? "", "");
$planLabels = [
    "starter" => "Starter",
    "growth" => "Growth",
    "scale" => "Scale",
];
$selectedPlanLabel = $planLabels[$selectedPlan] ?? null;
$authAvailable = btDatabaseAvailable();
$authStatusMessage = btDatabaseStatusMessage();
$requestMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";

if ($completedPlan === "" || $selectedPlan === "" || $selectedPlan !== $completedPlan) {
    header("Location: login.php");
    exit;
}

if ($requestMethod === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $planForAccount = $selectedPlan !== "" ? $selectedPlan : "growth";

    if (!$authAvailable) {
        $error = $authStatusMessage !== "" ? $authStatusMessage : "Account creation is temporarily unavailable.";
    } elseif (registerUser($name, $email, $password, $planForAccount)) {
        // Auto-login after registration
        $user = loginUser($email, $password);
        if ($user) {
            session_regenerate_id(true);
            if ($selectedPlan !== "") {
                updateUserPlan((int) $user["id"], $selectedPlan);
                unset($_SESSION["completed_purchase_plan"], $_SESSION["pending_plan"]);
                $_SESSION["purchase_flash"] = $planLabels[$selectedPlan] . " access is now active on your account.";
            }
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: login.php" . ($selectedPlan !== "" ? "?plan=" . urlencode($selectedPlan) : ""));
            exit;
        }
    }

    if ($error === "") {
        $error = "Unable to register user. The email may already be in use.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
        <h1 class="text-2xl font-semibold mb-2 text-center">Create Your Account</h1>
        <p class="text-sm text-slate-400 mb-6 text-center">Join Budget Tracker App and start organizing your finances.</p>

        <?php if (!$authAvailable && $authStatusMessage !== ""): ?>
            <div class="mb-4 rounded border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-sm text-amber-100">
                <?php echo htmlspecialchars($authStatusMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($selectedPlanLabel !== null): ?>
            <div class="mb-4 rounded border border-emerald-500/30 bg-emerald-500/10 px-3 py-2 text-sm text-emerald-100">
                Create your account to activate the <strong><?php echo htmlspecialchars($selectedPlanLabel); ?></strong> plan you purchased.
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
                <label class="block text-sm mb-1">Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required
                    <?php echo !$authAvailable ? "disabled" : ""; ?>
                    class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required
                    <?php echo !$authAvailable ? "disabled" : ""; ?>
                    class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" required
                    <?php echo !$authAvailable ? "disabled" : ""; ?>
                    class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <button type="submit"
                <?php echo !$authAvailable ? "disabled" : ""; ?>
                class="w-full inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-medium py-2 text-sm transition">
                <?php echo $authAvailable ? "Create Account" : "Temporarily Unavailable"; ?>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            Already have an account?
            <a href="login.php<?php echo $selectedPlan !== "" ? "?plan=" . urlencode($selectedPlan) : ""; ?>" class="text-emerald-400 hover:text-emerald-300">Sign in</a>
        </p>
    </div>
</body>
</html>
