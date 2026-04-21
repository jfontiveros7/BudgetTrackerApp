<?php
$host = getenv("BT_DB_HOST") ?: (getenv("MYSQLHOST") ?: "localhost");
$user = getenv("BT_DB_USER") ?: (getenv("MYSQLUSER") ?: "root");
$pass = getenv("BT_DB_PASSWORD") ?: (getenv("MYSQLPASSWORD") ?: "");
$db   = getenv("BT_DB_NAME") ?: (getenv("MYSQLDATABASE") ?: "budgettracker_pro");
$portRaw = getenv("BT_DB_PORT") ?: (getenv("MYSQLPORT") ?: "3306");
$port = is_numeric($portRaw) ? (int) $portRaw : 3306;

$localConfig = __DIR__ . "/database.local.php";
if (file_exists($localConfig)) {
    require $localConfig;
}

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
