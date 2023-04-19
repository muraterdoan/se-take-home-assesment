-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 19 Nis 2023, 10:02:47
-- Sunucu sürümü: 10.6.12-MariaDB-cll-lve
-- PHP Sürümü: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `u803060093_ideasoft`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `items` text NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `customerId`, `items`, `total`) VALUES
(1, 1, '[\n            {\n                \"productId\": 102,\n                \"quantity\": 10,\n                \"unitPrice\": \"11.28\",\n                \"total\": \"112.80\"\n            }\n        ]', 112.8),
(2, 2, '[\n            {\n                \"productId\": 101,\n                \"quantity\": 2,\n                \"unitPrice\": \"49.50\",\n                \"total\": \"99.00\"\n            },\n            {\n                \"productId\": 100,\n                \"quantity\": 1,\n                \"unitPrice\": \"120.75\",\n                \"total\": \"120.75\"\n            }\n        ]', 120.75),
(3, 3, '[\n            {\n                \"productId\": 102,\n                \"quantity\": 6,\n                \"unitPrice\": \"11.28\",\n                \"total\": \"67.68\"\n            },\n            {\n                \"productId\": 100,\n                \"quantity\": 10,\n                \"unitPrice\": \"120.75\",\n                \"total\": \"1207.50\"\n            }\n        ]', 1207.5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `category` int(11) NOT NULL,
  `price` float NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `stock`) VALUES
(100, 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti', 1, 120.75, 10),
(101, 'Reko Mini Tamir Hassas Tornavida Seti 32\'li', 1, 49.5, 10),
(102, 'Viko Karre Anahtar - Beyaz', 2, 11.28, 10),
(103, 'Legrand Salbei Anahtar, Alüminyum', 2, 22.8, 10),
(104, 'Schneider Asfora Beyaz Komütatör', 2, 12.95, 10);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
