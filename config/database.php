<?php
$host = getenv("BT_DB_HOST") ?: (getenv("MYSQLHOST") ?: "localhost");
$user = getenv("BT_DB_USER") ?: (getenv("MYSQLUSER") ?: "root");
$pass = getenv("BT_DB_PASSWORD") ?: (getenv("MYSQLPASSWORD") ?: "");
$db   = getenv("BT_DB_NAME") ?: (getenv("MYSQLDATABASE") ?: "budgettracker_pro");
$portRaw = getenv("BT_DB_PORT") ?: (getenv("MYSQLPORT") ?: "3306");
$port = is_numeric($portRaw) ? (int) $portRaw : 3306;
$btAllowDegradedMode = defined("BT_ALLOW_DB_DEGRADED") && BT_ALLOW_DB_DEGRADED;

$localConfig = __DIR__ . "/database.local.php";
if (file_exists($localConfig)) {
    require $localConfig;
}

$conn = null;
$GLOBALS["bt_db_error_message"] = "";

function btDatabaseAvailable() {
    global $conn;
    return $conn instanceof mysqli;
}

function btDatabaseStatusMessage() {
    return (string) ($GLOBALS["bt_db_error_message"] ?? "");
}

function btRenderDatabaseUnavailablePage() {
    http_response_code(503);
    header("Content-Type: text/html; charset=utf-8");
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Temporarily Unavailable</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-2xl rounded-2xl border border-amber-500/30 bg-slate-900 p-8 shadow-2xl">
        <p class="text-xs uppercase tracking-[0.2em] text-amber-300">Temporarily Unavailable</p>
        <h1 class="mt-3 text-3xl font-semibold">We could not reach the application database.</h1>
        <p class="mt-4 text-slate-300">
            The site is online, but account features such as login, registration, and password recovery are temporarily unavailable.
        </p>
        <p class="mt-4 text-sm text-slate-400">
            Please verify the production database connection and schema configuration, then try again.
        </p>
        <div class="mt-6">
            <a href="/" class="inline-flex items-center justify-center rounded-lg bg-emerald-500 px-4 py-3 text-sm font-medium text-slate-950 transition hover:bg-emerald-400">
                Return to Homepage
            </a>
        </div>
    </div>
</body>
</html>
<?php
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $db, $port);
    $conn->set_charset("utf8mb4");
} catch (Throwable $e) {
    error_log(sprintf(
        "Budget Tracker database bootstrap failed for host=%s db=%s port=%d: %s",
        $host,
        $db,
        $port,
        $e->getMessage()
    ));

    $GLOBALS["bt_db_error_message"] = "Account services are temporarily unavailable while we reconnect to the database.";

    if (PHP_SAPI === "cli" && !$btAllowDegradedMode) {
        throw $e;
    }

    if (!$btAllowDegradedMode) {
        btRenderDatabaseUnavailablePage();
        exit;
    }
}
