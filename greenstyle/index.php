<?php
require 'db.php';
session_start();


// Pobierz produkty
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>GreenStyle – ekologiczne ubrania</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1>GreenStyle 🌿</h1>
        <nav>
            <a href="index.php">🏠 Strona główna</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="cart.php">🛒 Koszyk</a>
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


    <h2>🛍️ Nasze produkty</h2>

    <div class="products">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <?php if ($product['image']): ?>
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="obrazek produktu">
                <?php endif; ?>
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <div class="price"><?= number_format($product['price'], 2) ?> zł</div>
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit">➕ Do koszyka</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
