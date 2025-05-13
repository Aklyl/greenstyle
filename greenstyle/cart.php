<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Pobieramy produkty w koszyku
$stmt = $pdo->prepare("
    SELECT cart.*, products.name, products.price, products.image
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

$total = 0;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Koszyk – GreenStyle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px #0002; padding: 32px 24px; }
        .cart-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .cart-table th, .cart-table td { padding: 14px 10px; text-align: center; border-bottom: 1px solid #e0f2f1; }
        .cart-table th { background: #388e3c; color: #fff; font-weight: 600; }
        .cart-table tr:nth-child(even) { background: #e8f5e9; }
        .cart-table tr:hover { background: #c8e6c9; }
        .cart-table img { border-radius: 6px; box-shadow: 0 2px 8px #0001; background: #e8f5e9; max-height: 60px; max-width: 80px; }
        .cart-actions button { background: #43a047; color: #fff; border: none; border-radius: 20px; padding: 7px 18px; font-size: 1em; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .cart-actions button:hover { background: #2e7d32; }
        .cart-back { display: inline-block; margin-bottom: 18px; color: #388e3c; text-decoration: none; }
        .cart-back:hover { text-decoration: underline; }
        @media (max-width: 700px) {
            .cart-container { padding: 10px 2px; }
            .cart-table, .cart-table thead, .cart-table tbody, .cart-table th, .cart-table td, .cart-table tr { display: block; }
            .cart-table thead tr { display: none; }
            .cart-table tr { margin-bottom: 18px; background: #fff; box-shadow: 0 2px 8px #0001; border-radius: 10px; padding: 10px 0; }
            .cart-table td { text-align: left; padding: 10px 16px; position: relative; }
            .cart-table td:before {
                content: attr(data-label);
                font-weight: bold;
                color: #388e3c;
                display: block;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>GreenStyle 🌿</h1>
            <nav>
                <a href="index.php">🏠 Strona główna</a>
                <a href="cart.php">🛒 Koszyk</a>
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
    <div class="cart-container">
        <h2>🛒 Twój koszyk</h2>
        <a href="index.php" class="cart-back">⬅️ Powrót do sklepu</a><br><br>
        <?php if (count($items) > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Ilość</th>
                        <th>Cena</th>
                        <th>Rozmiar</th>
                        <th>Suma</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item):
                    $sum = $item['price'] * $item['quantity'];
                    $total += $sum;
                ?>
                <tr>
                    <td data-label="Produkt">
                        <?php if ($item['image']): ?>
                            <img src="<?= htmlspecialchars($item['image']) ?>" width="50" alt="obrazek produktu"><br>
                        <?php endif; ?>
                        <?= htmlspecialchars($item['name']) ?>
                    </td>
                    <td data-label="Ilość">
                        <form method="post" action="update_cart.php" style="display:inline;">
                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width:50px;">
                            <button type="submit">Aktualizuj</button>
                        </form>
                    </td>
                    <td data-label="Cena"><?= number_format($item['price'], 2) ?> zł</td>
                    <td data-label="Rozmiar"><?= htmlspecialchars($item['size']) ?></td>
                    <td data-label="Suma"><?= number_format($sum, 2) ?> zł</td>
                    <td data-label="Akcje" class="cart-actions">
                        <form method="post" action="remove_from_cart.php">
                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                            <button type="submit">Usuń</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Łącznie:</strong></td>
                    <td colspan="2"><strong><?= number_format($total, 2) ?> zł</strong></td>
                </tr>
                </tbody>
            </table>
            <a href="order.php"><button>Złóż zamówienie</button></a>
        <?php else: ?>
            <p>Twój koszyk jest pusty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
