<?php
$stripeSecretKey = getenv("BT_STRIPE_SECRET_KEY") ?: "";
$starterStripePriceId = getenv("BT_STRIPE_STARTER_PRICE_ID") ?: "";
$growthStripePriceId = getenv("BT_STRIPE_GROWTH_PRICE_ID") ?: "";
$scaleStripePriceId = getenv("BT_STRIPE_SCALE_PRICE_ID") ?: "";
$appBaseUrl = getenv("BT_APP_URL") ?: "";

$localConfig = __DIR__ . "/stripe.local.php";
if (file_exists($localConfig)) {
    require $localConfig;
}

$stripeSecretKey = is_string($stripeSecretKey) ? trim($stripeSecretKey) : "";
$appBaseUrl = is_string($appBaseUrl) ? rtrim(trim($appBaseUrl), "/") : "";

$stripePriceIds = [
    "starter" => is_string($starterStripePriceId) ? trim($starterStripePriceId) : "",
    "growth" => is_string($growthStripePriceId) ? trim($growthStripePriceId) : "",
    "scale" => is_string($scaleStripePriceId) ? trim($scaleStripePriceId) : "",
];

function btResolveAppBaseUrl() {
    global $appBaseUrl;

    if ($appBaseUrl !== "") {
        return $appBaseUrl;
    }

    $https = $_SERVER["HTTPS"] ?? "";
    $scheme = (!empty($https) && $https !== "off") ? "https" : "http";
    $host = $_SERVER["HTTP_HOST"] ?? "127.0.0.1:8000";

    return $scheme . "://" . $host;
}

function btStripeCheckoutReadyForPlan($plan) {
    global $stripeSecretKey, $stripePriceIds;

    $autoloadPath = dirname(__DIR__) . "/vendor/autoload.php";
    $priceId = $stripePriceIds[$plan] ?? "";
    $hasPlaceholderSecret = str_contains($stripeSecretKey, "your_secret_key");
    $hasPlaceholderPrice = str_contains($priceId, "your_") || str_contains($priceId, "price_your_");

    return $stripeSecretKey !== ""
        && $priceId !== ""
        && !$hasPlaceholderSecret
        && !$hasPlaceholderPrice
        && file_exists($autoloadPath);
}
