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

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja – GreenStyle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .register-container {
            max-width: 420px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px #0002;
            padding: 32px 28px;
        }
        .register-container h2 {
            color: #388e3c;
            margin-bottom: 22px;
            text-align: center;
        }
        .register-container label {
            font-weight: 500;
            color: #234d20;
        }
        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0f2f1;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 1em;
            background: #f6fff7;
        }
        .register-container button {
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
        .register-container button:hover {
            background: #2e7d32;
        }
        .register-container .error {
            color: #c62828;
            background: #ffebee;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 16px;
            text-align: center;
        }
        .register-container .login-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #388e3c;
            text-decoration: none;
        }
        .register-container .login-link:hover {
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
    <div class="register-container">
        <h2>Rejestracja</h2>
        <?php foreach ($errors as $error): ?>
            <div class="error"><?= $error ?></div>
        <?php endforeach; ?>
        <form method="post" autocomplete="off">
            <label for="name">Imię i nazwisko:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm">Potwierdź hasło:</label>
            <input type="password" name="confirm" id="confirm" required>

            <button type="submit">Zarejestruj się</button>
        </form>
        <a class="login-link" href="login.php">Masz już konto? Zaloguj się</a>
    </div>
</body>
</html>
