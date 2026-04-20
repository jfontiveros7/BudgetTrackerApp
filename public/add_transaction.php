<?php
session_start();
require_once "../src/transactions.php";
require_once "../src/categories.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userEmail = $_SESSION["user_email"] ?? "";
$isAdmin = ($userEmail === "admin@example.com");

$category = "";
$description = "";
$amount = "";
$type = "expense";
$transactionDate = date("Y-m-d");
$categories = getCategories();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category = trim($_POST["category"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $amount = $_POST["amount"] ?? "";
    $type = $_POST["type"] ?? "expense";
    $transactionDate = $_POST["transaction_date"] ?? date("Y-m-d");

    addTransaction($_SESSION["user_id"], $category, $description, $amount, $type, $transactionDate);
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Transaction</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex">
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col p-6">
        <h1 class="text-xl font-semibold mb-8">BudgetTracker Pro</h1>
        <nav class="space-y-2">
            <a href="dashboard.php" class="block px-3 py-2 rounded hover:bg-slate-800">Dashboard</a>
            <a href="add_transaction.php" class="block px-3 py-2 rounded bg-slate-800 text-slate-100">Add Transaction</a>
            <?php if ($isAdmin): ?>
                <a href="admin.php" class="block px-3 py-2 rounded hover:bg-slate-800">Admin</a>
            <?php endif; ?>
        </nav>
        <div class="mt-auto pt-6 border-t border-slate-800">
            <p class="text-sm text-slate-400">Navigation</p>
            <a href="logout.php" class="text-sm text-red-400 hover:text-red-300 mt-3 inline-block">Logout</a>
        </div>
    </aside>

    <main class="flex-1 p-10">
        <div class="max-w-2xl">
            <div class="mb-8">
                <h2 class="text-3xl font-semibold">Add Transaction</h2>
                <p class="text-slate-400 text-sm mt-2">Record a new income or expense entry in your budget.</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
                <form method="POST" class="space-y-5">
                    <div>
                        <label class="block text-sm mb-1">Category</label>
                        <input name="category" list="category-options" value="<?php echo htmlspecialchars($category); ?>" placeholder="Groceries, Salary, Rent..." required
                            class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <?php if (!empty($categories)): ?>
                            <datalist id="category-options">
                                <?php foreach ($categories as $categoryOption): ?>
                                    <option value="<?php echo htmlspecialchars($categoryOption["name"]); ?>">
                                <?php endforeach; ?>
                            </datalist>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Description</label>
                        <input name="description" value="<?php echo htmlspecialchars($description); ?>" placeholder="Optional details"
                            class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm mb-1">Amount</label>
                            <input name="amount" type="number" step="0.01" value="<?php echo htmlspecialchars($amount); ?>" placeholder="0.00" required
                                class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Type</label>
                            <select name="type"
                                class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="income" <?php echo $type === "income" ? "selected" : ""; ?>>Income</option>
                                <option value="expense" <?php echo $type === "expense" ? "selected" : ""; ?>>Expense</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Transaction Date</label>
                        <input name="transaction_date" type="date" value="<?php echo htmlspecialchars($transactionDate); ?>"
                            class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-medium px-5 py-2.5 text-sm transition">
                            Save Transaction
                        </button>
                        <a href="dashboard.php"
                            class="inline-flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium px-5 py-2.5 text-sm transition">
                            Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
