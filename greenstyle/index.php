<?php
require 'db.php';
session_start();

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<h1>GreenStyle – ekologiczne ubrania</h1>
<?php if (isset($_SESSION['user'])): ?>
    <p>Witaj, <?= $_SESSION['user']['name'] ?> | <a href="logout.php">Wyloguj</a></p>
<?php else: ?>
    <p><a href="login.php">Zaloguj się</a> | <a href="register.php">Zarejestruj się</a></p>
<?php endif; ?>

<h2>Nasze produkty</h2>
<div style="display: flex; flex-wrap: wrap;">
    <?php foreach ($products as $product): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px; width: 200px;">
            <img src="uploads/<?= $product['image'] ?>" alt="" width="100%">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <p><?= number_format($product['price'], 2) ?> zł</p>
            <a href="product.php?id=<?= $product['id'] ?>">Szczegóły</a>
        </div>
    <?php endforeach; ?>
</div>
