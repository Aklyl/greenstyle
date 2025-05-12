<?php
$host = 'localhost';
$dbname = 'greenstyle';  // nazwa bazy danych
$username = 'root';  // domyślny użytkownik w XAMPP
$password = '';  // domyślne hasło w XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
?>
