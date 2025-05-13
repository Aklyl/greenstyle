<?php

require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Pobierz zamówienia użytkownika
$stmt = $pdo->prepare("
    SELECT o.id, o.created_at, o.status, SUM(oi.quantity * oi.price) AS total
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id, o.created_at, o.status
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Moje zamówienia – GreenStyle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .orders-container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px #0002; padding: 32px 24px; }
        .orders-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .orders-table th, .orders-table td { padding: 14px 10px; text-align: center; border-bottom: 1px solid #e0f2f1; }
        .orders-table th { background: #388e3c; color: #fff; font-weight: 600; }
        .orders-table tr:nth-child(even) { background: #e8f5e9; }
        .orders-table tr:hover { background: #c8e6c9; }
        .orders-table a { color: #2e7d32; text-decoration: none; }
        .orders-table a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>GreenStyle 🌿</h1>
            <nav>
                <a href="index.php">🏠 Strona główna</a>
                <a href="about.php">🌱 O nas</a>
                <a href="cart.php">🛒 Koszyk</a>
                <a href="orders_history.php">📦 Moje zamówienia</a>
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
    <div class="orders-container">
        <h2>📦 Moje zamówienia</h2>
        <?php if (count($orders) > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>ID zamówienia</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Kwota</th>
                        <th>Szczegóły</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['created_at'] ?></td>
                        <td><?= htmlspecialchars($order['status'] ?? 'Nowe') ?></td>
                        <td><?= number_format($order['total'], 2) ?> zł</td>
                        <td>
                            <a href="order_details.php?id=<?= $order['id'] ?>">Podgląd</a>
                            <?php if (($order['status'] ?? 'Nowe') === 'Nowe'): ?>
                                | <a href="cancel_order.php?id=<?= $order['id'] ?>" onclick="return confirm('Czy na pewno anulować zamówienie?');" style="color:#c62828;">Anuluj</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nie masz jeszcze żadnych zamówień.</p>
        <?php endif; ?>
    </div>
</body>
</html>