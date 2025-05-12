<?php
session_start();
require '../db.php';

// Sprawdź czy użytkownik to admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('Brak dostępu');
}

// Pobierz wszystkie zamówienia
$stmt = $pdo->query("
    SELECT o.id AS order_id, o.created_at, o.address, o.phone, u.name AS user_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zamówienia – Panel administratora</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .order-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #eee;
        }
        h1 {
            color: #333;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>

<h1>📦 Zamówienia</h1>
<p><a href="index.php">← Wróć do panelu administratora</a></p>

<?php foreach ($orders as $order): ?>
    <div class="order-box">
        <h3>Zamówienie #<?= $order['order_id'] ?> (<?= $order['created_at'] ?>)</h3>
        <p><strong>Klient:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
        <p><strong>Adres:</strong> <?= htmlspecialchars($order['address']) ?> |
           <strong>Telefon:</strong> <?= htmlspecialchars($order['phone']) ?></p>

        <table>
            <tr>
                <th>Produkt</th>
                <th>Ilość</th>
                <th>Cena jedn. (zł)</th>
                <th>Razem (zł)</th>
            </tr>
            <?php
            $stmtItems = $pdo->prepare("
                SELECT p.name, oi.quantity, oi.price
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?
            ");
            $stmtItems->execute([$order['order_id']]);
            $items = $stmtItems->fetchAll();
            $total = 0;
            foreach ($items as $item):
                $lineTotal = $item['quantity'] * $item['price'];
                $total += $lineTotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                    <td><?= number_format($lineTotal, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Suma:</strong></td>
                <td><strong><?= number_format($total, 2) ?> zł</strong></td>
            </tr>
        </table>
    </div>
<?php endforeach; ?>

</body>
</html>
