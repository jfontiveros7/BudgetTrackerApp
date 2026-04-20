<?php
require_once __DIR__ . '/analytics.php';
require_once __DIR__ . '/agent.php';
require_once __DIR__ . '/transactions.php';

function getOpenAiApiKey() {
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
    curl_close($ch);

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
