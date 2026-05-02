<?php
session_start();
define("BT_ALLOW_DB_DEGRADED", true);
require_once __DIR__ . "/../src/auth.php";

if (isset($_SESSION["user_id"], $_SESSION["user_email"])) {
    header("Location: dashboard.php");
    exit;
}

$email = "";
$success = "";
$error = "";
$resetLink = "";
$authAvailable = btDatabaseAvailable();
$authStatusMessage = btDatabaseStatusMessage();
$requestMethod = $_SERVER["REQUEST_METHOD"] ?? "GET";

if ($requestMethod === "POST") {
    $email = trim((string) ($_POST["email"] ?? ""));

    if (!$authAvailable) {
        $error = $authStatusMessage !== "" ? $authStatusMessage : "Password recovery is temporarily unavailable.";
    } elseif ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $token = createPasswordResetToken($email);
        $success = "If that email exists, a password reset link has been generated.";

        if ($token !== null) {
            $resetLink = "reset_password.php?token=" . urlencode($token);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
        <p class="app-kicker text-center">Account Recovery</p>
        <h1 class="text-2xl font-semibold mb-2 text-center">Forgot Password</h1>
        <p class="text-sm text-slate-400 mb-6 text-center">Enter your email and we will generate a reset link.</p>

        <?php if (!$authAvailable && $authStatusMessage !== ""): ?>
            <div class="mb-4 rounded border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-sm text-amber-100">
                <?php echo htmlspecialchars($authStatusMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="mb-4 rounded border border-rose-500 bg-rose-950/40 text-rose-200 px-3 py-2 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ""): ?>
            <div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 text-emerald-200 px-3 py-2 text-sm">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($resetLink !== ""): ?>
            <div class="mb-4 rounded border border-sky-500/40 bg-sky-500/10 text-sky-100 px-3 py-2 text-sm break-all">
                <p class="font-semibold mb-1">Reset Link</p>
                <a href="<?php echo htmlspecialchars($resetLink); ?>" class="text-sky-300 hover:text-sky-200">
                    <?php echo htmlspecialchars($resetLink); ?>
                </a>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required
                    <?php echo !$authAvailable ? "disabled" : ""; ?>
                    class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
            </div>
            <button
                type="submit"
                <?php echo !$authAvailable ? "disabled" : ""; ?>
                class="w-full inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-medium py-2 text-sm transition"
            >
                <?php echo $authAvailable ? "Generate Reset Link" : "Temporarily Unavailable"; ?>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            Remembered your password?
            <a href="login.php" class="text-emerald-400 hover:text-emerald-300">Back to sign in</a>
        </p>
    </div>
</body>
</html>
