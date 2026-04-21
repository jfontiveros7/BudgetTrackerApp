<?php
session_start();
require_once "../src/auth.php";

if (isset($_SESSION["user_id"], $_SESSION["user_email"])) {
    header("Location: dashboard.php");
    exit;
}

$token = trim((string) ($_GET["token"] ?? $_POST["token"] ?? ""));
$error = "";
$success = "";
$tokenValid = false;

if ($token !== "") {
    $tokenValid = getPasswordResetByToken($token) !== null;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = (string) ($_POST["password"] ?? "");
    $confirmPassword = (string) ($_POST["confirm_password"] ?? "");

    if ($token === "" || !$tokenValid) {
        $error = "This reset link is invalid or expired.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        if (resetPasswordWithToken($token, $password)) {
            $success = "Password updated successfully. You can now sign in.";
            $tokenValid = false;
        } else {
            $error = "Unable to reset password. Try requesting a new link.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
        <p class="app-kicker text-center">Account Recovery</p>
        <h1 class="text-2xl font-semibold mb-2 text-center">Reset Password</h1>
        <p class="text-sm text-slate-400 mb-6 text-center">Set a new password for your account.</p>

        <?php if ($error !== ""): ?>
            <div class="mb-4 rounded border border-rose-500 bg-rose-950/40 text-rose-200 px-3 py-2 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ""): ?>
            <div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 text-emerald-200 px-3 py-2 text-sm">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <p class="text-center text-sm text-slate-300 mt-4">
                <a href="login.php" class="text-emerald-400 hover:text-emerald-300">Go to sign in</a>
            </p>
        <?php elseif ($tokenValid): ?>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div>
                    <label class="block text-sm mb-1">New Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        minlength="8"
                        class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

                <div>
                    <label class="block text-sm mb-1">Confirm Password</label>
                    <input
                        type="password"
                        name="confirm_password"
                        required
                        minlength="8"
                        class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-medium py-2 text-sm transition"
                >
                    Save New Password
                </button>
            </form>
        <?php else: ?>
            <div class="rounded border border-amber-500/40 bg-amber-500/10 text-amber-200 px-3 py-2 text-sm">
                This reset link is invalid or expired. Please request a new one.
            </div>
            <p class="text-center text-sm text-slate-300 mt-4">
                <a href="forgot_password.php" class="text-emerald-400 hover:text-emerald-300">Request a new reset link</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
