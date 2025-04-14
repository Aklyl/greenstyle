<?php
require 'auth.php';
require '../db.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<h2>Produkty</h2>
<a href="add_product.php">➕ Dodaj produkt</a><br><br>

<table border="1" cellpadding="10">
    <tr><th>ID</th><th>Nazwa</th><th>Cena</th><th>Akcje</th></tr>
    <?php foreach ($products as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= number_format($p['price'], 2) ?> zł</td>
        <td>
            <a href="edit_product.php?id=<?= $p['id'] ?>">✏️</a>
            <a href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('Na pewno?')">🗑️</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
