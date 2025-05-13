<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$order_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user']['id'];
$is_admin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';

if (!$order_id) die('Brak ID zamówienia.');

// Sprawdź, czy zamówienie należy do użytkownika LUB użytkownik jest adminem
if ($is_admin) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user_id]);
}
$order = $stmt->fetch();

if (!$order) die('Brak dostępu do zamówienia.');

// Pobierz produkty z zamówienia
$stmt = $pdo->prepare("
    SELECT oi.quantity, oi.price, oi.size, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT o.id, o.created_at, o.status, SUM(oi.quantity * oi.price) AS total
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id, o.created_at, o.status
    ORDER BY o.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Szczegóły zamówienia – GreenStyle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .details-container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px #0002; padding: 32px 24px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .details-table th, .details-table td { padding: 10px 8px; text-align: center; border-bottom: 1px solid #e0f2f1; }
        .details-table th { background: #388e3c; color: #fff; font-weight: 600; }
        .details-table tr:nth-child(even) { background: #e8f5e9; }
        .details-table tr:hover { background: #c8e6c9; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>GreenStyle 🌿</h1>
            <nav>
                <a href="index.php">🏠 Strona główna</a>
                <a href="orders_history.php">📦 Moje zamówienia</a>
                <a href="cart.php">🛒 Koszyk</a>
                <a href="about.php">🌱 O nas</a>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <a href="admin/index.php">⚙️ Panel admina</a>
                    <?php endif; ?>
                    <span>Witaj, <?= htmlspecialchars($_SESSION['user']['name']) ?> |</span>
                    <a href="logout.php">🚪 Wyloguj</a>
                <?php else: ?>
                    <a href="login.php">🔐 Zaloguj</a>
                    <a href="register.php">📝 Rejestracja</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="details-container">
        <h2>Szczegóły zamówienia #<?= $order['id'] ?></h2>
        <p>
            <strong>Data:</strong> <?= $order['created_at'] ?><br>
            <strong>Status:</strong> <?= htmlspecialchars($order['status'] ?? 'Nowe') ?><br>
            <strong>Rozmiar:</strong> <?= isset($order['size']) ? htmlspecialchars($order['size']) : '-' ?>
        </p>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Ilość</th>
                    <th>Cena</th>
                    <th>Rozmiar</th>
                    <th>Suma</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; foreach ($items as $item): $sum = $item['quantity'] * $item['price']; $total += $sum; ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 2) ?> zł</td>
                    <td><?= htmlspecialchars($item['size']) ?></td>
                    <td><?= number_format($sum, 2) ?> zł</td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Łącznie:</strong></td>
                    <td><strong><?= number_format($total, 2) ?> zł</strong></td>
                </tr>
            </tbody>
        </table>
        <a href="orders_history.php" style="color:#388e3c;">← Powrót do historii zamówień</a>
    </div>
</body>
</html>