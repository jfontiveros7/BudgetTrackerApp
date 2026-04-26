<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/schema_support.php';

function normalizePlan($plan, $default = "growth") {
    $normalized = strtolower(trim((string) $plan));
    return in_array($normalized, ["starter", "growth", "scale"], true) ? $normalized : $default;
}

function completeLogin(array $user) {
    $_SESSION["user_id"] = (int) $user["id"];
    $_SESSION["user_name"] = $user["name"];
    $_SESSION["user_email"] = $user["email"];
    $_SESSION["user_role"] = $user["role"] ?? 'user';
    $_SESSION["selected_plan"] = normalizePlan($user["selected_plan"] ?? "growth");
    $_SESSION["active_plan"] = $_SESSION["selected_plan"];
}

function repairLegacyDemoPassword(array $user, $password) {
    global $conn;

    if (($user["email"] ?? "") !== "demo@konticodelabs.com" || $password !== "demo1234") {
        return false;
    }

    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $userId = (int) $user["id"];
    $stmt->bind_param("si", $newHash, $userId);

    if (!$stmt->execute()) {
        return false;
    }

    $user["password"] = $newHash;
    completeLogin($user);
    return $user;
}

function registerUser($name, $email, $password, $selectedPlan = "growth") {
    global $conn;

    btEnsureUsersPlanColumn();

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';
    $selectedPlan = normalizePlan($selectedPlan);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, selected_plan) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashed, $role, $selectedPlan);
    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function loginUser($email, $password) {
    global $conn;

    btEnsureUsersPlanColumn();

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        completeLogin($user);
        return $user;
    }

    if ($user) {
        $repairedUser = repairLegacyDemoPassword($user, $password);
        if ($repairedUser) {
            return $repairedUser;
        }
    }

    return false;
}

function updateUserPlan($userId, $plan) {
    global $conn;

    btEnsureUsersPlanColumn();

    $normalizedPlan = normalizePlan($plan);
    $stmt = $conn->prepare("UPDATE users SET selected_plan = ? WHERE id = ?");
    $stmt->bind_param("si", $normalizedPlan, $userId);
    $updated = $stmt->execute();

    if ($updated) {
        $_SESSION["selected_plan"] = $normalizedPlan;
        $_SESSION["active_plan"] = $normalizedPlan;
    }

    return $updated;
}

function createPasswordResetToken($email) {
    global $conn;

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        return null;
    }

    $userId = (int) $user["id"];
    $token = bin2hex(random_bytes(32));
    $tokenHash = hash("sha256", $token);
    $expiresAt = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ? OR expires_at < NOW() OR used_at IS NOT NULL");
    $deleteStmt->bind_param("i", $userId);
    $deleteStmt->execute();

    $insertStmt = $conn->prepare("INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
    $insertStmt->bind_param("iss", $userId, $tokenHash, $expiresAt);

    if (!$insertStmt->execute()) {
        return null;
    }

    return $token;
}

function getPasswordResetByToken($token) {
    global $conn;

    $tokenHash = hash("sha256", $token);
    $stmt = $conn->prepare(
        "SELECT pr.id, pr.user_id, pr.expires_at, pr.used_at, u.email
         FROM password_resets pr
         INNER JOIN users u ON u.id = pr.user_id
         WHERE pr.token_hash = ?
         LIMIT 1"
    );
    $stmt->bind_param("s", $tokenHash);
    $stmt->execute();

    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();
    if (!$reset) {
        return null;
    }

    if (!empty($reset["used_at"])) {
        return null;
    }

    if (strtotime($reset["expires_at"]) <= time()) {
        return null;
    }

    return $reset;
}

function resetPasswordWithToken($token, $newPassword) {
    global $conn;

    $reset = getPasswordResetByToken($token);
    if (!$reset) {
        return false;
    }

    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $userId = (int) $reset["user_id"];
    $tokenHash = hash("sha256", $token);

    $conn->begin_transaction();
    try {
        $updateUserStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateUserStmt->bind_param("si", $passwordHash, $userId);
        if (!$updateUserStmt->execute()) {
            throw new Exception("Failed to update password");
        }

        $consumeTokenStmt = $conn->prepare("UPDATE password_resets SET used_at = NOW() WHERE token_hash = ?");
        $consumeTokenStmt->bind_param("s", $tokenHash);
        if (!$consumeTokenStmt->execute()) {
            throw new Exception("Failed to consume token");
        }

        $cleanupStmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ? AND token_hash <> ?");
        $cleanupStmt->bind_param("is", $userId, $tokenHash);
        $cleanupStmt->execute();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}
?>
