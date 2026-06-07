<?php
$path = parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);
$publicPath = __DIR__ . "/public" . $path;

if ($path !== "/" && file_exists($publicPath) && !is_dir($publicPath)) {
    return false;
}

$routeMap = [
    "/api/contact" => __DIR__ . "/public/api/contact.php",
    "/api/resend/webhook" => __DIR__ . "/public/api/resend/webhook.php",
];

if (isset($routeMap[$path])) {
    require $routeMap[$path];
    return true;
}

if ($path === "/" || $path === "") {
    require __DIR__ . "/public/index.php";
    return true;
}

$phpFallback = __DIR__ . "/public" . $path . ".php";
if (file_exists($phpFallback) && !is_dir($phpFallback)) {
    require $phpFallback;
    return true;
}

return false;
?>
