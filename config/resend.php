<?php
function btLoadDotEnvFile($path) {
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === "" || strpos($trimmed, "#") === 0) {
            continue;
        }

        $parts = explode("=", $trimmed, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = trim($parts[0]);
        $value = trim($parts[1]);
        if ($key === "" || getenv($key) !== false) {
            continue;
        }

        putenv($key . "=" . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

btLoadDotEnvFile(dirname(__DIR__) . "/.env.local");

$resendApiKey = getenv("RESEND_API_KEY") ?: "";
$resendWebhookSigningSecret = getenv("RESEND_WEBHOOK_SIGNING_SECRET") ?: "";
$budgetContactToEmail = getenv("CONTACT_TO_EMAIL") ?: (getenv("BUDGET_CONTACT_TO_EMAIL") ?: "hello@konticode.com");
$budgetContactFromEmail = getenv("CONTACT_FROM_EMAIL") ?: (getenv("BUDGET_CONTACT_FROM_EMAIL") ?: "Budget Tracker <contact@budget.konticode.com>");

$localConfig = __DIR__ . "/resend.local.php";
if (file_exists($localConfig)) {
    require $localConfig;
}

$resendApiKey = is_string($resendApiKey) ? trim($resendApiKey) : "";
$resendWebhookSigningSecret = is_string($resendWebhookSigningSecret) ? trim($resendWebhookSigningSecret) : "";
$budgetContactToEmail = is_string($budgetContactToEmail) ? trim($budgetContactToEmail) : "hello@konticode.com";
$budgetContactFromEmail = is_string($budgetContactFromEmail) ? trim($budgetContactFromEmail) : "Budget Tracker <contact@budget.konticode.com>";

function btResendReady() {
    global $resendApiKey, $budgetContactToEmail, $budgetContactFromEmail;

    return $resendApiKey !== ""
        && $budgetContactToEmail !== ""
        && $budgetContactFromEmail !== "";
}

function btResendWebhookReady() {
    global $resendWebhookSigningSecret;

    return $resendWebhookSigningSecret !== "";
}
?>
