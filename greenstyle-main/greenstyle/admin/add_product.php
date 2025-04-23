<?php
session_start();

// Sprawdzamy, czy użytkownik jest zalogowany i ma rolę 'admin'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('Brak dostępu');
}

// Obsługa formularza dodawania produktu (przykład)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sprawdź, czy dane zostały przesłane
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $image = $_FILES['image'];

    if (empty($name) || empty($description) || empty($price)) {
        echo "Wszystkie pola są wymagane.";
    } else {
        // Zapisywanie pliku obrazu
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image['name']);

        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            // Po zapisaniu obrazu, dodaj produkt do bazy
            require '../db.php';
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, basename($image['name'])]);

            echo "Produkt został dodany!";
        } else {
            echo "Błąd przy zapisie zdjęcia.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj produkt - Panel Administratora</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>👨‍💼 Panel administratora</h1>
    <p>Witaj, <?= htmlspecialchars($_SESSION['user']['name']) ?></p>

    <ul>
        <li><a href="add_product.php">➕ Dodaj nowy produkt</a></li>
        <li><a href="orders.php">🧾 Zobacz zamówienia</a></li>
        <li><a href="../index.php">🏠 Przejdź do strony głównej sklepu</a></li>
        <li><a href="../logout.php">🚪 Wyloguj</a></li>
    </ul>

    <h2>📦 Dodaj nowy produkt</h2>

    <form method="POST" enctype="multipart/form-data">
        Nazwa: <input type="text" name="name" required><br>
        Opis: <textarea name="description" required></textarea><br>
        Cena (zł): <input type="number" name="price" required><br>
        Zdjęcie produktu: <input type="file" name="image" required><br>
        <button type="submit">Dodaj produkt</button>
    </form>
</body>
</html>
