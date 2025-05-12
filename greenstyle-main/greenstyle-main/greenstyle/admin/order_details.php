<?php
require 'auth.php';
require '../db.php';

$order_id = $_GET['id'] ?? 0;

// Zamówienie
$stmt = $pdo->prepare("
    SELECT orders.*, users.email 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    WHERE orders.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "Zamówienie nie istnieje.";
    exit;
}

// Pozycje zamówienia
$stmt = $pdo->prepare("
    SELECT order_items.*, products.name 
    FROM order_items 
    JOIN products ON order_items.product_id = products.id 
    WHERE order_items.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<h2>Szczegóły zamówienia #<?= $order['id'] ?></h2>
<p><strong>Klient:</strong> <?= htmlspecialchars($order['email']) ?></p>
<p><strong>Adres:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
<p><strong>Telefon:</strong> <?= $order['phone'] ?></p>
<p><strong>Data:</strong> <?= $order['created_at'] ?></p>

<h3>Produkty:</h3>
<table border="1" cellpadding="10">
    <tr><th>Nazwa</th><th>Ilość</th><th>Cena (szt.)</th><th>Suma</th></tr>
    <?php 
    $total = 0;
    foreach ($items as $item): 
        $sum = $item['price'] * $item['quantity'];
        $total += $sum;
    ?>
    <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($item['price'], 2) ?> zł</td>
        <td><?= number_format($sum, 2) ?> zł</td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Łącznie:</strong></td>
        <td><strong><?= number_format($total, 2) ?> zł</strong></td>
    </tr>
</table>

<br>
<a href="orders.php">⬅️ Powrót do zamówień</a>
