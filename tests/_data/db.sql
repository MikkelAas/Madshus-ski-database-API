-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 21, 2021 at 12:16 PM
-- Server version: 8.0.22
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `idatg2204`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `start_date`, `end_date`) VALUES
(1, 'Bob Dylan', '2021-03-09', '2021-03-17'),
(2, 'Din Mamma', '2021-04-11', NULL),
(3, 'Jens Stoltenberg', '2021-03-01', '2021-04-30'),
(4, 'Din Pappa', '2020-12-07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `id` int NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Customer representative','Storekeeper','Production planner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `department`, `name`, `role`) VALUES
(1, 'Chess Department', 'Bobby Fischer', 'Customer representative');

-- --------------------------------------------------------

--
-- Table structure for table `franchise`
--

DROP TABLE IF EXISTS `franchise`;
CREATE TABLE `franchise` (
  `id` int NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `negotiated_buying_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `franchise`
--

INSERT INTO `franchise` (`id`, `shipping_address`, `negotiated_buying_price`) VALUES
(4, 'Teknologivegen 22, 2815 Gjøvik', 35000);

-- --------------------------------------------------------

--
-- Table structure for table `individual_store`
--

DROP TABLE IF EXISTS `individual_store`;
CREATE TABLE `individual_store` (
  `id` int NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `negotiated_buying_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `individual_store`
--

INSERT INTO `individual_store` (`id`, `shipping_address`, `negotiated_buying_price`) VALUES
(3, 'Larsgårdsvegen 2, 6009 Ålesund', 35001);

-- --------------------------------------------------------

--
-- Table structure for table `partner_stores`
--

DROP TABLE IF EXISTS `partner_stores`;
CREATE TABLE `partner_stores` (
  `id` int NOT NULL,
  `franchise_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_stores`
--

INSERT INTO `partner_stores` (`id`, `franchise_id`, `name`) VALUES
(1, 4, 'Den hemmelige butikken i kjelleren i A bygget');

-- --------------------------------------------------------

--
-- Table structure for table `produced_skis`
--

DROP TABLE IF EXISTS `produced_skis`;
CREATE TABLE `produced_skis` (
  `prod_num` int NOT NULL,
  `prod_date` date NOT NULL,
  `ski_type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produced_skis`
--

INSERT INTO `produced_skis` (`prod_num`, `prod_date`, `ski_type`) VALUES
(1, '2021-01-11', 2),
(2, '2021-01-05', 1),
(3, '2021-01-11', 1),
(4, '2020-12-21', 3),
(5, '2020-11-18', 2);

-- --------------------------------------------------------

--
-- Table structure for table `production_plan`
--

DROP TABLE IF EXISTS `production_plan`;
CREATE TABLE `production_plan` (
  `id` int NOT NULL,
  `start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_plan`
--

INSERT INTO `production_plan` (`id`, `start_date`) VALUES
(1, '2021-04-08'),
(2, '2021-04-20');

-- --------------------------------------------------------

--
-- Table structure for table `production_plan_ski`
--

DROP TABLE IF EXISTS `production_plan_ski`;
CREATE TABLE `production_plan_ski` (
  `id` int NOT NULL,
  `production_plan_id` int NOT NULL,
  `ski_type_id` int NOT NULL,
  `daily_amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_plan_ski`
--

INSERT INTO `production_plan_ski` (`id`, `production_plan_id`, `ski_type_id`, `daily_amount`) VALUES
(1, 1, 1, 200),
(2, 1, 3, 20),
(3, 2, 2, 400),
(4, 2, 2, 500);

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

DROP TABLE IF EXISTS `shipment`;
CREATE TABLE `shipment` (
  `shipment_num` int NOT NULL,
  `store_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sched_pickup_date` date DEFAULT NULL,
  `driver_id` int DEFAULT NULL,
  `transport_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` enum('not ready','ready','picked up') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`shipment_num`, `store_name`, `shipping_address`, `sched_pickup_date`, `driver_id`, `transport_company`, `state`) VALUES
(1, 'Den store skibutikken i Texas', 'Texas ', '2021-04-06', 7, 'Shipped shipments shipping company inc', 'ready'),
(2, 'Den lille skibutikken i Tyksland', 'Berlin', '2021-04-29', 2, 'Shipped shipments shipping company inc', 'ready');

-- --------------------------------------------------------

--
-- Table structure for table `shipment_orders`
--

DROP TABLE IF EXISTS `shipment_orders`;
CREATE TABLE `shipment_orders` (
  `id` int NOT NULL,
  `shipment_num` int NOT NULL,
  `order_num` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipment_orders`
--

INSERT INTO `shipment_orders` (`id`, `shipment_num`, `order_num`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `ski_order`
--

DROP TABLE IF EXISTS `ski_order`;
CREATE TABLE `ski_order` (
  `order_number` int NOT NULL,
  `total_price` int NOT NULL,
  `reference_to_larger_order` int DEFAULT NULL,
  `customer_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ski_order`
--

INSERT INTO `ski_order` (`order_number`, `total_price`, `reference_to_larger_order`, `customer_id`) VALUES
(1, 100, NULL, 1),
(4, 300, NULL, 1),
(10, 10000, NULL, 1),
(13, 1000, NULL, 1),
(37, 999, NULL, 1),
(38, 999, NULL, 1),
(39, 999, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ski_order_ski_type`
--

DROP TABLE IF EXISTS `ski_order_ski_type`;
CREATE TABLE `ski_order_ski_type` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `ski_type_id` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ski_order_ski_type`
--

INSERT INTO `ski_order_ski_type` (`id`, `order_id`, `ski_type_id`, `quantity`) VALUES
(11, 1, 1, 4),
(12, 4, 3, 200),
(13, 10, 2, 5001),
(14, 13, 1, 55),
(15, 13, 1, 100),
(16, 13, 2, 500),
(17, 37, 1, 12345),
(18, 38, 1, 12345),
(19, 39, 1, 12345);

-- --------------------------------------------------------

--
-- Table structure for table `ski_order_state_history`
--

DROP TABLE IF EXISTS `ski_order_state_history`;
CREATE TABLE `ski_order_state_history` (
  `id` int NOT NULL,
  `ski_order_id` int NOT NULL,
  `employee_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `state` enum('new','open','skis available','ready to be shipped') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ski_order_state_history`
--

INSERT INTO `ski_order_state_history` (`id`, `ski_order_id`, `employee_id`, `date`, `state`) VALUES
(38, 1, 1, '2021-04-15', 'new'),
(39, 4, NULL, '2021-04-05', 'new'),
(40, 10, 1, '2021-03-16', 'new'),
(41, 13, NULL, '2021-03-09', 'new'),
(42, 37, NULL, '2021-04-08', 'new'),
(43, 38, NULL, '2021-04-08', 'new'),
(44, 39, NULL, '2021-04-08', 'new');

-- --------------------------------------------------------

--
-- Table structure for table `ski_type`
--

DROP TABLE IF EXISTS `ski_type`;
CREATE TABLE `ski_type` (
  `id` int NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temperature` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grip_system` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `weight_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `historical` tinyint(1) NOT NULL,
  `url` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `msrp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ski_type`
--

INSERT INTO `ski_type` (`id`, `model`, `type`, `temperature`, `grip_system`, `size`, `weight_class`, `description`, `historical`, `url`, `msrp`) VALUES
(1, 'Fast', 'Speed ski', 'Hot', 'Grippy', 9000, 'THICK', 'nyoom', 0, 'https://karlsmoen.no/dog.jpg', 69420),
(2, 'Fast', 'Speeder ski', 'Hotter', 'Grippier', 9001, 'THICKER', 'nyoomer', 1, 'https://karlsmoen.no/dog.jpg', 69421),
(3, 'Fastest', 'Speedest ski', 'Hottest', 'Grippier', 9002, 'THICKEST', 'nyoomest', 0, 'https://karlsmoen.no/dog.jpg', 69422);

-- --------------------------------------------------------

--
-- Table structure for table `team_skier`
--

DROP TABLE IF EXISTS `team_skier`;
CREATE TABLE `team_skier` (
  `id` int NOT NULL,
  `dob` date NOT NULL,
  `club` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numer_of_skis_pr_year` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_skier`
--

INSERT INTO `team_skier` (`id`, `dob`, `club`, `numer_of_skis_pr_year`) VALUES
(2, '2021-04-07', 'Famileklubben', 50);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `franchise`
--
ALTER TABLE `franchise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `individual_store`
--
ALTER TABLE `individual_store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partner_stores`
--
ALTER TABLE `partner_stores`
  ADD PRIMARY KEY (`id`,`franchise_id`),
  ADD KEY `franchise_franchise_id_constraint` (`franchise_id`);

--
-- Indexes for table `produced_skis`
--
ALTER TABLE `produced_skis`
  ADD PRIMARY KEY (`prod_num`),
  ADD KEY `ski_type_ski_constraint` (`ski_type`);

--
-- Indexes for table `production_plan`
--
ALTER TABLE `production_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_plan_ski`
--
ALTER TABLE `production_plan_ski`
  ADD PRIMARY KEY (`id`,`production_plan_id`,`ski_type_id`),
  ADD KEY `production_plan_production_plan_ski_constraint` (`production_plan_id`),
  ADD KEY `production_plan_ski_type_constraint` (`ski_type_id`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`shipment_num`);

--
-- Indexes for table `shipment_orders`
--
ALTER TABLE `shipment_orders`
  ADD PRIMARY KEY (`id`,`shipment_num`,`order_num`),
  ADD KEY `shipment_orders_shipment_constraint` (`shipment_num`),
  ADD KEY `shipment_orders_ski_order_constraint` (`order_num`);

--
-- Indexes for table `ski_order`
--
ALTER TABLE `ski_order`
  ADD PRIMARY KEY (`order_number`),
  ADD KEY `ski_order_order_constraint` (`reference_to_larger_order`),
  ADD KEY `ski_order_customer_constraint` (`customer_id`);

--
-- Indexes for table `ski_order_ski_type`
--
ALTER TABLE `ski_order_ski_type`
  ADD PRIMARY KEY (`id`,`order_id`,`ski_type_id`),
  ADD KEY `ski_type_order_ski_type_constraint` (`ski_type_id`),
  ADD KEY `order_order_ski_type_constraint` (`order_id`);

--
-- Indexes for table `ski_order_state_history`
--
ALTER TABLE `ski_order_state_history`
  ADD PRIMARY KEY (`id`,`ski_order_id`),
  ADD KEY `ski_order_ski_order_state_history_constriant` (`ski_order_id`),
  ADD KEY `ski_order_state_history_employee_constraint` (`employee_id`);

--
-- Indexes for table `ski_type`
--
ALTER TABLE `ski_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_skier`
--
ALTER TABLE `team_skier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `partner_stores`
--
ALTER TABLE `partner_stores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produced_skis`
--
ALTER TABLE `produced_skis`
  MODIFY `prod_num` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `production_plan`
--
ALTER TABLE `production_plan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `production_plan_ski`
--
ALTER TABLE `production_plan_ski`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipment_num` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shipment_orders`
--
ALTER TABLE `shipment_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ski_order`
--
ALTER TABLE `ski_order`
  MODIFY `order_number` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `ski_order_ski_type`
--
ALTER TABLE `ski_order_ski_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ski_order_state_history`
--
ALTER TABLE `ski_order_state_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `ski_type`
--
ALTER TABLE `ski_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `franchise`
--
ALTER TABLE `franchise`
  ADD CONSTRAINT `customer_franchise_constraint` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `individual_store`
--
ALTER TABLE `individual_store`
  ADD CONSTRAINT `customer_individual_store_constraint` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `partner_stores`
--
ALTER TABLE `partner_stores`
  ADD CONSTRAINT `franchise_franchise_id_constraint` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `produced_skis`
--
ALTER TABLE `produced_skis`
  ADD CONSTRAINT `ski_type_ski_constraint` FOREIGN KEY (`ski_type`) REFERENCES `ski_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `production_plan_ski`
--
ALTER TABLE `production_plan_ski`
  ADD CONSTRAINT `production_plan_production_plan_ski_constraint` FOREIGN KEY (`production_plan_id`) REFERENCES `production_plan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `production_plan_ski_type_constraint` FOREIGN KEY (`ski_type_id`) REFERENCES `ski_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `shipment_orders`
--
ALTER TABLE `shipment_orders`
  ADD CONSTRAINT `shipment_orders_shipment_constraint` FOREIGN KEY (`shipment_num`) REFERENCES `shipment` (`shipment_num`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `shipment_orders_ski_order_constraint` FOREIGN KEY (`order_num`) REFERENCES `ski_order` (`order_number`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `ski_order`
--
ALTER TABLE `ski_order`
  ADD CONSTRAINT `ski_order_customer_constraint` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ski_order_order_constraint` FOREIGN KEY (`reference_to_larger_order`) REFERENCES `ski_order` (`order_number`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `ski_order_ski_type`
--
ALTER TABLE `ski_order_ski_type`
  ADD CONSTRAINT `order_order_ski_type_constraint` FOREIGN KEY (`order_id`) REFERENCES `ski_order` (`order_number`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ski_type_order_ski_type_constraint` FOREIGN KEY (`ski_type_id`) REFERENCES `ski_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `ski_order_state_history`
--
ALTER TABLE `ski_order_state_history`
  ADD CONSTRAINT `ski_order_ski_order_state_history_constriant` FOREIGN KEY (`ski_order_id`) REFERENCES `ski_order` (`order_number`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ski_order_state_history_employee_constraint` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `team_skier`
--
ALTER TABLE `team_skier`
  ADD CONSTRAINT `customer_team_skier_constraint` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
