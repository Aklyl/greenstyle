<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$product_id = $_POST['product_id'] ?? null;
$size = $_POST['size'] ?? null;

if (!$product_id || !$size) {
    header('Location: index.php');
    exit;
}

// Sprawdź, czy już jest taki produkt z tym rozmiarem w koszyku
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
$stmt->execute([$user_id, $product_id, $size]);
$item = $stmt->fetch();

if ($item) {
    // Zwiększ ilość
    $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
    $stmt->execute([$item['id']]);
} else {
    // Dodaj nowy produkt z rozmiarem
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity, size) VALUES (?, ?, 1, ?)");
    $stmt->execute([$user_id, $product_id, $size]);
}

header('Location: cart.php');
exit;
