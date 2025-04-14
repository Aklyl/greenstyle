<?php
require 'auth.php';
require '../db.php';

$stmt = $pdo->query("
    SELECT orders.*, users.email 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    ORDER BY orders.created_at DESC
");
$orders = $stmt->fetchAll();
?>

<h2>Zamówienia</h2>
<a href="index.php">⬅️ Powrót do panelu admina</a><br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Klient</th>
        <th>Adres</th>
        <th>Telefon</th>
        <th>Data</th>
        <th>Akcje</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= $order['id'] ?></td>
        <td><?= htmlspecialchars($order['email']) ?></td>
        <td><?= nl2br(htmlspecialchars($order['address'])) ?></td>
        <td><?= $order['phone'] ?></td>
        <td><?= $order['created_at'] ?></td>
        <td><a href="order_details.php?id=<?= $order['id'] ?>">Szczegóły</a></td>
    </tr>
    <?php endforeach; ?>
</table>
