<?php

require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$order_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user']['id'];

if (!$order_id) {
    die('Brak ID zamówienia.');
}

// Sprawdź, czy zamówienie należy do użytkownika i ma status "Nowe"
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? AND status = 'Nowe'");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    die('Nie można anulować tego zamówienia.');
}

// Zmień status na "Anulowane"
$stmt = $pdo->prepare("UPDATE orders SET status = 'Anulowane' WHERE id = ?");
$stmt->execute([$order_id]);

header("Location: orders_history.php");
exit;