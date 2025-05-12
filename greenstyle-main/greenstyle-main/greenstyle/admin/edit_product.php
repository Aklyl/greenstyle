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

<h1>✏️ Edytuj produkt</h1>
<a href="index.php">← Wróć do panelu</a>
<form method="post" enctype="multipart/form-data">
    <label>Nazwa:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>"><br><br>

    <label>Opis:</label><br>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea><br><br>

    <label>Cena (zł):</label><br>
    <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>"><br><br>

    <label>Obrazek (jeśli chcesz zmienić):</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit">Zapisz zmiany</button>
</form>
