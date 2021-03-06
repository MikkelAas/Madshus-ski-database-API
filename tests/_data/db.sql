-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: May 20, 2021 at 11:43 AM
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
-- Table structure for table `access_token`
--

DROP TABLE IF EXISTS `access_token`;
CREATE TABLE `access_token` (
                                `token` varchar(50) NOT NULL,
                                `company_access` tinyint(1) NOT NULL,
                                `customer_access` tinyint(1) NOT NULL,
                                `transporter_access` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `access_token`
--

INSERT INTO `access_token` (`token`, `company_access`, `customer_access`, `transporter_access`) VALUES
('test-token', 1, 1, 1),
('useless-token', 0, 0, 0),
('transport-token', 0, 0, 1),
('customer-token', 0, 1, 0),
('company-token', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
                            `id` int NOT NULL,
                            `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
                            `department` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                            `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
                             `shipping_address` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                             `negotiated_buying_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `franchise`
--

INSERT INTO `franchise` (`id`, `shipping_address`, `negotiated_buying_price`) VALUES
(4, 'Teknologivegen 22, 2815 Gj??vik', 35000);

-- --------------------------------------------------------

--
-- Table structure for table `individual_store`
--

DROP TABLE IF EXISTS `individual_store`;
CREATE TABLE `individual_store` (
                                    `id` int NOT NULL,
                                    `shipping_address` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                                    `negotiated_buying_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `individual_store`
--

INSERT INTO `individual_store` (`id`, `shipping_address`, `negotiated_buying_price`) VALUES
(3, 'Larsg??rdsvegen 2, 6009 ??lesund', 35001);

-- --------------------------------------------------------

--
-- Table structure for table `partner_stores`
--

DROP TABLE IF EXISTS `partner_stores`;
CREATE TABLE `partner_stores` (
                                  `id` int NOT NULL,
                                  `franchise_id` int NOT NULL,
                                  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
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
                                 `ski_type` int NOT NULL,
                                 `order_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produced_skis`
--

INSERT INTO `produced_skis` (`prod_num`, `prod_date`, `ski_type`, `order_id`) VALUES
(2, '2021-01-05', 1, 10),
(3, '2021-01-11', 1, 1),
(4, '2020-12-21', 3, 10),
(6, '2021-04-26', 1, 1),
(7, '2021-04-20', 1, 1),
(8, '2021-04-05', 1, 1),
(9, '2020-08-07', 1, 13),
(10, '2021-05-03', 1, 13),
(11, '2021-05-12', 1, 13),
(12, '2021-05-11', 2, 13),
(13, '2021-05-11', 1, 13),
(14, '2021-05-09', 2, 13),
(15, '2021-05-04', 1, 13),
(16, '2021-05-02', 2, 13),
(17, '2020-08-07', 1, NULL),
(18, '2020-08-07', 1, NULL),
(19, '2020-08-07', 1, NULL),
(20, '2020-08-07', 1, NULL),
(21, '2020-08-07', 1, NULL),
(22, '2020-08-07', 1, NULL),
(23, '2020-08-07', 1, NULL);

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
(2, '2021-04-20'),
(8, '2020-12-20'),
(16, '2020-04-07'),
(23, '2020-04-07');

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
(4, 2, 2, 500),
(17, 16, 1, 69),
(18, 16, 2, 96),
(19, 23, 1, 69),
(20, 23, 2, 96);

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
                            `state` enum('not ready','ready','picked up') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`shipment_num`, `store_name`, `shipping_address`, `sched_pickup_date`, `driver_id`, `transport_company`, `state`) VALUES
(1, 'Den store skibutikken i Texas', 'Texas ', '2021-04-06', 7, 'Shipped shipments shipping company inc', 'ready'),
(2, 'Den lille skibutikken i Tyksland', 'Berlin', '2021-04-29', 2, 'Shipped shipments shipping company inc', 'ready'),
(6, NULL, NULL, NULL, NULL, NULL, 'not ready');

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
(3, 2, 10),
(12, 6, 1),
(13, 6, 4);

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
(40, 0, 13, 1),
(41, 0, 13, 1),
(42, 0, 13, 1),
(43, 0, 42, 1);

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
(15, 13, 1, 5),
(16, 13, 2, 3),
(17, 37, 1, 12345),
(18, 38, 1, 12345),
(21, 40, 1, 95),
(22, 40, 2, 497),
(23, 40, 3, 1000),
(29, 43, 3, 1000);

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
(45, 1, NULL, '2021-05-12', 'new'),
(46, 1, NULL, '2021-05-12', 'skis available'),
(49, 1, NULL, '2021-05-12', 'skis available'),
(50, 1, NULL, '2021-05-13', 'skis available'),
(51, 10, NULL, '2021-05-13', 'skis available'),
(53, 10, NULL, '2021-05-13', 'skis available'),
(54, 40, NULL, '2021-05-13', 'new'),
(55, 41, NULL, '2021-05-13', 'new'),
(56, 42, NULL, '2021-05-13', 'new'),
(57, 43, NULL, '2021-05-13', 'new');

-- --------------------------------------------------------

--
-- Stand-in structure for view `ski_order_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `ski_order_view`;
CREATE TABLE `ski_order_view` (
                                  `order_number` int
    ,`total_price` int
    ,`reference_to_larger_order` int
    ,`customer_id` int
    ,`id` int
    ,`order_id` int
    ,`ski_type_id` int
    ,`quantity` int
);

-- --------------------------------------------------------

--
-- Table structure for table `ski_type`
--

DROP TABLE IF EXISTS `ski_type`;
CREATE TABLE `ski_type` (
                            `id` int NOT NULL,
                            `model` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                            `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                            `temperature` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                            `grip_system` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                            `size` int NOT NULL,
                            `weight_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                            `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                            `historical` tinyint(1) NOT NULL,
                            `url` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
                              `club` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                              `numer_of_skis_pr_year` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_skier`
--

INSERT INTO `team_skier` (`id`, `dob`, `club`, `numer_of_skis_pr_year`) VALUES
(2, '2021-04-07', 'Famileklubben', 50);

-- --------------------------------------------------------

--
-- Stand-in structure for view `whatever`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `whatever`;
CREATE TABLE `whatever` (
                            `order_number` int
    ,`total_price` int
    ,`reference_to_larger_order` int
    ,`customer_id` int
);

-- --------------------------------------------------------

--
-- Structure for view `ski_order_view`
--
DROP TABLE IF EXISTS `ski_order_view`;

DROP VIEW IF EXISTS `ski_order_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`idatg2204`@`%` SQL SECURITY DEFINER VIEW `ski_order_view`  AS SELECT `ski_order`.`order_number` AS `order_number`, `ski_order`.`total_price` AS `total_price`, `ski_order`.`reference_to_larger_order` AS `reference_to_larger_order`, `ski_order`.`customer_id` AS `customer_id`, `ski_order_ski_type`.`id` AS `id`, `ski_order_ski_type`.`order_id` AS `order_id`, `ski_order_ski_type`.`ski_type_id` AS `ski_type_id`, `ski_order_ski_type`.`quantity` AS `quantity` FROM (`ski_order` join `ski_order_ski_type` on((`ski_order`.`order_number` = `ski_order_ski_type`.`order_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `whatever`
--
DROP TABLE IF EXISTS `whatever`;

DROP VIEW IF EXISTS `whatever`;
CREATE ALGORITHM=UNDEFINED DEFINER=`idatg2204`@`%` SQL SECURITY DEFINER VIEW `whatever`  AS SELECT `ski_order`.`order_number` AS `order_number`, `ski_order`.`total_price` AS `total_price`, `ski_order`.`reference_to_larger_order` AS `reference_to_larger_order`, `ski_order`.`customer_id` AS `customer_id` FROM `ski_order` ;

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
  ADD KEY `ski_type_ski_constraint` (`ski_type`),
  ADD KEY `produced_skis_order_id_ski_order_order_number` (`order_id`);

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
    MODIFY `prod_num` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `production_plan`
--
ALTER TABLE `production_plan`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `production_plan_ski`
--
ALTER TABLE `production_plan_ski`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
    MODIFY `shipment_num` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shipment_orders`
--
ALTER TABLE `shipment_orders`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ski_order`
--
ALTER TABLE `ski_order`
    MODIFY `order_number` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `ski_order_ski_type`
--
ALTER TABLE `ski_order_ski_type`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ski_order_state_history`
--
ALTER TABLE `ski_order_state_history`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

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
    ADD CONSTRAINT `customer_franchise_constraint` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `individual_store`
--
ALTER TABLE `individual_store`
    ADD CONSTRAINT `customer_individual_store_constraint` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `partner_stores`
--
ALTER TABLE `partner_stores`
    ADD CONSTRAINT `franchise_franchise_id_constraint` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produced_skis`
--
ALTER TABLE `produced_skis`
    ADD CONSTRAINT `produced_skis_order_id_ski_order_order_number` FOREIGN KEY (`order_id`) REFERENCES `ski_order` (`order_number`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ski_type_ski_constraint` FOREIGN KEY (`ski_type`) REFERENCES `ski_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `production_plan_ski`
--
ALTER TABLE `production_plan_ski`
    ADD CONSTRAINT `production_plan_production_plan_ski_constraint` FOREIGN KEY (`production_plan_id`) REFERENCES `production_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `production_plan_ski_type_constraint` FOREIGN KEY (`ski_type_id`) REFERENCES `ski_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipment_orders`
--
ALTER TABLE `shipment_orders`
    ADD CONSTRAINT `shipment_orders_shipment_constraint` FOREIGN KEY (`shipment_num`) REFERENCES `shipment` (`shipment_num`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shipment_orders_ski_order_constraint` FOREIGN KEY (`order_num`) REFERENCES `ski_order` (`order_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ski_order`
--
ALTER TABLE `ski_order`
    ADD CONSTRAINT `ski_order_customer_constraint` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ski_order_order_constraint` FOREIGN KEY (`reference_to_larger_order`) REFERENCES `ski_order` (`order_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ski_order_ski_type`
--
ALTER TABLE `ski_order_ski_type`
    ADD CONSTRAINT `order_order_ski_type_constraint` FOREIGN KEY (`order_id`) REFERENCES `ski_order` (`order_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ski_type_order_ski_type_constraint` FOREIGN KEY (`ski_type_id`) REFERENCES `ski_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ski_order_state_history`
--
ALTER TABLE `ski_order_state_history`
    ADD CONSTRAINT `ski_order_ski_order_state_history_constriant` FOREIGN KEY (`ski_order_id`) REFERENCES `ski_order` (`order_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ski_order_state_history_employee_constraint` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `team_skier`
--
ALTER TABLE `team_skier`
    ADD CONSTRAINT `customer_team_skier_constraint` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
