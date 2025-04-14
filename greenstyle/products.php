<?php
require 'db.php';
session_start();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Nie podano produktu.");
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Produkt nie istnieje.");
}
?>

<h2><?= htmlspecialchars($product['name']) ?></h2>
<img src="uploads/<?= $product['image'] ?>" alt="" width="300px"><br>
<p><strong>Cena:</strong> <?= number_format($product['price'], 2) ?> zł</p>
<p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

<?php if (isset($_SESSION['user'])): ?>
    <form method="post" action="add_to_cart.php">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        Ilość: <input type="number" name="quantity" value="1" min="1">
        <button type="submit">Dodaj do koszyka</button>
    </form>
<?php else: ?>
    <p><a href="login.php">Zaloguj się</a>, aby dodać do koszyka.</p>
<?php endif; ?>
