<?php
require 'db.php';

// Pobierz kategorie
// $categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Parametry
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? '';

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

// Szukanie
if ($search) {
    $sql .= " AND name LIKE ?";
    $params[] = "%$search%";
}

// Filtrowanie
if ($category) {
    $sql .= " AND category_id = ?";
    $params[] = $category;
}

// Sortowanie
if ($sort == 'price_asc') $sql .= " ORDER BY price ASC";
elseif ($sort == 'price_desc') $sql .= " ORDER BY price DESC";
else $sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<h2>Sklep</h2>

<form method="get">
    <input type="text" name="search" placeholder="Szukaj..." value="<?= htmlspecialchars($search) ?>">
    
    <select name="category">
        <option value="">Wszystkie kategorie</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id'] == $category ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="sort">
        <option value="">Sortuj wg</option>
        <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>Cena rosnąco</option>
        <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>Cena malejąco</option>
    </select>

    <button type="submit">Szukaj</button>
</form>

<br>

<?php foreach ($products as $p): ?>
    <div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p><?= htmlspecialchars($p['description']) ?></p>
        <p><strong><?= number_format($p['price'], 2) ?> zł</strong></p>
        <a href="product.php?id=<?= $p['id'] ?>">Szczegóły</a>
    </div>
<?php endforeach; ?>
