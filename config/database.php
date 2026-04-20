<?php
$host = getenv("BT_DB_HOST") ?: "localhost";
$user = getenv("BT_DB_USER") ?: "root";
$pass = getenv("BT_DB_PASSWORD") ?: "";
$db   = getenv("BT_DB_NAME") ?: "budgettracker_pro";

$localConfig = __DIR__ . "/database.local.php";
if (file_exists($localConfig)) {
    require $localConfig;
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
