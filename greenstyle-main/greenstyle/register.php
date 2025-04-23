<?php
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "Wszystkie pola są wymagane.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Nieprawidłowy email.";
    } elseif ($password !== $confirm) {
        $errors[] = "Hasła się nie zgadzają.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Użytkownik z takim mailem już istnieje.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);
            header("Location: login.php");
            exit;
        }
    }
}
?>

<h2>Rejestracja</h2>
<?php foreach ($errors as $error): ?>
    <p style="color:red"><?= $error ?></p>
<?php endforeach; ?>
<form method="post">
    Imię i nazwisko: <input type="text" name="name"><br>
    Email: <input type="email" name="email"><br>
    Hasło: <input type="password" name="password"><br>
    Potwierdź hasło: <input type="password" name="confirm"><br>
    <button type="submit">Zarejestruj się</button>
</form>
