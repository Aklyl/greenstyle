<?php
require 'db.php';
session_start();

// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Pobierz dane z koszyka
$stmt = $pdo->prepare("
    SELECT cart.product_id, products.name, products.price, cart.quantity
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

if (count($items) === 0) {
    echo "<p>Twój koszyk jest pusty.</p>";
    echo "<a href='index.php'>Wróć do sklepu</a>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address']);
    $phone   = trim($_POST['phone']);

    // Dodaj zamówienie do bazy danych
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, address, phone)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$user_id, $address, $phone]);

    // Pobierz ID ostatnio dodanego zamówienia
    $order_id = $pdo->lastInsertId();

    // Dodaj pozycje do zamówienia
    foreach ($items as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // Opróżnij koszyk
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Przekierowanie po złożeniu zamówienia
    echo "<p>Twoje zamówienie zostało złożone.</p>";
    echo "<a href='index.php'>Wróć do sklepu</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Złożenie zamówienia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Podsumowanie zamówienia</h2>

        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Nazwa produktu</th>
                <th>Cena (zł)</th>
                <th>Ilość</th>
                <th>Razem (zł)</th>
            </tr>
            <?php
            $total = 0;
            foreach ($items as $item):
                $total += $item['price'] * $item['quantity'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Całkowita kwota:</strong></td>
                <td><strong><?= number_format($total, 2) ?> zł</strong></td>
            </tr>
        </table>

        <h3>Formularz zamówienia</h3>
        <form method="post">
            <label for="address">Adres dostawy:</label><br>
            <input type="text" id="address" name="address" required><br><br>

            <label for="phone">Numer telefonu:</label><br>
            <input type="text" id="phone" name="phone" required><br><br>

            <button type="submit">Złóż zamówienie</button>
        </form>
    </div>
</body>
</html>
