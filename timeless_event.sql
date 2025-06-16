-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 04:52 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timeless_event_updated`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `PlaceOrder` (IN `p_paket_id` INT, IN `p_nama` VARCHAR(255), IN `p_email` VARCHAR(255), IN `p_tanggal_event` DATE, IN `p_qty` INT)   BEGIN
    DECLARE calculated_total INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    -- Calculate total price using the function
    SET calculated_total = CalculateTotalPrice(p_paket_id, p_qty);

    -- Insert into pemesanan table
    INSERT INTO pemesanan (paket_id, nama, email, tanggal_event, qty, total, status)
    VALUES (p_paket_id, p_nama, p_email, p_tanggal_event, p_qty, calculated_total, 'pending');

    COMMIT;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CalculateTotalPrice` (`p_paket_id` INT, `p_qty` INT) RETURNS INT(11) READS SQL DATA BEGIN
    DECLARE total_price INT;
    DECLARE package_price INT;
    DECLARE discount_percentage TINYINT;

    -- Get package price and discount
    SELECT harga, diskon INTO package_price, discount_percentage
    FROM paket
    WHERE id = p_paket_id;

    -- Calculate total price with discount
    SET total_price = (package_price * p_qty) - (package_price * p_qty * discount_percentage / 100);

    RETURN total_price;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'septi', 'sptwn26@upi.edu', 'saran', 'dtyhuf,irdtxyk', '2025-05-23 15:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `list_paket`
--

CREATE TABLE `list_paket` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `harga` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` int(11) NOT NULL,
  `diskon` tinyint(4) NOT NULL,
  `status` varchar(20) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id`, `nama`, `kategori`, `deskripsi`, `harga`, `diskon`, `status`, `foto`) VALUES
(7, 'Elegant Romance', 'Wedding', 'Dekorasi klasik dan catering premium', 35000000, 10, 'new', 'elegan_romance.jpeg'),
(8, 'Garden Bliss', 'Wedding', 'Outdoor wedding di taman terbuka', 40000000, 15, 'new', 'garden_bliss.jpeg'),
(9, 'Royal Wedding', 'Wedding', 'Konsep mewah dengan gedung eksklusif', 60000000, 15, 'new', 'royal_wedding.jpeg'),
(10, 'Intimate Moment', 'Wedding', 'Paket untuk 100 tamu, dekor minimalis', 25000000, 5, 'new', 'intimate_moment.jpeg'),
(11, 'Rustic Charm', 'Wedding', 'Tema rustic dengan elemen kayu dan bunga liar', 30000000, 12, 'new', 'rustic_charm.jpeg'),
(12, 'Dino Party', 'Birthday', 'Tema dinosaurus untuk anak-anak', 6000000, 5, 'new', 'dino_party.jpeg'),
(13, 'Galaxy Night', 'Birthday', 'Tema luar angkasa dan pertunjukan LED', 14000000, 8, 'new', 'galaxy_night.jpeg'),
(14, 'Candyland', 'Birthday', 'Warna-warni dan banyak permen', 7500000, 8, 'new', 'candyland.jpeg'),
(15, 'Mermaid Splash', 'Birthday', 'Tema putri duyung dan kolam renang', 9000000, 0, 'new', 'mermaid_splash.jpeg'),
(16, 'Jungle Adventure', 'Birthday', 'Dekorasi hutan dan karakter hewan', 6800000, 10, 'new', 'jungle_adventure.jpeg'),
(17, 'Food Festival', 'Festival', 'Stand makanan dan minuman dari berbagai daerah', 10000000, 10, 'new', 'food_fiesta.jpeg'),
(18, 'Music festival', 'Festival', 'Mini konser dan lighting panggung', 35000000, 13, 'new', 'music_vibes.jpeg'),
(19, 'Art and Craft Expo', 'Festival', 'Pameran kerajinan lokal', 16000000, 8, 'new', 'art_craft_expo.jpeg'),
(20, 'Ramadan fair', 'Festival', 'Festival kuliner dan religi selama Ramadan', 17000000, 10, 'new', 'ramadhan_fair.jpeg'),
(21, 'Lantern Night', 'Festival', 'Festival malam dengan pelepasan lampion', 20000000, 10, 'new', 'lantern_night.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int(11) NOT NULL,
  `paket_id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tanggal_event` date DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `paket_id`, `nama`, `email`, `tanggal_event`, `qty`, `total`, `status`, `created_at`) VALUES
(1, 6, 'Septi', 'sptwn26@upi.edu', '2025-05-18', 1, 9000000, 'pending', '2025-05-18 07:40:25'),
(2, 6, 'Septi', 'sptwn26@upi.edu', '2025-05-18', 1, 9000000, 'pending', '2025-05-18 07:41:56'),
(3, 6, 'septi', 'septiawan@gmail.com', '2025-05-18', 1, 9000000, 'pending', '2025-05-18 07:42:14'),
(4, 6, 'septi', 'septiawan@gmail.com', '2025-05-18', 1, 9000000, 'pending', '2025-05-18 07:57:48'),
(5, 6, 'septi', 'fshaditya@gmail.com', '2025-05-18', 1, 9000000, 'pending', '2025-05-18 07:58:04'),
(6, 6, 'septi', 'fshaditya@gmail.com', '2025-05-18', 1, 9000000, 'pending', '2025-05-18 08:00:55'),
(7, 6, 'septi', 'fshaditya@gmail.com', '2025-05-18', 1, 9000000, 'pending', '2025-05-18 08:10:24'),
(8, 6, 'septi', 'fashaafebrian@gmail.com', '2025-05-18', 1, 9000000, 'pending', '2025-05-18 08:11:09'),
(9, 6, 'septi', 'sptwn26@upi.edu', '2025-05-18', 2, 18000000, 'pending', '2025-05-18 13:55:16'),
(10, 6, 'septi', 'sptwn26@upi.edu', '2025-05-18', 2, 18000000, 'pending', '2025-05-18 14:13:47'),
(11, 6, 'septi', 'fshaditya@gmail.com', '2025-05-18', 1, 450000, 'pending', '2025-05-18 14:19:22'),
(12, 6, 'septi', 'sptwn26@upi.edu', '2025-05-19', 1, 450000, 'pending', '2025-05-19 09:44:10'),
(13, 6, 'septi', 'sptwn26@upi.edu', '2025-05-30', 2, 900000, 'pending', '2025-05-21 16:17:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `created_at`, `role`) VALUES
(0, 'nabila', 'nabilahdyna@gmail.com', '$2y$10$hgHnqvi3rNTeloKHJTxDLus5l.Bwl89Ga63/fdrR8oURCmKDdMZUy', '2025-06-15 14:08:31', 'user'),
(4, 'restu', 'fshaditya@gmail.com', '$2y$10$zwops55agJ6eV.c0cw2xIug.YkvGsZfV93X/6uvtwKnzPkTow3AfO', '2025-05-21 02:43:18', 'user'),
(6, 'Admin Timeless', 'admin@event.com', '$2y$10$eYkYZvYrFsiEyk5lgUDblOmSmfJq03J7nrR68zMduE9ZZl3JzFYea', '2025-05-21 15:20:22', 'admin'),
(7, 'mimin', 'admin@gmail.com', '$2y$10$x24qUjZEL1itR.H6/J3Dx.9ff9fqqC8eXAZI85jYj1Vqq394qISJ6', '2025-05-21 15:34:43', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_paket`
--
ALTER TABLE `list_paket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
