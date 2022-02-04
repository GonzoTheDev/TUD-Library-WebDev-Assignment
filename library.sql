-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2021 at 10:54 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--
CREATE DATABASE IF NOT EXISTS `library` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `library`;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `ID` int(11) NOT NULL,
  `ISBN` varchar(50) NOT NULL,
  `BookTitle` varchar(255) NOT NULL,
  `Author` varchar(255) NOT NULL,
  `Edition` int(11) NOT NULL,
  `Year` int(4) NOT NULL,
  `Category` varchar(3) NOT NULL,
  `Reserved` varchar(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`ID`, `ISBN`, `BookTitle`, `Author`, `Edition`, `Year`, `Category`, `Reserved`) VALUES
(1, '093-403992', 'Computers in Business', 'Alicia O Neill', 3, 1997, '003', 'N'),
(2, '23472-8729', 'Exploring Peru', 'Stephanie Birchi', 4, 2005, '005', 'N'),
(3, '237-34823', 'Business Strategy', 'Joe Peppard', 2, 2002, '002', 'N'),
(4, '23u8-923849', 'A guide to nutrition', 'John Thorpe', 2, 1997, '001', 'N'),
(5, '2983-3494', 'Cooking for children', 'Anabelle Sharpe', 1, 2003, '007', 'N'),
(6, '82n8-308', 'computers for idiots', 'Susan O Neill', 5, 1998, '004', 'N'),
(7, '9823-23984', 'My life in picture', 'Kevin Graham', 8, 2004, '001', 'N'),
(8, '9823-2403-0', 'DaVinci Code', 'Dan Brown', 1, 2003, '008', 'N'),
(9, '98234-029384', 'My ranch in Texas', 'George Bush', 1, 2005, '001', 'N'),
(10, '9823-98345', 'How to cook italian food', 'Jamie Oliver', 2, 2005, '007', 'N'),
(11, '9823-98487', 'Optimising your business', 'Cleo Blair', 1, 2001, '002', 'N'),
(12, '988745-234', 'Tara Road', 'Maeve Binchy', 4, 2002, '008', 'N'),
(13, '993-004-00', 'My life in bits', 'John Smith', 1, 2001, '001', 'N'),
(14, '9987-0039882', 'Shooting History', 'Jon Snow', 1, 2003, '001', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `CategoryID` varchar(3) NOT NULL,
  `CategoryDesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryDesc`) VALUES
('001', 'Health'),
('002', 'Business'),
('003', 'Biography'),
('004', 'Technology'),
('005', 'Travel'),
('006', 'Self-Help'),
('007', 'Cookery'),
('008', 'Fiction');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
  `ID` int(11) NOT NULL,
  `ISBN` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `ReserveDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `Surname` varchar(255) NOT NULL,
  `AddressLine1` varchar(255) NOT NULL,
  `AddressLine2` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Telephone` varchar(10) NOT NULL,
  `Mobile` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `password`, `FirstName`, `Surname`, `AddressLine1`, `AddressLine2`, `City`, `Telephone`, `Mobile`) VALUES
(3, 'alanjmckenna', 't1234s', 'Alan', 'McKenna', '38 Cranley Road', 'Fairview', 'Dublin', '9998377', '0856625567'),
(4, 'joecrotty', 'kj7899', 'Joseph', 'Crotty', 'Apt 5 Clyde Road', 'Donnybrook', 'Dublin', '8887889', '0876654456'),
(5, 'tommy100', '123456', 'Tom', 'Behan', '14 Hyde Road', 'Dalkey', 'Dublin', '9983747', '0876738782');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ISBN` (`ISBN`),
  ADD KEY `Category` (`Category`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IBSN` (`ISBN`),
  ADD KEY `Username` (`Username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`Category`) REFERENCES `categories` (`CategoryID`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`ISBN`) REFERENCES `books` (`ISBN`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
