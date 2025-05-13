<?php
session_start();
require '../db.php';

// Sprawdzamy, czy użytkownik jest zalogowany i ma rolę 'admin'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Pobierz wszystkie produkty
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel administratora</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>👨‍💼 Panel administratora</h1>
            <p>Witaj, <?= htmlspecialchars($_SESSION['user']['name']) ?></p>
            <nav>
                <a href="add_product.php">➕ Dodaj nowy produkt</a>
                <a href="orders.php">🧾 Zobacz zamówienia</a>
                <a href="../index.php">🏠 Strona sklepu</a>
                <a href="../logout.php">🚪 Wyloguj</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>📦 Lista produktów</h2>
        <div style="overflow-x:auto;">
            <table>
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
        </div>
    </div>
</body>
</html>
