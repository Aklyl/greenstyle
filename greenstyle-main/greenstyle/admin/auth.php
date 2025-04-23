<?php
session_start();

// Sprawdzamy, czy użytkownik jest zalogowany i czy ma rolę 'admin'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Jeśli nie jest zalogowany jako admin, przekierowujemy na stronę logowania
    header("Location: ../login.php");
    exit;
}

// Jeśli jest adminem, to kontynuujemy
?>
