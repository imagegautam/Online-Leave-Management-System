-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2024 at 02:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leave_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `comp_off_leaves`
--

CREATE TABLE `comp_off_leaves` (
  `comp_off_leaves_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `number_of_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comp_off_leaves`
--

INSERT INTO `comp_off_leaves` (`comp_off_leaves_id`, `staff_id`, `date_from`, `date_to`, `number_of_days`) VALUES
(15, 76, '2024-09-15', '2024-09-17', 3);

-- --------------------------------------------------------

--
-- Table structure for table `comp_off_leave_applications`
--

CREATE TABLE `comp_off_leave_applications` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `leave_date` date NOT NULL,
  `number_of_days` int(11) NOT NULL,
  `status` enum('Pending','Approved','Denied') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comp_off_leave_applications`
--

INSERT INTO `comp_off_leave_applications` (`id`, `staff_id`, `leave_date`, `number_of_days`, `status`) VALUES
(9, 76, '2024-09-25', 1, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `leave_applications`
--

CREATE TABLE `leave_applications` (
  `app_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `leave_type` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reason` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `leave_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_applications`
--

INSERT INTO `leave_applications` (`app_id`, `staff_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `leave_type_id`) VALUES
(49, 76, 'Casual Leave', '2024-09-20', '2024-09-22', 'Personal', 'Approved', 1),
(50, 76, 'Medical Leave', '2024-09-27', '2024-09-27', 'Emergency', 'Approved', 2);

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `leave_id` int(11) NOT NULL,
  `leave_category` varchar(50) NOT NULL,
  `available_leaves` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`leave_id`, `leave_category`, `available_leaves`) VALUES
(1, 'Casual Leave', 12),
(2, 'Medical Leave', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `reset_token_hash` varchar(100) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(22, 'admin', 'admin@gmail.com', 'HMpS2W6zVllj65IvRSleOg==', 'admin', NULL, NULL),
(76, 'Joes', 'prasanthp0955@gmail.com', 'SyIw5hurxEHzdOrVAaspgQ==', 'staff', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comp_off_leaves`
--
ALTER TABLE `comp_off_leaves`
  ADD PRIMARY KEY (`comp_off_leaves_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `comp_off_leave_applications`
--
ALTER TABLE `comp_off_leave_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `leave_applications`
--
ALTER TABLE `leave_applications`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `fk_leave_id` (`leave_type_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comp_off_leaves`
--
ALTER TABLE `comp_off_leaves`
  MODIFY `comp_off_leaves_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `comp_off_leave_applications`
--
ALTER TABLE `comp_off_leave_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `leave_applications`
--
ALTER TABLE `leave_applications`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comp_off_leaves`
--
ALTER TABLE `comp_off_leaves`
  ADD CONSTRAINT `comp_off_leaves_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `comp_off_leave_applications`
--
ALTER TABLE `comp_off_leave_applications`
  ADD CONSTRAINT `comp_off_leave_applications_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `leave_applications`
--
ALTER TABLE `leave_applications`
  ADD CONSTRAINT `fk_leave_id` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`leave_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leave_applications_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
