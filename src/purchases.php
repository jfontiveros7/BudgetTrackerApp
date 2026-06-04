<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/schema_support.php';

function btNormalizePurchasePlan($plan, $default = "") {
    if (function_exists("normalizePlan")) {
        return normalizePlan($plan, $default);
    }

    $normalized = strtolower(trim((string) $plan));
    return in_array($normalized, ["starter", "growth", "scale"], true) ? $normalized : $default;
}

function btPurchaseClaimsReady() {
    if (!function_exists("btDatabaseAvailable") || !btDatabaseAvailable()) {
        return false;
    }

    return btEnsurePurchaseClaimsTable();
}

function btGeneratePurchaseClaimToken() {
    return bin2hex(random_bytes(16));
}

function btCreatePurchaseClaim($plan) {
    global $conn;

    if (!btPurchaseClaimsReady()) {
        return null;
    }

    $normalizedPlan = btNormalizePurchasePlan($plan);
    if ($normalizedPlan === "") {
        return null;
    }

    $token = btGeneratePurchaseClaimToken();
    $stmt = $conn->prepare("INSERT INTO purchase_claims (claim_token, plan) VALUES (?, ?)");
    $stmt->bind_param("ss", $token, $normalizedPlan);

    if (!$stmt->execute()) {
        return null;
    }

    return $token;
}

function btGetPurchaseClaimByToken($token) {
    global $conn;

    if (!btPurchaseClaimsReady()) {
        return null;
    }

    $token = trim((string) $token);
    if ($token === "") {
        return null;
    }

    $stmt = $conn->prepare("SELECT * FROM purchase_claims WHERE claim_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc() ?: null;
}

function btGetPurchaseClaimByStripeSessionId($sessionId) {
    global $conn;

    if (!btPurchaseClaimsReady()) {
        return null;
    }

    $sessionId = trim((string) $sessionId);
    if ($sessionId === "") {
        return null;
    }

    $stmt = $conn->prepare("SELECT * FROM purchase_claims WHERE stripe_checkout_session_id = ? LIMIT 1");
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc() ?: null;
}

function btStorePurchaseClaimStripeSession($token, $sessionId) {
    global $conn;

    if (!btPurchaseClaimsReady()) {
        return false;
    }

    $token = trim((string) $token);
    $sessionId = trim((string) $sessionId);
    if ($token === "" || $sessionId === "") {
        return false;
    }

    $stmt = $conn->prepare(
        "UPDATE purchase_claims
         SET stripe_checkout_session_id = ?
         WHERE claim_token = ?
           AND (stripe_checkout_session_id IS NULL OR stripe_checkout_session_id = '')"
    );
    $stmt->bind_param("ss", $sessionId, $token);
    return $stmt->execute();
}

function btMarkPurchaseClaimPaid($token, $plan, $sessionId = "", $customerEmail = "", $metadata = null) {
    global $conn;

    if (!btPurchaseClaimsReady()) {
        return false;
    }

    $token = trim((string) $token);
    $normalizedPlan = btNormalizePurchasePlan($plan);
    $sessionId = trim((string) $sessionId);
    $customerEmail = trim((string) $customerEmail);
    $metadataJson = $metadata !== null ? json_encode($metadata, JSON_UNESCAPED_SLASHES) : null;

    if ($token === "" || $normalizedPlan === "") {
        return false;
    }

    $claim = btGetPurchaseClaimByToken($token);
    if (!$claim) {
        return false;
    }

    $sql = "
        UPDATE purchase_claims
        SET plan = ?,
            stripe_checkout_session_id = CASE
                WHEN ? <> '' THEN ?
                ELSE stripe_checkout_session_id
            END,
            stripe_customer_email = CASE
                WHEN ? <> '' THEN ?
                ELSE stripe_customer_email
            END,
            payment_status = CASE
                WHEN payment_status = 'claimed' THEN 'claimed'
                ELSE 'paid'
            END,
            metadata_json = CASE
                WHEN ? IS NOT NULL THEN ?
                ELSE metadata_json
            END,
            paid_at = COALESCE(paid_at, NOW())
        WHERE claim_token = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssss",
        $normalizedPlan,
        $sessionId,
        $sessionId,
        $customerEmail,
        $customerEmail,
        $metadataJson,
        $metadataJson,
        $token
    );

    return $stmt->execute();
}

