<?php
session_start();
require_once __DIR__ . "/../src/transactions.php";
require_once __DIR__ . "/../src/categories.php";

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
$categoryNames = array_values(array_filter(array_map(
    static fn($row) => trim((string) ($row["name"] ?? "")),
    $categories
)));

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
    <title>Add Transaction - Budget Tracker App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        input[type="date"] {
            color-scheme: dark;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(1.2);
            cursor: pointer;
            opacity: 1;
            background-color: rgba(148, 163, 184, 0.18);
            border-radius: 0.375rem;
            padding: 0.2rem;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex">
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col p-6">
        <h1 class="text-xl font-semibold mb-8">Budget Tracker App</h1>
        <nav class="space-y-2">
            <a href="dashboard.php" class="block px-3 py-2 rounded hover:bg-slate-800">Dashboard</a>
            <a href="add_transaction.php" class="block px-3 py-2 rounded bg-slate-800 text-slate-100">Add Transaction</a>
            <a href="settings.php" class="block px-3 py-2 rounded hover:bg-slate-800">Settings</a>
            <?php if ($isAdmin): ?>
                <a href="admin.php" class="block px-3 py-2 rounded hover:bg-slate-800">Admin</a>
            <?php endif; ?>
        </nav>
        <div class="mt-auto pt-6 border-t border-slate-800">
            <p class="text-sm text-slate-400">Quick Add Tips</p>
            <div class="mt-3 space-y-2 text-sm text-slate-300">
                <p class="rounded-lg bg-slate-800/60 px-3 py-2">Starbucks 6.45</p>
                <p class="rounded-lg bg-slate-800/60 px-3 py-2">Paid rent 850</p>
                <p class="rounded-lg bg-slate-800/60 px-3 py-2">Got paid 1200</p>
                <p class="rounded-lg bg-slate-800/60 px-3 py-2">Walmart groceries 42.18 yesterday</p>
            </div>
            <a href="logout.php" class="text-sm text-red-400 hover:text-red-300 mt-3 inline-block">Logout</a>
        </div>
    </aside>

    <main class="flex-1 p-10">
        <div class="app-status-strip">
            <span class="app-status-pill">transaction mode: quick add</span>
            <span class="app-status-pill">input: natural language</span>
            <span class="app-status-pill">save target: realtime</span>
        </div>
        <div class="max-w-2xl">
            <div class="mb-8">
                <p class="app-kicker">Operations / Transactions</p>
                <h2 class="text-3xl font-semibold">Add Transaction</h2>
                <p class="text-slate-400 text-sm mt-2">Record a new income or expense entry in your budget.</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl mb-8">
                <div class="mb-5">
                    <h3 class="text-xl font-semibold">Quick Add</h3>
                    <p class="text-slate-400 text-sm mt-2">Type a transaction naturally and let the app fill the form for you. Review it before saving.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="quick-add-input" class="block text-sm mb-1">Natural Language Input</label>
                        <input
                            id="quick-add-input"
                            type="text"
                            placeholder='Try "Starbucks 6.45", "Paid rent 850", or "Walmart groceries 42.18 yesterday"'
                            class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        >
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            id="quick-add-parse"
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg bg-sky-500 hover:bg-sky-400 text-slate-950 font-medium px-5 py-2.5 text-sm transition"
                        >
                            Parse Into Form
                        </button>
                        <button
                            id="quick-add-clear"
                            type="button"
                            class="inline-flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium px-5 py-2.5 text-sm transition"
                        >
                            Clear Quick Add
                        </button>
                    </div>

                    <div id="quick-add-preview" class="hidden rounded-xl border border-slate-800 bg-slate-950/60 p-5">
                        <div class="flex flex-col gap-2 mb-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-200">Parsed Result</h4>
                                <p class="text-sm text-slate-400 mt-1">This is what the app understood from your natural-language entry.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
                            <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Amount</p>
                                <p id="preview-amount" class="mt-2 text-lg font-semibold text-slate-100">-</p>
                            </div>
                            <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Category</p>
                                <p id="preview-category" class="mt-2 text-lg font-semibold text-slate-100">-</p>
                            </div>
                            <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Merchant</p>
                                <p id="preview-merchant" class="mt-2 text-lg font-semibold text-slate-100">-</p>
                            </div>
                            <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Date</p>
                                <p id="preview-date" class="mt-2 text-lg font-semibold text-slate-100">-</p>
                            </div>
                            <div class="rounded-lg border border-slate-800 bg-slate-900/80 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Type</p>
                                <p id="preview-type" class="mt-2 text-lg font-semibold text-slate-100">-</p>
                            </div>
                        </div>
                    </div>

                    <div id="quick-add-feedback" class="hidden rounded-lg border px-4 py-3 text-sm"></div>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-8 shadow-xl">
                <form method="POST" class="space-y-5">
                    <div>
                        <label class="block text-sm mb-1">Category</label>
                        <input id="transaction-category" name="category" list="category-options" value="<?php echo htmlspecialchars($category); ?>" placeholder="Groceries, Salary, Rent..." required
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
                        <input id="transaction-description" name="description" value="<?php echo htmlspecialchars($description); ?>" placeholder="Optional details"
                            class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm mb-1">Amount</label>
                            <input id="transaction-amount" name="amount" type="number" step="0.01" value="<?php echo htmlspecialchars($amount); ?>" placeholder="0.00" required
                                class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Type</label>
                            <select id="transaction-type" name="type"
                                class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="income" <?php echo $type === "income" ? "selected" : ""; ?>>Income</option>
                                <option value="expense" <?php echo $type === "expense" ? "selected" : ""; ?>>Expense</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Transaction Date</label>
                        <input id="transaction-date" name="transaction_date" type="date" value="<?php echo htmlspecialchars($transactionDate); ?>"
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
    <script>
        const knownCategories = <?php echo json_encode($categoryNames); ?>;
        const quickAddInput = document.getElementById('quick-add-input');
        const quickAddParseButton = document.getElementById('quick-add-parse');
        const quickAddClearButton = document.getElementById('quick-add-clear');
        const quickAddPreview = document.getElementById('quick-add-preview');
        const quickAddFeedback = document.getElementById('quick-add-feedback');
        const previewAmount = document.getElementById('preview-amount');
        const previewCategory = document.getElementById('preview-category');
        const previewMerchant = document.getElementById('preview-merchant');
        const previewDate = document.getElementById('preview-date');
        const previewType = document.getElementById('preview-type');
        const categoryInput = document.getElementById('transaction-category');
        const descriptionInput = document.getElementById('transaction-description');
        const amountInput = document.getElementById('transaction-amount');
        const typeInput = document.getElementById('transaction-type');
        const dateInput = document.getElementById('transaction-date');

        const keywordCategoryMap = {
            groceries: 'Groceries',
            grocery: 'Groceries',
            walmart: 'Groceries',
            target: 'Shopping',
            amazon: 'Shopping',
            rent: 'Rent',
            salary: 'Salary',
            paycheck: 'Salary',
            paid: 'Salary',
            starbucks: 'Dining Out',
            coffee: 'Dining Out',
            dining: 'Dining Out',
            restaurant: 'Dining Out',
            uber: 'Transportation',
            lyft: 'Transportation',
            gas: 'Transportation',
            fuel: 'Transportation',
            netflix: 'Subscriptions',
            spotify: 'Subscriptions',
            subscription: 'Subscriptions',
            subscriptions: 'Subscriptions',
            electric: 'Utilities',
            utility: 'Utilities',
            utilities: 'Utilities',
            doctor: 'Healthcare',
            pharmacy: 'Healthcare',
        };

        function titleCase(value) {
            return value
                .split(/\s+/)
                .filter(Boolean)
                .map((part) => part.charAt(0).toUpperCase() + part.slice(1).toLowerCase())
                .join(' ');
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function parseRelativeDate(text) {
            const today = new Date();
            const lowered = text.toLowerCase();
            if (lowered.includes('yesterday')) {
                const parsed = new Date(today);
                parsed.setDate(parsed.getDate() - 1);
                return formatDate(parsed);
            }
            if (lowered.includes('today')) {
                return formatDate(today);
            }
            return formatDate(today);
        }

        function detectType(text) {
            const lowered = text.toLowerCase();
            const incomeKeywords = ['got paid', 'payday', 'salary', 'income', 'deposit', 'bonus', 'paycheck', 'freelance'];
            return incomeKeywords.some((keyword) => lowered.includes(keyword)) ? 'income' : 'expense';
        }

        function detectAmount(text) {
            const matches = text.match(/-?\d+(?:\.\d{1,2})?/g);
            if (!matches || !matches.length) {
                return '';
            }
            return matches[matches.length - 1];
        }

        function detectKnownCategory(text, type) {
            const lowered = text.toLowerCase();
            const exactMatch = knownCategories.find((category) => lowered.includes(category.toLowerCase()));
            if (exactMatch) {
                return exactMatch;
            }

            for (const [keyword, category] of Object.entries(keywordCategoryMap)) {
                if (lowered.includes(keyword)) {
                    return category;
                }
            }

            return type === 'income' ? 'Salary' : 'Miscellaneous';
        }

        function detectMerchant(text, amount, category) {
            let merchant = text
                .replace(/-?\d+(?:\.\d{1,2})?/g, ' ')
                .replace(/\b(today|yesterday)\b/gi, ' ')
                .replace(/\b(got paid|paid|for|income|expense)\b/gi, ' ')
                .replace(new RegExp(`\\b${category}\\b`, 'ig'), ' ')
                .replace(/\s+/g, ' ')
                .trim();

            if (!merchant) {
                return '';
            }

            if (merchant.toLowerCase() === category.toLowerCase()) {
                return '';
            }

            return titleCase(merchant);
        }

        function parseQuickAdd(text) {
            const trimmed = text.trim();
            if (!trimmed) {
                return null;
            }

            const type = detectType(trimmed);
            const amount = detectAmount(trimmed);
            const category = detectKnownCategory(trimmed, type);
            const date = parseRelativeDate(trimmed);
            const merchant = detectMerchant(trimmed, amount, category);

            return {
                amount,
                category,
                merchant,
                date,
                type,
            };
        }

        function setFeedback(message, tone) {
            const toneClasses = tone === 'error'
                ? 'border-rose-500/30 bg-rose-500/10 text-rose-200'
                : 'border-emerald-500/30 bg-emerald-500/10 text-emerald-200';
            quickAddFeedback.className = `rounded-lg border px-4 py-3 text-sm ${toneClasses}`;
            quickAddFeedback.textContent = message;
            quickAddFeedback.classList.remove('hidden');
        }

        function updatePreview(parsed) {
            if (!parsed) {
                quickAddPreview.classList.add('hidden');
                previewAmount.textContent = '-';
                previewCategory.textContent = '-';
                previewMerchant.textContent = '-';
                previewDate.textContent = '-';
                previewType.textContent = '-';
                return;
            }

            previewAmount.textContent = parsed.amount ? `$${parsed.amount}` : '-';
            previewCategory.textContent = parsed.category || '-';
            previewMerchant.textContent = parsed.merchant || 'None';
            previewDate.textContent = parsed.date || '-';
            previewType.textContent = parsed.type ? titleCase(parsed.type) : '-';
            quickAddPreview.classList.remove('hidden');
        }

        quickAddParseButton.addEventListener('click', () => {
            const parsed = parseQuickAdd(quickAddInput.value);
            if (!parsed || !parsed.amount) {
                updatePreview(null);
                setFeedback('Could not find an amount yet. Try something like "Starbucks 6.45" or "Got paid 1200".', 'error');
                return;
            }

            updatePreview(parsed);
            amountInput.value = parsed.amount;
            categoryInput.value = parsed.category;
            descriptionInput.value = parsed.merchant;
            typeInput.value = parsed.type;
            dateInput.value = parsed.date;

            setFeedback(
                `Filled the form with amount $${parsed.amount}, category ${parsed.category}, ${parsed.type}, date ${parsed.date}${parsed.merchant ? `, and merchant ${parsed.merchant}` : ''}. Review it, then save when it looks right.`,
                'success'
            );
        });

        quickAddClearButton.addEventListener('click', () => {
            quickAddInput.value = '';
            updatePreview(null);
            quickAddFeedback.classList.add('hidden');
            quickAddInput.focus();
        });

        quickAddInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                quickAddParseButton.click();
            }
        });
    </script>
</body>
</html>
