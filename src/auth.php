<?php
require_once __DIR__ . '/../config/database.php';

function registerUser($name, $email, $password) {
    global $conn;

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed);
    return $stmt->execute();
}

function loginUser($email, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
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
