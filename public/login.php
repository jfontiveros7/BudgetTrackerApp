<?php
session_start();
require_once "../src/auth.php";

$error = "";
$email = "";

if (isset($_SESSION["user"])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $user = loginUser($email, $password);

    if ($user) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = (int) $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_email"] = $user["email"];
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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
        <h1 class="text-2xl font-semibold mb-2 text-center">Budget Tracker App</h1>
        <p class="text-sm text-slate-400 mb-6 text-center">Sign in to manage your finances.</p>

        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded border border-rose-500 bg-rose-950/40 text-rose-200 px-3 py-2 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
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
            <div class="text-right">
                <a href="forgot_password.php" class="text-sm text-sky-300 hover:text-sky-200">Forgot password?</a>
            </div>
        </form>
        <p class="mt-6 text-center text-sm text-slate-400">
            Need an account?
            <a href="register.php" class="text-emerald-400 hover:text-emerald-300">Create one</a>
        </p>
    </div>
</body>
</html>
