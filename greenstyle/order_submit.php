<?php
session_start();
require 'db.php';
require 'send_mail.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';

if (!$address || !$phone) {
    die('Brak danych adresowych.');
}

// Pobierz produkty z koszyka
$stmt = $pdo->prepare("SELECT cart.*, products.name, products.price 
                       FROM cart 
                       JOIN products ON cart.product_id = products.id 
                       WHERE cart.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (!$cart_items) {
    die('Koszyk jest pusty.');
}

// Utwórz zamówienie
$stmt = $pdo->prepare("INSERT INTO orders (user_id, address, phone, created_at) VALUES (?, ?, ?, NOW())");
$stmt->execute([$user_id, $address, $phone]);
$order_id = $pdo->lastInsertId();

// Dodaj pozycje zamówienia
$stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

foreach ($cart_items as $item) {
    $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
}

// Wyczyść koszyk
$pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

// Wyślij potwierdzenie mailowe
sendConfirmationEmail($_SESSION['user']['email'], $order_id);

echo "<h2>Dziękujemy za złożenie zamówienia!</h2>";
echo "<p>Twoje zamówienie #{$order_id} zostało zapisane.</p>";
echo "<a href='index.php'>← Powrót do sklepu</a>";
