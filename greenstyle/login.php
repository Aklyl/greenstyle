<?php
require 'db.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id'   => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ];
        header("Location: index.php");
        exit;
    } else {
        $errors[] = "Nieprawidłowy email lub hasło.";
    }
}
?>

<h2>Logowanie</h2>
<?php foreach ($errors as $error): ?>
    <p style="color:red"><?= $error ?></p>
<?php endforeach; ?>
<form method="post">
    Email: <input type="email" name="email"><br>
    Hasło: <input type="password" name="password"><br>
    <button type="submit">Zaloguj się</button>
</form>
