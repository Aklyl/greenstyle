<?php
session_start();

// Sprawdzamy, czy użytkownik jest zalogowany i ma rolę 'admin'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('Brak dostępu');
}

$message = "";

// Obsługa formularza dodawania produktu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $image = $_FILES['image'];

    if (empty($name) || empty($description) || empty($price)) {
        $message = '<div class="error">Wszystkie pola są wymagane.</div>';
    } else {
        $target_dir = "../uploads/";
        $filename = time() . '_' . basename($image['name']);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            require '../db.php';
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, 'uploads/' . $filename]);
            $message = '<div class="success">Produkt został dodany!</div>';
        } else {
            $message = '<div class="error">Błąd przy zapisie zdjęcia.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj produkt – Panel administratora</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .add-container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px #0002;
            padding: 32px 24px;
        }
        .add-container h2 {
            color: #388e3c;
            margin-bottom: 18px;
            text-align: center;
        }
        .add-container label {
            font-weight: 500;
            color: #234d20;
        }
        .add-container input[type="text"],
        .add-container input[type="number"],
        .add-container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0f2f1;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 1em;
            background: #f6fff7;
        }
        .add-container input[type="file"] {
            margin-bottom: 18px;
        }
        .add-container button {
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
        .add-container button:hover {
            background: #2e7d32;
        }
        .add-container .success {
            color: #2e7d32;
            background: #e8f5e9;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 16px;
            text-align: center;
        }
        .add-container .error {
            color: #c62828;
            background: #ffebee;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 16px;
            text-align: center;
        }
        .add-container .back-link {
            display: inline-block;
            margin-bottom: 18px;
            color: #388e3c;
            text-decoration: none;
        }
        .add-container .back-link:hover {
            text-decoration: underline;
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
    <div class="add-container">
        <h2>📦 Dodaj nowy produkt</h2>
        <?= $message ?>
        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <label for="name">Nazwa:</label>
            <input type="text" name="name" id="name" required>

            <label for="description">Opis:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="price">Cena (zł):</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <label for="image">Zdjęcie produktu:</label>
            <input type="file" name="image" id="image" required>

            <button type="submit">Dodaj produkt</button>
        </form>
    </div>
</body>
</html>
