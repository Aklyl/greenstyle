<?php
session_start();
require '../db.php';

// Sprawdzenie, czy użytkownik to admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('Brak dostępu');
}

// Pobranie ID produktu z adresu URL
if (!isset($_GET['id'])) {
    die('Nie podano ID produktu');
}

$product_id = (int) $_GET['id'];

// Jeśli formularz został wysłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = (float) $_POST['price'];

    // Obsługa przesłanego pliku (jeśli jest)
    if (!empty($_FILES['image']['name'])) {
        $filename = time() . '_' . $_FILES['image']['name'];
        $target_path = '../uploads/' . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // Zaktualizuj produkt z nowym zdjęciem
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, 'uploads/' . $filename, $product_id]);
        } else {
            echo "<p>Błąd podczas zapisu zdjęcia.</p>";
        }
    } else {
        // Zaktualizuj produkt bez zmiany zdjęcia
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $product_id]);
    }

    header('Location: index.php');
    exit;
}

// Pobierz dane produktu z bazy
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die('Nie znaleziono produktu.');
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj produkt – Panel admina</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .edit-container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px #0002;
            padding: 32px 24px;
        }
        .edit-container h2 {
            color: #388e3c;
            margin-bottom: 18px;
            text-align: center;
        }
        .edit-container label {
            font-weight: 500;
            color: #234d20;
        }
        .edit-container input[type="text"],
        .edit-container input[type="number"],
        .edit-container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0f2f1;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 1em;
            background: #f6fff7;
        }
        .edit-container input[type="file"] {
            margin-bottom: 18px;
        }
        .edit-container button {
            background: #43a047;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 12px 28px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            display: block;
            margin: 0 auto;
        }
        .edit-container button:hover {
            background: #2e7d32;
        }
        .edit-container .back-link {
            display: inline-block;
            margin-bottom: 18px;
            color: #388e3c;
            text-decoration: none;
        }
        .edit-container .back-link:hover {
            text-decoration: underline;
        }
        .edit-container .current-img {
            display: block;
            margin: 0 auto 18px auto;
            max-width: 180px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #0001;
        }
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
    <div class="edit-container">
        <h2>✏️ Edytuj produkt</h2>
        <?php if ($product['image']): ?>
            <img src="../<?= htmlspecialchars($product['image']) ?>" alt="obrazek produktu" class="current-img">
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label>Nazwa:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

            <label>Opis:</label>
            <textarea name="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>

            <label>Cena (zł):</label>
            <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required>

            <label>Obrazek (jeśli chcesz zmienić):</label>
            <input type="file" name="image">

            <button type="submit">Zapisz zmiany</button>
        </form>
    </div>
</body>
</html>
