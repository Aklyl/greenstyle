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
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel administratora - GreenStyle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">👨‍💼 GreenStyle Admin</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="../index.php">🏠 Strona główna sklepu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_product.php">➕ Dodaj produkt</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="orders.php">🧾 Zamówienia</a>
        </li>
      </ul>
      <span class="navbar-text text-light me-3">
        Zalogowany jako: <?= htmlspecialchars($_SESSION['user']['email']) ?>
      </span>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">🚪 Wyloguj</a>
    </div>
  </div>
</nav>

<div class="container">
    <h2>📦 Lista produktów</h2>

    <table class="table table-striped table-bordered align-middle mt-3">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Cena (zł)</th>
                <th>Obraz</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
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
                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-sm">✏️ Edytuj</a>
                    <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Na pewno usunąć produkt?');">🗑️ Usuń</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
