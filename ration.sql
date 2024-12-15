-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 08:34 AM
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
-- Database: `ration`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `time_slot` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `payment_status` varchar(40) NOT NULL,
  `rice_quantity` int(15) NOT NULL,
  `wheat_quantity` int(15) NOT NULL,
  `atta_quantity` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `shop_id`, `booking_date`, `time_slot`, `created_at`, `status`, `payment_status`, `rice_quantity`, `wheat_quantity`, `atta_quantity`) VALUES
(6, 5, 3, '2024-11-20', '11:30:00', '2024-10-20 11:40:57', 'accepted', 'cash', 2, 2, 0),
(7, 6, 3, '2024-11-20', '11:30:00', '2024-11-16 09:02:39', 'accepted', 'paid', 2, 2, 0),
(9, 8, 3, '2024-11-20', '11:30:00', '2024-11-16 09:23:17', 'pending', 'paid', 1, 1, 1),
(10, 9, 3, '2024-11-20', '11:30:00', '2024-11-16 09:23:17', 'pending', 'cash', 1, 1, 1),
(11, 10, 3, '2024-11-20', '11:30:00', '2024-11-16 09:23:55', 'pending', 'cash', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `card_type` enum('white','blue','pink','yellow') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rice` varchar(50) NOT NULL,
  `atta` varchar(50) NOT NULL,
  `wheat` varchar(50) NOT NULL,
  `rice_price` decimal(10,2) DEFAULT NULL,
  `atta_price` decimal(10,2) DEFAULT NULL,
  `wheat_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `card_type`, `created_at`, `rice`, `atta`, `wheat`, `rice_price`, `atta_price`, `wheat_price`) VALUES
(5, 'white', '2024-09-26 06:23:48', '2', '0', '2', 3.00, 0.00, 3.00),
(7, 'yellow', '2024-10-19 00:54:31', '20', '5', '15', 3.00, 5.00, 2.00),
(8, 'pink', '2024-10-19 00:57:19', '4', '2', '1', 0.00, 10.00, 0.00),
(9, 'blue', '2024-10-19 01:10:56', '2', '2', '2', 2.00, 10.00, 3.00);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`name`, `email`, `msg`) VALUES
('fwncfjenf', 'wdf@gmail.com', 'wdewdffefc'),
('efdewf', 'wdw@gmail.com', 'efewfewf'),
('Famis Noushad ', 'chalakkalration@gmail.com', 'wdewdffefc'),
('fft', 'admin@gmail.com', 'tgttyty');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `email` varchar(55) NOT NULL,
  `password` varchar(25) NOT NULL,
  `usertype` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`email`, `password`, `usertype`) VALUES
('admin@gmail.com', 'admin123', 3),
('', '', 0),
('chalakkalration@gmail.com', 'asdf', 1),
('chalakkalration@gmail.com', 'asdfg', 1),
('suresh@gmail.com', 'fyyuiigg', 1),
('admin@example.com', '$2y$10$PNOU0N2/i0C39/7b.m', 3),
('sukumaran456@gmail.com', 'SUKU123', 0),
('chalakkalration@gmail.com', 'fami1234', 0),
('sulu456@gmail.com', 'SULU@345', 0),
('chalakkalration@gmail.com', 'famis2003', 0),
('suku@gmail.com', '5tyhgg', 0),
('das123@gmail.com', '45566', 0),
('famischalakkaaal@gmail.co', 'asdf', 0),
('famischalakkaaal@gmail.com', 'asdf', 0),
('rajan@gmail.com', 'Rajan@123', 1),
('rajan@gmail.com', 'Rajan@123', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `supply_type` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shop_owner` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `pincode` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `shop_name`, `address`, `contact_number`, `created_at`, `shop_owner`, `email`, `password`, `pincode`) VALUES
(3, 'challakal', 'chalakkal ', '9879879876', '2024-09-26 09:34:40', 'swalih', 'chalakkalration@gmail.com', 'asdfg', 683105),
(4, 'surya stores', 'companypady', '6788768899', '2024-10-05 12:41:27', 'suresh', 'suresh@gmail.com', 'fyyuiigg', 683105),
(8, 'Aluva ', 'Aluva bank jn', '8547095929', '2024-10-24 14:18:27', 'Rajan', 'rajan@gmail.com', 'Rajan@123', 683102);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `name` varchar(25) NOT NULL,
  `phno` varchar(15) NOT NULL,
  `email` varchar(55) NOT NULL,
  `address` varchar(100) NOT NULL,
  `pincode` int(10) NOT NULL,
  `rcardno` varchar(30) NOT NULL,
  `password` varchar(38) NOT NULL,
  `usid` int(10) NOT NULL,
  `card_color` varchar(20) DEFAULT NULL,
  `members` int(11) DEFAULT NULL,
  `ration_card_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `phno`, `email`, `address`, `pincode`, `rcardno`, `password`, `usid`, `card_color`, `members`, `ration_card_image`) VALUES
('Famis Noushad ', '9658565256', 'famischalakkaaal@gmail.com', 'Thottathil kottapurath house', 683105, '5696585656', 'asdf', 5, 'white', 4, 'uploads/card_199913294.jpg'),
('sukumaran', '8569656595', 'sukumaran456@gmail.com', 'kalaveedu (H)', 683105, '3652148796', 'SUKU123', 6, 'white', 4, 'assets/12.jpeg'),
('sulthana', '7546525698', 'sulu456@gmail.com', 'kalapoth(H)', 683105, '3652148569', 'SULU@345', 8, 'white', 4, 'uploads/12.jpeg'),
('Famis Noushad ', '8569321456', 'chalakkalration@gmail.com', 'Thottathil kottapurath house', 683105, '6598523645', 'famis2003', 9, 'white', 3, 'uploads/12.jpeg'),
('sulthana salim', '6952413589', 'suku@gmail.com', 'gshhssgdiijjjduyhjdj', 683105, '6565231458', '5tyhgg', 10, 'white', 6, 'uploads/12.jpeg'),
('leo', '9612348579', 'das123@gmail.com', 'das and co', 683105, '5654258963', '45566', 11, 'white', 3, 'uploads/12.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `usid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
