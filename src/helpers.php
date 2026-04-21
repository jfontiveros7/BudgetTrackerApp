<?php

function bt_env(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    if ($value === false || $value === "") {
        return $default;
    }

    return $value;
}

function bt_base_path(string $path = ""): string
{
    $base = dirname(__DIR__);
    return $path === "" ? $base : $base . DIRECTORY_SEPARATOR . ltrim($path, "\\/");
}
