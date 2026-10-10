<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $csrf = $_POST["csrf"] ?? "";
    
    // Validasi CSRF Token
    if (!hash_equals($_SESSION["csrf"] ?? "", $csrf)) {
        http_response_code(403);
        exit("Token CSRF tidak valid.");
    }
    
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(["id" => $id]);
        header("Location: index.php?status=deleted");
        exit;
    }
}

header("Location: index.php?status=error");
exit;
