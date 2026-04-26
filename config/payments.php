<?php
$starterPaymentLink = getenv("BT_STRIPE_STARTER_LINK") ?: "";
$growthPaymentLink = getenv("BT_STRIPE_GROWTH_LINK") ?: "";
$scalePaymentLink = getenv("BT_STRIPE_SCALE_LINK") ?: "";

$localConfig = __DIR__ . "/payments.local.php";
if (file_exists($localConfig)) {
    require $localConfig;
}

$paymentLinks = [
    "starter" => is_string($starterPaymentLink) ? trim($starterPaymentLink) : "",
    "growth" => is_string($growthPaymentLink) ? trim($growthPaymentLink) : "",
    "scale" => is_string($scalePaymentLink) ? trim($scalePaymentLink) : "",
];
