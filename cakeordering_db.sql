-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 08:00 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cakeordering_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblexpenses`
--

CREATE TABLE `tblexpenses` (
  `expense_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Emailid` varchar(100) NOT NULL,
  `Contact` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`Id`, `Name`, `Password`, `Emailid`, `Contact`) VALUES
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@cake.com', '0300000000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cakes`
--

CREATE TABLE `tbl_cakes` (
  `cakeId` int(11) NOT NULL,
  `cakeName` varchar(100) NOT NULL,
  `cakePrice` decimal(10,2) NOT NULL,
  `cakePicture` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_cakes`
--

INSERT INTO `tbl_cakes` (`cakeId`, `cakeName`, `cakePrice`, `cakePicture`, `category_id`, `description`) VALUES
(1, 'wedding cake', '10000.00', '1_1760973742_wedding.jpg', 3, 'a fully decorated wedding cake is here to serve you with love and happiness.'),
(2, 'Red Velvet Cake', '2800.00', '2_1760973718_RED-VELVET-CAKE-23-S-01.jpg', 8, 'Soft, velvety layers with a hint of cocoa and classic cream cheese frosting, perfect for celebrations.'),
(3, 'Pineapple Cream Cake', '2200.00', '3_1760973692_front-view-delicious-cake-with-yellow-syrup-red-strawberries-blue-desk.jpg', 6, 'Light sponge cake layered with fresh pineapple chunks and whipped cream, loved for its tropical taste.'),
(4, 'Coffee Walnut Cake', '2600.00', '4_1760973673_cup-cappuccino-with-piece-cake.jpg', 8, 'Delicious coffee-flavored cake with crunchy walnut pieces, a popular choice for tea time.'),
(5, 'Mango Delight Cake', '2400.00', '5_1760973628_yummy-cake-table.jpg', 7, 'Soft vanilla sponge layered with mango puree and cream, a summer favorite across Pakistan.'),
(7, 'Bounty Chocolate Cake', '2700.00', '7_1760973607_chocolate-cake-surrounded-by-chocolate-truffles-bonbons.jpg', 5, 'Chocolate and coconut fusion cake, inspired by the famous Bounty bar, moist and indulgent.'),
(8, 'KitKat Crunch Cake', '3000.00', '8_1760973588_dark-cutting-board-sweet-cake-star-anise.jpg', 7, 'Loaded with KitKat bars and chocolate ganache, a modern favorite among youngsters.'),
(9, 'Strawberry Cream Cake', '2200.00', '9_1760973571_zoom-view-little-round-cake-decorated-with-strawberries-white-plate.jpg', 6, 'Fresh strawberry layers with soft sponge and light cream frosting, perfect for birthdays.'),
(10, 'Nutella Hazelnut Cake', '3200.00', '10_1760973550_high-angle-cake-plate-with-pine-cone.jpg', 8, 'Rich chocolate sponge filled with Nutella and roasted hazelnuts, a heavenly treat for chocolate lovers.'),
(11, 'Chocolate Fudge Cake', '2500.00', '11_1760973518_sliced-tasty-chocolate-brownie-with-cream-dark-plate-high-quality-photo.jpg', 5, 'Rich and moist chocolate cake topped with creamy fudge frosting, a favorite in Pakistani bakeries.'),
(22, 'Black Forest Cake', '2300.00', '22_1760973496_dark-chocolate-cake-topped-with-pomegranate-seeds.jpg', 6, 'Classic German-inspired chocolate cake with cherries and whipped cream, found in every Pakistani bakery.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cake_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`id`, `category_name`) VALUES
(3, 'Wedding Cake'),
(4, 'Birthday Cakes'),
(5, 'Chocolate Cakes'),
(6, 'Cream Cakes'),
(7, 'Fruit Cakes'),
(8, 'Special Edition Cakes'),
(11, 'Seo Expert');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_custom_requests`
--

CREATE TABLE `tbl_custom_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cake_title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `design_preference` varchar(255) DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `flavor` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `budget` decimal(10,2) DEFAULT NULL,
  `occasion` varchar(100) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Reviewed','Accepted','Modified','Rejected') DEFAULT 'Pending',
  `admin_feedback` text DEFAULT NULL,
  `request_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_custom_requests`
--

INSERT INTO `tbl_custom_requests` (`request_id`, `user_id`, `cake_title`, `description`, `design_preference`, `ingredients`, `flavor`, `quantity`, `budget`, `occasion`, `delivery_date`, `image`, `status`, `admin_feedback`, `request_date`) VALUES
(3, 1, 'Galaxy Theme Birthday Cake', '', NULL, NULL, 'Chocolate Fudge', 1, NULL, 'Birthday', '2025-10-25', 'galaxy_cake.jpg', 'Pending', 'Your galaxy cake design is approved! We will add shimmer stars and a fondant planet ring. Delivery will be made at 5 PM sharp.', '2025-10-20 08:03:03'),
(4, 1, 'Traditional Wedding Layer Cake', '', NULL, NULL, 'Vanilla Almond', 1, NULL, 'Wedding', '2025-11-02', 'wedding_layer_cake.png', 'Reviewed', 'Awaiting confirmation for flower color preference before final approval.', '2025-10-20 08:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses`
--

CREATE TABLE `tbl_expenses` (
  `id` int(11) NOT NULL,
  `expense_title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date DEFAULT curdate(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_expenses`
--

INSERT INTO `tbl_expenses` (`id`, `expense_title`, `amount`, `date`, `description`) VALUES
(3, '2025 Expenses', '90000.00', '2025-10-20', 'All available Cakes expenses total calculated');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` enum('Pending','Processing','Confirmed','Dispatched','Delivered','Rejected') DEFAULT 'Pending',
  `voucher` varchar(255) DEFAULT NULL,
  `dispatch_time` datetime DEFAULT NULL,
  `estimated_minutes` int(11) DEFAULT NULL,
  `reject_reason` text DEFAULT NULL,
  `expected_delivery_time` datetime DEFAULT NULL,
  `action_taken` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`order_id`, `user_id`, `customer_name`, `address`, `phone`, `total_price`, `order_date`, `status`, `voucher`, `dispatch_time`, `estimated_minutes`, `reject_reason`, `expected_delivery_time`, `action_taken`) VALUES
(5, 1, 'Diya Shezadi', 'PWD Mareee Road isb', '0301540000', '90300.00', '2025-10-20 15:41:48', 'Confirmed', '1760967812_education-internet-people-concept-female-redhead-digital-nomad-freelance-girl-glasses-finishe.jpg', '2025-10-20 16:24:30', 1, NULL, '2025-10-20 16:45:15', 'Dispatched'),
(6, 1, 'Diya Shezadi', 'PWD Mareee Road', '03015401809', '20300.00', '2025-10-20 06:54:45', 'Confirmed', NULL, '2025-10-20 16:29:45', 2, NULL, '2025-10-20 07:30:00', NULL),
(7, 1, 'Diya Shezadi', 'PWD Mareee Road', '03015401809', '60300.00', '2025-10-20 16:39:08', 'Processing', '1760971355_education-internet-people-concept-female-redhead-digital-nomad-freelance-girl-glasses-finishe.jpg', '2025-10-20 16:40:57', NULL, NULL, '2025-10-20 08:41:00', NULL),
(8, 1, 'Diya Shezadi', 'PWD Mareee Road', '03015401809', '15300.00', '2025-10-20 19:14:39', 'Confirmed', '1760980503_zoom-view-little-round-cake-decorated-with-strawberries-white-plate.jpg', '2025-10-20 19:16:41', NULL, NULL, '2025-10-20 10:16:00', NULL),
(9, 1, 'Diya Shezadi', 'PWD Mareee Road', '03015401809', '5200.00', '2025-10-20 19:37:11', 'Delivered', '1760981859_RED-VELVET-CAKE-23-S-01.jpg', '2025-10-20 19:38:42', NULL, NULL, '2025-10-20 10:39:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_items`
--

CREATE TABLE `tbl_order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `cake_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_order_items`
--

INSERT INTO `tbl_order_items` (`id`, `order_id`, `cake_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, '10000.00'),
(2, 1, 6, 1, '50000.00'),
(3, 4, 1, 1, '10000.00'),
(4, 5, 1, 1, '10000.00'),
(5, 6, 1, 1, '10000.00'),
(6, 6, 6, 1, '50000.00'),
(7, 1, 1, 1, '10000.00'),
(8, 1, 6, 1, '50000.00'),
(9, 2, 1, 2, '10000.00'),
(10, 5, 1, 4, '10000.00'),
(11, 5, 6, 1, '50000.00'),
(12, 7, 1, 1, '10000.00'),
(13, 7, 6, 1, '50000.00'),
(14, 8, 1, 1, '10000.00'),
(15, 8, 2, 1, '2800.00'),
(16, 8, 3, 1, '2200.00'),
(17, 9, 3, 1, '2200.00'),
(18, 9, 7, 1, '2700.00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `User_id` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Contact` varchar(100) DEFAULT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`User_id`, `UserName`, `Password`, `Email`, `Contact`, `Address`) VALUES
(1, 'user', '$2y$10$7WPekxvU0.1I.aljrjPEYum9Uk8W8xoA9HbWd4IyZENtxKZGwPdPm', 'user@gmail.com', '03015400000', 'Islamabad, Punjab');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblexpenses`
--
ALTER TABLE `tblexpenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Emailid` (`Emailid`);

--
-- Indexes for table `tbl_cakes`
--
ALTER TABLE `tbl_cakes`
  ADD PRIMARY KEY (`cakeId`);

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`cake_id`);

--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_custom_requests`
--
ALTER TABLE `tbl_custom_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `tbl_custom_requests_ibfk_1` (`user_id`);

--
-- Indexes for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblexpenses`
--
ALTER TABLE `tblexpenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_custom_requests`
--
ALTER TABLE `tbl_custom_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_custom_requests`
--
ALTER TABLE `tbl_custom_requests`
  ADD CONSTRAINT `tbl_custom_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`User_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
