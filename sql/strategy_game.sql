-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 12, 2016 at 02:39 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `strategy_game`
--
CREATE DATABASE IF NOT EXISTS `strategy_game` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `strategy_game`;

-- --------------------------------------------------------

--
-- Table structure for table `army_purchase_log`
--

CREATE TABLE IF NOT EXISTS `army_purchase_log` (
  `army_purchase_log` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `move_number` int(11) NOT NULL,
  `army_wanted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attack_log`
--

CREATE TABLE IF NOT EXISTS `attack_log` (
  `attack_log_id` int(11) NOT NULL,
  `move_number` int(5) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `army_enroute` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trade_log`
--

CREATE TABLE IF NOT EXISTS `trade_log` (
  `trade_log_id` int(11) NOT NULL,
  `move_number` int(5) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `army_offered` int(5) NOT NULL,
  `money_offered` int(5) NOT NULL,
  `land_offered` int(5) NOT NULL,
  `army_demanded` int(5) NOT NULL,
  `money_demanded` int(5) NOT NULL,
  `land_demanded` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `disqualified` int(1) NOT NULL DEFAULT '0',
  `college` varchar(300) NOT NULL,
  `email_id` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `army` int(5) NOT NULL DEFAULT '500',
  `money` int(5) NOT NULL DEFAULT '500',
  `land` int(5) NOT NULL DEFAULT '500',
  `move_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_loan_log`
--

CREATE TABLE IF NOT EXISTS `user_loan_log` (
  `loan_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_amount` int(2) NOT NULL,
  `taken_on` int(2) NOT NULL,
  `returned` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `army_purchase_log`
--
ALTER TABLE `army_purchase_log`
  ADD PRIMARY KEY (`army_purchase_log`);

--
-- Indexes for table `attack_log`
--
ALTER TABLE `attack_log`
  ADD PRIMARY KEY (`attack_log_id`);

--
-- Indexes for table `trade_log`
--
ALTER TABLE `trade_log`
  ADD PRIMARY KEY (`trade_log_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_loan_log`
--
ALTER TABLE `user_loan_log`
  ADD PRIMARY KEY (`loan_log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `army_purchase_log`
--
ALTER TABLE `army_purchase_log`
  MODIFY `army_purchase_log` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attack_log`
--
ALTER TABLE `attack_log`
  MODIFY `attack_log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `trade_log`
--
ALTER TABLE `trade_log`
  MODIFY `trade_log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_loan_log`
--
ALTER TABLE `user_loan_log`
  MODIFY `loan_log_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
