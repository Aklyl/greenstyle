<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Pobieramy produkty w koszyku
$stmt = $pdo->prepare("
    SELECT cart.id AS cart_id, products.name, products.price, products.image, cart.quantity
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

$total = 0;
?>

<h2>Twój koszyk</h2>
<a href="index.php">⬅️ Powrót do sklepu</a><br><br>

<?php if (count($items) > 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>Produkt</th>
            <th>Ilość</th>
            <th>Cena</th>
            <th>Suma</th>
            <th>Akcje</th>
        </tr>
        <?php foreach ($items as $item): 
            $sum = $item['price'] * $item['quantity'];
            $total += $sum;
        ?>
        <tr>
            <td>
                <img src="uploads/<?= $item['image'] ?>" width="50"><br>
                <?= htmlspecialchars($item['name']) ?>
            </td>
            <td>
                <form method="post" action="update_cart.php" style="display:inline;">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width:50px;">
                    <button type="submit">Aktualizuj</button>
                </form>
            </td>
            <td><?= number_format($item['price'], 2) ?> zł</td>
            <td><?= number_format($sum, 2) ?> zł</td>
            <td>
                <form method="post" action="remove_from_cart.php">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                    <button type="submit">Usuń</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>Łącznie:</strong></td>
            <td colspan="2"><strong><?= number_format($total, 2) ?> zł</strong></td>
        </tr>
    </table>
    <br>
    <a href="order.php"><button>Złóż zamówienie</button></a>
<?php else: ?>
    <p>Twój koszyk jest pusty.</p>
<?php endif; ?>
