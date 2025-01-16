-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 03:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundry_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(30) NOT NULL,
  `supply_id` int(30) NOT NULL,
  `qty` int(30) NOT NULL,
  `used` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `supply_id`, `qty`, `used`, `date_created`, `date_updated`) VALUES
(14, 1, 273, 50, '2024-12-14 11:44:57', '2024-12-16 10:15:31'),
(17, 12, 74, 3, '2024-12-16 09:20:33', '2025-01-15 20:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `laundry_categories`
--

CREATE TABLE `laundry_categories` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laundry_categories`
--

INSERT INTO `laundry_categories` (`id`, `name`, `price`) VALUES
(1, 'Bed Sheets', 30),
(3, 'Clothes', 25);

-- --------------------------------------------------------

--
-- Table structure for table `laundry_items`
--

CREATE TABLE `laundry_items` (
  `id` int(30) NOT NULL,
  `laundry_category_id` int(30) NOT NULL,
  `weight` double NOT NULL,
  `supply_list_id` int(11) NOT NULL,
  `product_price` double NOT NULL,
  `laundry_id` int(30) NOT NULL,
  `unit_price` double NOT NULL,
  `amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laundry_items`
--

INSERT INTO `laundry_items` (`id`, `laundry_category_id`, `weight`, `supply_list_id`, `product_price`, `laundry_id`, `unit_price`, `amount`) VALUES
(17, 3, 1, 1, 3.12, 21, 25, 28.12),
(18, 1, 1, 1, 3.12, 21, 30, 33.12),
(20, 1, 1, 12, 6.6, 25, 30, 36.6),
(21, 3, 1, 1, 3.12, 25, 25, 28.12);

-- --------------------------------------------------------

--
-- Table structure for table `laundry_list`
--

CREATE TABLE `laundry_list` (
  `id` int(30) NOT NULL,
  `customer_name` text NOT NULL,
  `phone` varchar(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Pending, 1 = ongoing,2= ready,3= claimed',
  `queue` int(30) NOT NULL,
  `total_amount` double NOT NULL,
  `pay_status` tinyint(1) DEFAULT 0,
  `amount_tendered` double NOT NULL,
  `amount_change` double NOT NULL,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laundry_list`
--

INSERT INTO `laundry_list` (`id`, `customer_name`, `phone`, `status`, `queue`, `total_amount`, `pay_status`, `amount_tendered`, `amount_change`, `remarks`, `date_created`) VALUES
(21, 'sadsdas', '21321321', 0, 2, 61.239999999999995, 0, 0, -61.24, '', '2025-01-16 10:06:03'),
(25, '21313', '1213213213', 0, 3, 64.72, 0, 0, -64.72, '', '2025-01-16 10:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `supply_list`
--

CREATE TABLE `supply_list` (
  `id` int(30) NOT NULL,
  `brand` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `classification` varchar(100) NOT NULL,
  `size` varchar(120) NOT NULL,
  `price` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supply_list`
--

INSERT INTO `supply_list` (`id`, `brand`, `category`, `classification`, `size`, `price`) VALUES
(1, 'Downy', 'Alkaline Builder Detergent', 'Fragrance', '20 L', 120),
(10, 'Tide', 'Laundry Soaps', 'Cleaning Agent', '20 L', 530),
(12, 'Pride', 'Laundry Soaps', 'Cleaning Agent', '20 L', 3600);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=admin , 2 = staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(1, 'Administrator', 'admin', 'admin123', 1),
(4, 'John Smith', 'jsmith', 'admin123', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laundry_categories`
--
ALTER TABLE `laundry_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laundry_items`
--
ALTER TABLE `laundry_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laundry_list`
--
ALTER TABLE `laundry_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supply_list`
--
ALTER TABLE `supply_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `laundry_categories`
--
ALTER TABLE `laundry_categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `laundry_items`
--
ALTER TABLE `laundry_items`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `laundry_list`
--
ALTER TABLE `laundry_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `supply_list`
--
ALTER TABLE `supply_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
