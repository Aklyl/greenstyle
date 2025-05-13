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
    <style>
        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
        }
        .product {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px #0001;
            padding: 24px 18px 18px 18px;
            width: 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .product img {
            max-width: 120px;
            max-height: 120px;
            margin-bottom: 12px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #0001;
        }
        .product select[name="size"] {
            margin: 12px 0 10px 0;
            padding: 7px 18px;
            border-radius: 8px;
            border: 1px solid #388e3c;
            background: #e8f5e9;
            font-size: 1em;
            color: #234d20;
            font-weight: 500;
            outline: none;
            transition: border 0.2s;
            width: 100%;
            box-sizing: border-box;
            display: block;
        }
        .product select[name="size"]:focus {
            border: 2px solid #43a047;
        }
        .product button[type="submit"] {
            background: #43a047;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 9px 22px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            transition: background 0.2s;
            width: 100%;
            box-sizing: border-box;
            display: block;
        }
        .product button[type="submit"]:hover {
            background: #2e7d32;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1>GreenStyle 🌿</h1>
        <nav>
            <a href="index.php">🏠 Strona główna</a>
            <a href="about.php">🌱 O nas</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="cart.php">🛒 Koszyk</a>
                <a href="orders_history.php">📦 Moje zamówienia</a>
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
                    <select name="size" required>
                        <option value="">Rozmiar</option>
                        <option>XS</option>
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                        <option>XXL</option>
                        <option>XXXL</option>
                    </select>
                    <button type="submit">➕ Do koszyka</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
