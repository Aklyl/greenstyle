<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    die('Brak dostępu');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    // Obsługa zdjęcia
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = '../uploads/';
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'],'../uploads/'. $target_file)) {

            //move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $filename);

            $image_path = 'uploads/' . $image_name;
        } else {
            $errors[] = 'Błąd przy zapisie zdjęcia.';
        }
    }

    if (!$name || !$price) {
        $errors[] = 'Wypełnij wszystkie wymagane pola.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $image_path]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj produkt - Panel Admina</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Dodaj nowy produkt</h1>
    <a href="index.php">← Wróć do panelu</a>

    <?php foreach ($errors as $error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Nazwa:</label><br>
        <input type="text" name="name"><br><br>

        <label>Opis:</label><br>
        <textarea name="description" rows="4" cols="50"></textarea><br><br>

        <label>Cena (zł):</label><br>
        <input type="number" step="0.01" name="price"><br><br>

        <label>Zdjęcie produktu:</label><br>
        <input type="file" name="image"><br><br>

        <button type="submit">Dodaj produkt</button>
    </form>
</body>
</html>
