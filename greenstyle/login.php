<?php
require 'db.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Sprawdzenie użytkownika w bazie danych
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Weryfikacja hasła
    if ($user && password_verify($password, $user['password'])) {
        // Ustawienie sesji użytkownika
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'], // np. 'admin' lub 'user'
        ];

        // Przekierowanie do odpowiedniej strony w zależności od roli
        if ($_SESSION['user']['role'] === 'admin') {
            header("Location: admin/index.php"); // Panel admina
        } else {
            header("Location: index.php"); // Strona główna dla użytkowników
        }
        exit;
    } else {
        $errors[] = "Nieprawidłowy email lub hasło.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logowanie</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Logowanie</h2>
    <?php foreach ($errors as $error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endforeach; ?>
    <form method="post">
        Email: <input type="email" name="email" required><br>
        Hasło: <input type="password" name="password" required><br>
        <button type="submit">Zaloguj się</button>
    </form>
</body>
</html>
