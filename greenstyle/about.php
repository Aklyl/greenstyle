<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>O nas – GreenStyle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .about-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px #0002;
            padding: 36px 28px;
        }
        .about-container h2 {
            color: #388e3c;
            margin-bottom: 18px;
            text-align: center;
        }
        .about-container ul {
            margin: 18px 0 0 20px;
            color: #234d20;
        }
        .about-container strong {
            color: #388e3c;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>GreenStyle 🌿</h1>
            <nav>
                <a href="index.php">🏠 Strona główna</a>
                <a href="about.php">🌱 O nas</a>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="cart.php">🛒 Koszyk</a>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <a href="admin/index.php">⚙️ Panel admina</a>
                    <?php endif; ?>
                    <span>Witaj, <?= htmlspecialchars($_SESSION['user']['name']) ?> |</span>
                    <a href="logout.php">🚪 Wyloguj</a>
                <?php else: ?>
                    <a href="login.php">🔐 Zaloguj</a>
                    <a href="register.php">📝 Rejestracja</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="about-container">
        <h2>🌱 O GreenStyle – Ekologiczne ubrania</h2>
        <p>
            W GreenStyle wierzymy, że moda może być przyjazna dla ludzi i planety. Nasze produkty powstają z myślą o środowisku, zdrowiu i komforcie użytkowników.
        </p>
        <h3>Z czego wykonane są nasze ubrania?</h3>
        <ul>
            <li><strong>Bawełna organiczna</strong> – uprawiana bez pestycydów i sztucznych nawozów, bezpieczna dla skóry i środowiska.</li>
            <li><strong>Len ekologiczny</strong> – naturalny, przewiewny materiał, którego produkcja wymaga niewielkiej ilości wody.</li>
            <li><strong>Włókna bambusowe</strong> – biodegradowalne, miękkie i antybakteryjne, pozyskiwane z szybko rosnących roślin.</li>
            <li><strong>Recyklingowane materiały</strong> – wykorzystujemy przędze z recyklingu (np. poliester z butelek PET), by ograniczać ilość odpadów.</li>
            <li><strong>Barwniki roślinne</strong> – stosujemy naturalne barwniki, które są bezpieczne dla skóry i nie zanieczyszczają wód.</li>
        </ul>
        <h3>Nasze wartości:</h3>
        <ul>
            <li>🌍 <strong>Ekologia</strong> – minimalizujemy ślad węglowy i zużycie wody.</li>
            <li>🤝 <strong>Fair Trade</strong> – współpracujemy z certyfikowanymi dostawcami i dbamy o godne warunki pracy.</li>
            <li>♻️ <strong>Zero Waste</strong> – ograniczamy odpady na każdym etapie produkcji i pakowania.</li>
            <li>🇵🇱 <strong>Polska produkcja</strong> – większość kolekcji szyjemy lokalnie, wspierając rodzimy rynek.</li>
        </ul>
        <p>
            Wybierając GreenStyle, wybierasz świadomą modę, która dba o Ciebie i naszą planetę.<br>
            Dziękujemy, że jesteś z nami!
        </p>
    </div>
</body>
</html>