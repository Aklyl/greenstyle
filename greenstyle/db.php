<?php
$host = 'localhost';
$db   = 'greenstyle_db';
$user = 'root';  // zmień jeśli masz inne dane
$pass = '';      // zmień jeśli masz hasło
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die('Błąd połączenia z bazą danych: ' . $e->getMessage());
}
?>
