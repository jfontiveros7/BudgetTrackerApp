<?php
session_start();
require_once "../src/auth.php";

if (isset($_SESSION["user_id"], $_SESSION["user_email"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (registerUser($name, $email, $password)) {
        header("Location: login.php");
        exit;
    }

    $error = "Unable to register user. The email may already be in use.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
        <h1 class="text-2xl font-semibold mb-2 text-center">Create Your Account</h1>
        <p class="text-sm text-slate-400 mb-6 text-center">Join Budget Tracker App and start organizing your finances.</p>

        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded border border-rose-500 bg-rose-950/40 text-rose-200 px-3 py-2 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm mb-1">Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required
                    class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
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
            <button type="submit"
                class="w-full inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-medium py-2 text-sm transition">
                Create Account
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            Already have an account?
            <a href="login.php" class="text-emerald-400 hover:text-emerald-300">Sign in</a>
        </p>
    </div>
</body>
</html>
