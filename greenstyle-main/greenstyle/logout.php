<?php
session_start();
session_unset(); // Czyszczenie sesji
session_destroy(); // Zniszczenie sesji

header("Location: login.php"); // Przekierowanie na stronę logowania
exit;
?>
