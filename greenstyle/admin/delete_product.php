<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('Brak dostępu');
}

$id = $_GET['id'] ?? null;
if (!$id) die('Nieprawidłowe ID produktu.');

// Opcjonalnie: pobierz dane produktu, żeby usunąć zdjęcie
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

// Usuń produkt z bazy
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

// Usuń też zdjęcie z serwera
if ($product && $product['image'] && file_exists('../' . $product['image'])) {
    unlink('../' . $product['image']);
}

header("Location: index.php");
exit;
