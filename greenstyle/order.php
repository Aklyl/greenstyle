<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Pobierz dane z koszyka
$stmt = $pdo->prepare("
    SELECT cart.product_id, products.price, cart.quantity
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

if (count($items) === 0) {
    echo "<p>Twój koszyk jest pusty.</p>";
    echo "<a href='index.php'>Wróć do sklepu</a>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address']);
    $phone   = trim($_POST['phone']);
