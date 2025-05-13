<?php
session_start();
require '../db.php';

// Sprawdź czy użytkownik to admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('Brak dostępu');
}

// Zmień status zamówienia na wybrany
if (isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['new_status'];
    $allowed = ['Nowe', 'W przygotowaniu', 'Wysłane', 'W drodze', 'Dostarczone', 'Skompletowane'];
    if (in_array($new_status, $allowed)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $order_id]);
    }
}

// Pobierz wszystkie zamówienia (bez WHERE user_id = ...)
$stmt = $pdo->query("
    SELECT o.id, o.user_id, o.created_at, o.status, o.size, SUM(oi.quantity * oi.price) AS total, u.name AS user_name
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN users u ON o.user_id = u.id
    GROUP BY o.id, o.created_at, o.status, o.size, u.name
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
        .orders-admin-container { max-width: 1000px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px #0002; padding: 32px 24px; }
        .orders-admin-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .orders-admin-table th, .orders-admin-table td { padding: 14px 10px; text-align: center; border-bottom: 1px solid #e0f2f1; }
        .orders-admin-table th { background: #388e3c; color: #fff; font-weight: 600; }
        .orders-admin-table tr:nth-child(even) { background: #e8f5e9; }
        .orders-admin-table tr:hover { background: #c8e6c9; }
        .orders-admin-table form { display: inline; }
        .orders-admin-table select {
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid #e0f2f1;
            background: #f6fff7;
            font-size: 1em;
            margin-right: 6px;
        }
        .orders-admin-table button { background: #43a047; color: #fff; border: none; border-radius: 20px; padding: 7px 18px; font-size: 1em; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .orders-admin-table button:hover { background: #2e7d32; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>👨‍💼 Panel administratora</h1>
            <nav>
                <a href="index.php">← Wróć do panelu</a>
                <a href="add_product.php">➕ Dodaj nowy produkt</a>
                <a href="orders.php">🧾 Zamówienia</a>
                <a href="../index.php">🏠 Strona sklepu</a>
                <a href="../logout.php">🚪 Wyloguj</a>
            </nav>
        </div>
    </header>
    <div class="orders-admin-container">
        <h2>🧾 Zamówienia klientów</h2>
        <table class="orders-admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Klient</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Kwota</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['user_name']) ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><?= number_format($order['total'], 2) ?> zł</td>
                    <td>
                        <?php if ($order['status'] === 'Anulowane'): ?>
                            <span style="color:#c62828;">Anulowane</span>
                        <?php else: ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="new_status">
                                    <?php
                                    $statuses = ['Nowe', 'W przygotowaniu', 'Wysłane', 'W drodze', 'Dostarczone', 'Skompletowane'];
                                    foreach ($statuses as $status):
                                    ?>
                                        <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                                            <?= $status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit">Zmień status</button>
                            </form>
                        <?php endif; ?>
                        <a href="../order_details.php?id=<?= $order['id'] ?>" style="margin-left:10px;">Podgląd</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
