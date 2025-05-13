-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 13 Maj 2025, 11:51
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `greenstyle`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(32) DEFAULT 'Nowe',
  `size` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address`, `phone`, `created_at`, `total_price`, `status`, `size`) VALUES
(1, 2, 'test test test 1/2', '70000000000', '2025-05-12 08:34:42', '0.00', 'Anulowane', NULL),
(2, 5, 'test test test 1/233', '4343434343', '2025-05-12 08:39:41', '0.00', 'Nowe', NULL),
(3, 5, 'po zmianie', '23424424', '2025-05-12 08:42:48', '0.00', 'Nowe', NULL),
(4, 5, 'sdfsfdfsf', 'dfsdfsdfsf', '2025-05-13 08:46:09', '0.00', 'Nowe', NULL),
(5, 5, 'ertetetete', 'ertetretertert', '2025-05-13 08:46:43', '0.00', 'W przygotowaniu', NULL),
(6, 5, 'sdsdfsdf', 'sdfsdf', '2025-05-13 09:12:14', '0.00', 'Nowe', NULL),
(7, 2, 'sdf', 'dsf', '2025-05-13 09:15:54', '0.00', 'Nowe', NULL),
(8, 2, 'test11', 'test11', '2025-05-13 09:42:21', '0.00', 'Nowe', NULL),
(9, 5, 'AASDADSADA', 'ASDASDASDA', '2025-05-13 09:44:19', '0.00', 'Nowe', NULL),
(10, 5, 'asdadsa', 'adsads', '2025-05-13 09:47:47', '0.00', 'Nowe', NULL),
(11, 5, 'hjkhjkhjk', 'hjkhkjh', '2025-05-13 09:49:15', '0.00', 'Nowe', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `size`) VALUES
(8, 6, 10, 1, '139.99', NULL),
(9, 7, 10, 1, '139.99', 'S'),
(10, 8, 9, 1, '64.99', 'XL'),
(11, 8, 9, 1, '64.99', 'L'),
(12, 9, 10, 1, '139.99', 'XS'),
(13, 9, 9, 1, '64.99', 'XS'),
(14, 10, 8, 1, '89.99', 'XS'),
(15, 11, 7, 1, '119.99', 'L');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES
(4, 'Koszulka Classic Eco', 'Klasyczna koszulka z bawełny organicznej.', '49.99', 'uploads/1747126986_kszulkaa.jpg'),
(5, 'Koszulka Sport Black', 'Lekka koszulka sportowa z recyklingowanego poliestru, kolor czarny', '59.99', 'uploads/1747127039_pobrane.jpg'),
(6, 'Koszulka Bamboo Soft', 'Miękka koszulka z włókna bambusowego, kolor czarny.', '54.99', 'uploads/1747127279_tshirt.jpg'),
(7, 'Spodnie Chino Eco', 'Wygodne spodnie chino z bawełny organicznej, kolor czarny.', '119.99', 'uploads/1747127105_spodnid1.jpg'),
(8, 'Spodnie Dresowe Basic', 'podnie dresowe z recyklingu, kolor szary.', '89.99', 'uploads/1747127156_dres.jpg'),
(9, 'Koszulka Summer Print', 'Kolorowa koszulka z nadrukiem, wykonana z bawełny organicznej.', '64.99', 'uploads/1747127176_shopping.jpg'),
(10, 'Spodnie Jeans Eco', 'Jeansy z domieszką włókien z recyklingu, kolor szary.', '139.99', 'uploads/1747127200_spodnie.jpg'),
(11, 'Koszulka Longsleeve Forest', 'Długi rękaw z miękkiej bawełny organicznej, kolor butelkowa zieleń.', '69.99', 'uploads/1747127251_zielen.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(2, 'joanna', 'joanna@greenstyle.pl', '$2y$10$uWBbA6cD1FfqCKMggw.bUuSD0.NSNMu2AdEYoQ1o19ru93KXE.EDK', 'user'),
(5, 'Admin', 'admin@greenstyle.pl', '$2y$10$f7LMplF2mtHPBTszZrgyRuBmC2CXlKSZzxlOZg6hV18UqNKbGecLS', 'admin');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeksy dla tabeli `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT dla tabeli `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT dla tabeli `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
