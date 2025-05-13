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
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie – GreenStyle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px #0002;
            padding: 32px 28px;
        }
        .login-container h2 {
            color: #388e3c;
            margin-bottom: 22px;
            text-align: center;
        }
        .login-container label {
            font-weight: 500;
            color: #234d20;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0f2f1;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 1em;
            background: #f6fff7;
        }
        .login-container button {
            background: #43a047;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 12px 28px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
        }
        .login-container button:hover {
            background: #2e7d32;
        }
        .login-container .error {
            color: #c62828;
            background: #ffebee;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 16px;
            text-align: center;
        }
        .login-container .register-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #388e3c;
            text-decoration: none;
        }
        .login-container .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>GreenStyle 🌿</h1>
            <nav>
                <a href="index.php">🏠 Strona główna</a>
                <a href="login.php">🔐 Logowanie</a>
                <a href="register.php">📝 Rejestracja</a>
            </nav>
        </div>
    </header>
    <div class="login-container">
        <h2>Logowanie</h2>
        <?php foreach ($errors as $error): ?>
            <div class="error"><?= $error ?></div>
        <?php endforeach; ?>
        <form method="post" autocomplete="off">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Zaloguj się</button>
        </form>
        <a class="register-link" href="register.php">Nie masz konta? Zarejestruj się</a>
    </div>
</body>
</html>
