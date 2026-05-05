<?php
require_once __DIR__ . '/analytics.php';
require_once __DIR__ . '/agent.php';
require_once __DIR__ . '/transactions.php';

function loadBudgetCoachEnvFile($path) {
    if (!is_string($path) || $path === '' || !is_file($path) || !is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '#')) {
            continue;
        }

        $parts = explode('=', $trimmed, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $name = trim($parts[0]);
        $value = trim($parts[1]);
        if ($name === '') {
            continue;
        }

        if (
            (getenv($name) !== false && trim((string) getenv($name)) !== '') ||
            (isset($_ENV[$name]) && trim((string) $_ENV[$name]) !== '') ||
            (isset($_SERVER[$name]) && trim((string) $_SERVER[$name]) !== '')
        ) {
            continue;
        }

        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        putenv($name . '=' . $value);
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

function isInvalidLoopbackProxyValue($value) {
    if (!is_string($value) || trim($value) === '') {
        return false;
    }

    $parsed = parse_url(trim($value));
    if (!is_array($parsed)) {
        return false;
    }

    $host = strtolower((string) ($parsed['host'] ?? ''));
    $port = (int) ($parsed['port'] ?? 0);

    return in_array($host, ['127.0.0.1', 'localhost'], true) && $port === 9;
}

function clearInvalidOpenAiProxyEnv() {
    foreach (['HTTP_PROXY', 'HTTPS_PROXY', 'ALL_PROXY', 'http_proxy', 'https_proxy', 'all_proxy'] as $name) {
        $value = getenv($name);
        if ($value !== false && isInvalidLoopbackProxyValue((string) $value)) {
            putenv($name);
            unset($_ENV[$name], $_SERVER[$name]);
        }
    }
}

function getOpenAiApiKey() {
    loadBudgetCoachEnvFile(dirname(__DIR__) . '/agent_sdk/.env');
    loadBudgetCoachEnvFile(dirname(__DIR__) . '/.env');
    clearInvalidOpenAiProxyEnv();

    $candidates = [
        getenv("OPENAI_API_KEY"),
        $_ENV["OPENAI_API_KEY"] ?? null,
        $_SERVER["OPENAI_API_KEY"] ?? null,
    ];

    foreach ($candidates as $candidate) {
        if (is_string($candidate) && trim($candidate) !== "") {
            return trim($candidate);
        }
    }

    return null;
}

function buildBudgetCoachContext($userId) {
    $summary = getUserSummary($userId);
    $chartData = getUserChartData($userId);
    $recentTransactions = array_slice(getRecentTransactions($userId, 12), 0, 12);
    $agentReport = getBudgetAgentReport($userId);

    return [
        "summary" => $summary,
        "chart_data" => $chartData,
        "recent_transactions" => $recentTransactions,
        "agent_report" => $agentReport,
    ];
}

function getBudgetCoachFallbackReply($userId, $message) {
    $context = buildBudgetCoachContext($userId);
    $summary = $context["summary"];
    $agentReport = $context["agent_report"];
    $question = strtolower(trim($message));

    if ($question === "") {
        return "Ask me about your spending, savings, income trends, or where your money is going.";
    }

    if (strpos($question, "save") !== false || strpos($question, "saving") !== false) {
        if ($summary["net"] > 0) {
            return "You are currently net positive by $" . number_format($summary["net"], 2) . ". A strong next move is to automatically set aside part of that surplus right after income hits.";
        }

        return "Your current net balance is negative by $" . number_format(abs($summary["net"]), 2) . ". Focus on reducing one high-spend category first before setting an aggressive savings target.";
    }

    if (strpos($question, "expense") !== false || strpos($question, "spend") !== false) {
        return $agentReport["insights"][0] ?? "Your recent spending data is limited right now. Add a few more transactions so I can spot stronger trends.";
    }

    if (strpos($question, "income") !== false) {
        return "Your total recorded income is $" . number_format($summary["total_income"], 2) . ". Compare that against your expenses of $" . number_format($summary["total_expense"], 2) . " to understand your current cash flow.";
    }

    $parts = [];
    $parts[] = $agentReport["headline"];
    if (!empty($agentReport["insights"][0])) {
        $parts[] = $agentReport["insights"][0];
    }
    if (!empty($agentReport["actions"][0])) {
        $parts[] = "Suggested next step: " . $agentReport["actions"][0];
    }

    return implode(" ", $parts);
}

function askBudgetCoach($userId, $message) {
    $message = trim($message);
    if ($message === "") {
        return [
            "reply" => "Ask me about your budget, recent spending, savings, or what to improve next.",
            "mode" => "fallback",
        ];
    }

    $apiKey = getOpenAiApiKey();
    if (!$apiKey) {
        return [
            "reply" => getBudgetCoachFallbackReply($userId, $message),
            "mode" => "fallback",
        ];
    }

    $context = buildBudgetCoachContext($userId);
    $payload = [
        "model" => "gpt-5",
        "instructions" => "You are Budget Coach, a concise personal finance assistant inside a budgeting app. Use only the provided user finance data. Give practical, specific advice in 3 short paragraphs or less. Do not claim access to data you were not given. Avoid generic disclaimers unless needed.",
        "input" => [
            [
                "role" => "user",
                "content" => [
                    [
                        "type" => "input_text",
                        "text" => "User finance context:\n" . json_encode($context, JSON_PRETTY_PRINT),
                    ],
                    [
                        "type" => "input_text",
                        "text" => "User question: " . $message,
                    ],
                ],
            ],
        ],
    ];

    $ch = curl_init("https://api.openai.com/v1/responses");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_PROXY => '',
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . $apiKey,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 30,
    ]);

    $rawResponse = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($rawResponse === false || $curlError) {
        return [
            "reply" => getBudgetCoachFallbackReply($userId, $message),
            "mode" => "fallback",
            "error" => "OpenAI request failed.",
        ];
    }

    $decoded = json_decode($rawResponse, true);
    if ($httpCode >= 400 || !is_array($decoded)) {
        return [
            "reply" => getBudgetCoachFallbackReply($userId, $message),
            "mode" => "fallback",
            "error" => "OpenAI response was unavailable.",
        ];
    }

    $reply = trim($decoded["output_text"] ?? "");
    if ($reply === "") {
        $reply = getBudgetCoachFallbackReply($userId, $message);
        return [
            "reply" => $reply,
            "mode" => "fallback",
        ];
    }

    return [
        "reply" => $reply,
        "mode" => "openai",
    ];
}
