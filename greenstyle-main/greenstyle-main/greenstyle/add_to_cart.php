<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$product_id = $_POST['product_id'] ?? null;
$quantity = (int)($_POST['quantity'] ?? 1);

if ($product_id && $quantity > 0) {
    // sprawdzamy, czy produkt już jest w koszyku
    $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $item = $stmt->fetch();

    if ($item) {
        // aktualizacja ilości
        $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?")
            ->execute([$quantity, $item['id']]);
    } else {
        // dodanie nowego wpisu
        $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)")
            ->execute([$user_id, $product_id, $quantity]);
    }

    header("Location: cart.php");
    exit;
} else {
    echo "Błąd dodawania do koszyka.";
}
