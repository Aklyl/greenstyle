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
    SELECT cart.product_id, products.name, products.price, cart.quantity, cart.size
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
    $size    = $_POST['size'] ?? null; // pobierz rozmiar z formularza, jeśli jest

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, address, phone, size) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $address, $phone, $size]);

    // Pobierz ID ostatnio dodanego zamówienia
    $order_id = $pdo->lastInsertId();

    // Dodaj pozycje do zamówienia
    foreach ($items as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price, size)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price'],
            $item['size']
        ]);
    }

    // Opróżnij koszyk
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Nowoczesna strona podziękowania:
    ?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Zamówienie złożone – GreenStyle</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <style>
            body {
                background: #e8f5e9;
                font-family: 'Segoe UI', Arial, sans-serif;
            }
            .thanks-container {
                max-width: 500px;
                margin: 60px auto;
                background: #fff;
                border-radius: 18px;
                box-shadow: 0 4px 24px #0002;
                padding: 40px 30px 32px 30px;
                text-align: center;
            }
            .thanks-container h2 {
                color: #388e3c;
                margin-bottom: 18px;
                font-size: 2em;
            }
            .thanks-container p {
                color: #234d20;
                font-size: 1.2em;
                margin-bottom: 28px;
            }
            .thanks-container a {
                display: inline-block;
                background: #43a047;
                color: #fff;
                text-decoration: none;
                border-radius: 20px;
                padding: 12px 32px;
                font-size: 1.1em;
                font-weight: 600;
                transition: background 0.2s;
            }
            .thanks-container a:hover {
                background: #2e7d32;
            }
            .thanks-container .icon {
                font-size: 3em;
                margin-bottom: 10px;
                color: #43a047;
            }
        </style>
    </head>
    <body>
        <div class="thanks-container">
            <div class="icon">✅</div>
            <h2>Dziękujemy za złożenie zamówienia!</h2>
            <p>Twoje zamówienie zostało przyjęte.<br>
            Wkrótce otrzymasz potwierdzenie na e-mail.</p>
            <a href="index.php">← Powrót do sklepu</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Złożenie zamówienia – GreenStyle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #e8f5e9;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .order-container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px #0002;
            padding: 36px 28px;
        }
        h2, h3 {
            color: #388e3c;
            text-align: center;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
            background: #f6fff7;
            border-radius: 10px;
            overflow: hidden;
        }
        .order-table th, .order-table td {
            padding: 14px 10px;
            text-align: center;
            border-bottom: 1px solid #e0f2f1;
        }
        .order-table th {
            background: #388e3c;
            color: #fff;
            font-weight: 600;
        }
        .order-table tr:last-child td {
            border-bottom: none;
        }
        .order-table tr:nth-child(even) {
            background: #e8f5e9;
        }
        .order-table tr:hover {
            background: #c8e6c9;
        }
        .order-form label {
            font-weight: 500;
            color: #234d20;
        }
        .order-form input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0 18px 0;
            border-radius: 8px;
            border: 1px solid #b2dfdb;
            background: #f6fff7;
            font-size: 1em;
            box-sizing: border-box;
            transition: border 0.2s;
        }
        .order-form input[type="text"]:focus {
            border: 2px solid #43a047;
            outline: none;
        }
        .order-form button[type="submit"] {
            background: #43a047;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 12px 32px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
            display: block;
            width: 100%;
        }
        .order-form button[type="submit"]:hover {
            background: #2e7d32;
        }
        @media (max-width: 800px) {
            .order-container { padding: 12px 4px; }
            .order-table th, .order-table td { padding: 10px 4px; }
        }
    </style>
</head>
<body>
    <div class="order-container">
        <h2>Podsumowanie zamówienia</h2>
        <table class="order-table">
            <tr>
                <th>Nazwa produktu</th>
                <th>Cena (zł)</th>
                <th>Ilość</th>
                <th>Rozmiar</th>
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
                    <td><?= htmlspecialchars($item['size']) ?></td>
                    <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Całkowita kwota:</strong></td>
                <td><strong><?= number_format($total, 2) ?> zł</strong></td>
            </tr>
        </table>

        <h3>Formularz zamówienia</h3>
        <form method="post" class="order-form">
            <label for="address">Adres dostawy:</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Numer telefonu:</label>
            <input type="text" id="phone" name="phone" required>

            <button type="submit">Złóż zamówienie</button>
        </form>
    </div>
</body>
</html>
