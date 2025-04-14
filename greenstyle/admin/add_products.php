<?php
require 'auth.php';
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image");
    }

    $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)")
        ->execute([$name, $desc, $price, $image]);

    header('Location: products.php');
}
?>

<h2>Dodaj nowy produkt</h2>
<form method="post" enctype="multipart/form-data">
    Nazwa: <input name="name"><br><br>
    Opis: <textarea name="description"></textarea><br><br>
    Cena: <input name="price" type="number" step="0.01"><br><br>
    Obrazek: <input type="file" name="image"><br><br>
    <button type="submit">Dodaj</button>
</form>
