<?php
require_once __DIR__ . "/../config/resend.php";

function btJsonResponse(array $payload, int $status = 200) {
    http_response_code($status);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($payload);
    exit;
}

function btReadJsonInput() {
    $payload = json_decode(file_get_contents("php://input") ?: "{}", true);
    if (is_array($payload)) {
        return $payload;
    }

    return $_POST;
}

function btNormalizeLeadPayload(array $payload) {
    return [
        "name" => trim((string) ($payload["name"] ?? "")),
        "email" => trim((string) ($payload["email"] ?? "")),
        "company_size" => trim((string) ($payload["company_size"] ?? "")),
        "plan_interest" => trim((string) ($payload["plan_interest"] ?? "")),
        "message" => trim((string) ($payload["message"] ?? "")),
    ];
}

function btValidateLeadPayload(array $lead) {
    if ($lead["name"] === "" || $lead["email"] === "" || $lead["message"] === "") {
        return "Missing required fields.";
    }

    if (!filter_var($lead["email"], FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address.";
    }

    return "";
}

function btPostJson(string $url, array $payload, array $headers = []) {
    $context = stream_context_create([
        "http" => [
            "method" => "POST",
            "header" => implode("\r\n", array_merge(["Content-Type: application/json"], $headers)),
            "content" => json_encode($payload),
            "ignore_errors" => true,
            "timeout" => 15,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);

    return [
        "status" => btExtractHttpStatusCode($http_response_header ?? []),
        "body" => $body ?: "",
        "error" => $body === false ? "Request failed." : "",
    ];
}

function btSendLeadEmail(array $lead) {
    global $resendApiKey, $budgetContactToEmail, $budgetContactFromEmail;

    if (!btResendReady()) {
        return [
            "ok" => false,
            "error" => "Email delivery is not configured yet.",
            "status" => 503,
        ];
    }

    $payload = [
        "from" => $budgetContactFromEmail,
        "to" => [$budgetContactToEmail],
        "reply_to" => $lead["email"],
        "subject" => "Budget Tracker inquiry from " . $lead["name"],
        "html" => btRenderLeadEmailHtml($lead),
        "text" => btRenderLeadEmailText($lead),
    ];

    $response = btPostJson(
        "https://api.resend.com/emails",
        $payload,
        ["Authorization: Bearer " . $resendApiKey]
    );

    if ($response["status"] >= 400 || $response["error"] !== "") {
        error_log("Resend contact send failed: " . json_encode($response));

        return [
            "ok" => false,
            "error" => "Failed to send via Resend.",
            "status" => 502,
        ];
    }

    return [
        "ok" => true,
        "status" => 200,
        "resend" => json_decode($response["body"], true),
    ];
}

function btRenderLeadEmailHtml(array $lead) {
    $companySize = $lead["company_size"] !== "" ? $lead["company_size"] : "Not provided";
    $planInterest = $lead["plan_interest"] !== "" ? $lead["plan_interest"] : "Not provided";

    return sprintf(
        '<h2>New Budget Tracker inquiry</h2><p><strong>Name:</strong> %s</p><p><strong>Email:</strong> %s</p><p><strong>Company size:</strong> %s</p><p><strong>Plan interest:</strong> %s</p><p><strong>Message:</strong></p><p>%s</p>',
        htmlspecialchars($lead["name"], ENT_QUOTES, "UTF-8"),
        htmlspecialchars($lead["email"], ENT_QUOTES, "UTF-8"),
        htmlspecialchars($companySize, ENT_QUOTES, "UTF-8"),
        htmlspecialchars($planInterest, ENT_QUOTES, "UTF-8"),
        nl2br(htmlspecialchars($lead["message"], ENT_QUOTES, "UTF-8"))
    );
}

function btRenderLeadEmailText(array $lead) {
    $companySize = $lead["company_size"] !== "" ? $lead["company_size"] : "Not provided";
    $planInterest = $lead["plan_interest"] !== "" ? $lead["plan_interest"] : "Not provided";

    return implode("\n", [
        "New Budget Tracker inquiry",
        "",
        "Name: " . $lead["name"],
        "Email: " . $lead["email"],
        "Company size: " . $companySize,
        "Plan interest: " . $planInterest,
        "",
        "Message:",
        $lead["message"],
    ]);
}

function btVerifyResendSignature(string $payload, array $headers, string $secret) {
    $messageId = $headers["svix-id"] ?? "";
    $timestamp = $headers["svix-timestamp"] ?? "";
    $signatureHeader = $headers["svix-signature"] ?? "";

    if ($messageId === "" || $timestamp === "" || $signatureHeader === "" || $secret === "") {
        return false;
    }

    $signedContent = $messageId . "." . $timestamp . "." . $payload;
    $expected = base64_encode(hash_hmac("sha256", $signedContent, $secret, true));

    foreach (explode(" ", $signatureHeader) as $part) {
        if (strpos($part, "v1,") !== 0) {
            continue;
        }

        $candidate = substr($part, 3);
        if (hash_equals($expected, $candidate)) {
            return true;
        }
    }

    return false;
}

function btHandleResendWebhook(string $rawPayload, array $headers) {
    global $resendWebhookSigningSecret;

    if (!btResendWebhookReady()) {
        return [
            "ok" => false,
            "error" => "Webhook signing secret is not configured.",
            "status" => 503,
        ];
    }

    if (!btVerifyResendSignature($rawPayload, $headers, $resendWebhookSigningSecret)) {
        return [
            "ok" => false,
            "error" => "Invalid webhook signature.",
            "status" => 400,
        ];
    }

    $event = json_decode($rawPayload, true) ?: [];
    $type = $event["type"] ?? "unknown";
    error_log("Resend webhook event: " . $type . " " . $rawPayload);

    return [
        "ok" => true,
        "status" => 200,
    ];
}

function btExtractHttpStatusCode(array $headers) {
    foreach ($headers as $header) {
        if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $matches)) {
            return (int) $matches[1];
        }
    }

    return 0;
}
?>
