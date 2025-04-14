<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    die('Brak dostępu');
}

// Pobierz wszystkie zamówienia z użytkownikami
$stmt = $pdo->query("
    SELECT o.*, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zamówienia - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Zamówienia klientów</h1>
    <a href="index.php">← Wróć do panelu</a><br><br>

    <?php foreach ($orders as $order): ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
            <strong>Zamówienie #<?= $order['id'] ?></strong><br>
            Klient: <?= htmlspecialchars($order['email']) ?><br>
            Data: <?= $order['created_at'] ?><br>
            Adres: <?= htmlspecialchars($order['address']) ?><br>
            Kwota: <?= number_format($order['total'], 2) ?> zł<br><br>

            <u>Produkty:</u><br>
            <ul>
            <?php
                $stmtItems = $pdo->prepare("
                    SELECT oi.quantity, p.name 
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = ?
                ");
                $stmtItems->execute([$order['id']]);
                $items = $stmtItems->fetchAll();

                foreach ($items as $item):
            ?>
                <li><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</body>
</html>
