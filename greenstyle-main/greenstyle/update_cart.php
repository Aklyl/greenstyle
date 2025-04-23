<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$cart_id = $_POST['cart_id'] ?? null;
$quantity = (int)($_POST['quantity'] ?? 1);

if ($cart_id && $quantity > 0) {
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $cart_id]);
}

header("Location: cart.php");
