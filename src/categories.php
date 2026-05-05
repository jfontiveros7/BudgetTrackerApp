<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/schema_support.php';

function createCategory($name, $type = 'expense', $icon = '', $color = '') {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO categories (name, type, icon) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $type, $icon);
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    return false;
}

function updateCategory($id, $name, $type = 'expense', $icon = '', $color = '') {
    global $conn;
    $stmt = $conn->prepare("UPDATE categories SET name=?, type=?, icon=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $type, $icon, $id);
    return $stmt->execute();
}

function deleteCategory($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM categories WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function getCategories($type = null) {
    global $conn;

    if (!btTableExists("categories")) {
        return [];
    }

    if ($type !== null) {
        $stmt = $conn->prepare("SELECT id, name, type, icon FROM categories WHERE type = ? ORDER BY name ASC");
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query("SELECT id, name, type, icon FROM categories ORDER BY type ASC, name ASC");
    }

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}

function getOrCreateCategoryId($name, $type = "expense") {
    global $conn;

    if (!btTableExists("categories")) {
        return null;
    }

    $normalizedName = trim($name);
    if ($normalizedName === "") {
        return null;
    }

    $stmt = $conn->prepare("SELECT id FROM categories WHERE LOWER(name) = LOWER(?) LIMIT 1");
    $stmt->bind_param("s", $normalizedName);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        return (int) $result["id"];
    }

    $stmt = $conn->prepare("INSERT INTO categories (name, type) VALUES (?, ?)");
    $stmt->bind_param("ss", $normalizedName, $type);
    if ($stmt->execute()) {
        return (int) $stmt->insert_id;
    }

    return null;
}