function btGetLatestPaidUnclaimedPurchaseByEmail($email, $plan = "") {
    global $conn;

    if (!btPurchaseClaimsReady()) {
        return null;
    }

    $email = strtolower(trim((string) $email));
    $plan = btNormalizePurchasePlan($plan, "");
    if ($email === "") {
        return null;
    }

    if ($plan !== "") {
        $stmt = $conn->prepare(
            "SELECT *
             FROM purchase_claims
             WHERE LOWER(stripe_customer_email) = ?
               AND plan = ?
               AND payment_status = 'paid'
               AND claimed_user_id IS NULL
             ORDER BY paid_at DESC, id DESC
             LIMIT 1"
        );
        $stmt->bind_param("ss", $email, $plan);
    } else {
        $stmt = $conn->prepare(
            "SELECT *
             FROM purchase_claims
             WHERE LOWER(stripe_customer_email) = ?
               AND payment_status = 'paid'
               AND claimed_user_id IS NULL
             ORDER BY paid_at DESC, id DESC
             LIMIT 1"
        );
        $stmt->bind_param("s", $email);
    }
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc() ?: null;
}

function btClaimPurchaseForUser($userId, $email, $purchaseToken = "", $planHint = "") {
    global $conn;

    if (!btPurchaseClaimsReady()) {
        return [
            "ok" => false,
            "reason" => "purchase_claims_unavailable",
        ];
    }

    $userId = (int) $userId;
    $email = strtolower(trim((string) $email));
    $purchaseToken = trim((string) $purchaseToken);
    $planHint = btNormalizePurchasePlan($planHint, "");

    $claim = null;
    if ($purchaseToken !== "") {
        $claim = btGetPurchaseClaimByToken($purchaseToken);
    }

    if (!$claim && $email !== "") {
        $claim = btGetLatestPaidUnclaimedPurchaseByEmail($email, $planHint);
    }

    if (!$claim) {
        return [
            "ok" => false,
            "reason" => "purchase_not_found",
        ];
    }

    if (($claim["payment_status"] ?? "") !== "paid" && ($claim["payment_status"] ?? "") !== "claimed") {
        return [
            "ok" => false,
            "reason" => "purchase_not_paid",
            "claim" => $claim,
        ];
    }

    $claimedUserId = (int) ($claim["claimed_user_id"] ?? 0);
    if ($claimedUserId > 0 && $claimedUserId !== $userId) {
        return [
            "ok" => false,
            "reason" => "purchase_claimed_by_other_user",
            "claim" => $claim,
        ];
    }

    $plan = btNormalizePurchasePlan($claim["plan"] ?? "", $planHint !== "" ? $planHint : "growth");
    $emailMismatch = false;
    $expectedEmail = strtolower(trim((string) ($claim["stripe_customer_email"] ?? "")));
    if ($expectedEmail !== "" && $email !== "" && $expectedEmail !== $email) {
        $emailMismatch = true;
    }

    $stmt = $conn->prepare(
        "UPDATE purchase_claims
         SET claimed_user_id = ?,
             payment_status = 'claimed',
             claimed_at = COALESCE(claimed_at, NOW())
         WHERE id = ?"
    );
    $claimId = (int) $claim["id"];
    $stmt->bind_param("ii", $userId, $claimId);

    if (!$stmt->execute()) {
        return [
            "ok" => false,
            "reason" => "purchase_claim_update_failed",
            "claim" => $claim,
        ];
    }

    return [
        "ok" => true,
        "plan" => $plan,
        "claim" => $claim,
        "email_mismatch" => $emailMismatch,
        "expected_email" => $expectedEmail,
    ];
}
?>
