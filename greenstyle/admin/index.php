<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    die('Brak dostępu');
}

// Pobierz wszystkie produkty
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel administratora</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>👨‍💼 Panel administratora</h1>
    <p>Witaj, <?= htmlspecialchars($_SESSION['user']['email']) ?></p>

    <ul>
        <li><a href="add_product.php">➕ Dodaj nowy produkt</a></li>
        <li><a href="orders.php">🧾 Zobacz zamówienia</a></li>
        <li><a href="../index.php">🏠 Przejdź do strony głównej sklepu</a></li>
        <li><a href="../logout.php">🚪 Wyloguj</a></li>
    </ul>

    <h2>📦 Lista produktów</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Opis</th>
            <th>Cena (zł)</th>
            <th>Obraz</th>
            <th>Akcje</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td><?= number_format($product['price'], 2) ?></td>
                <td>
                    <?php if ($product['image']): ?>
                        <img src="../<?= $product['image'] ?>" alt="obrazek" height="50">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_product.php?id=<?= $product['id'] ?>">✏️ Edytuj</a> |
                    <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Na pewno usunąć produkt?');">🗑️ Usuń</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
