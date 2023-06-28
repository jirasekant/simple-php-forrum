-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Jun 20, 2023 at 12:30 PM
-- Server version: 8.0.33
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_docker`
--
CREATE DATABASE IF NOT EXISTS `php_docker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `php_docker`;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `user` varchar(127) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `title`, `body`, `user`, `date_created`) VALUES
(1, 'First post!', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam posuere lacus quis dolor. Duis pulvinar. Aliquam erat volutpat. Maecenas libero. Fusce tellus odio, dapibus id fermentum quis, suscipit id erat. Cras elementum. Suspendisse nisl. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus. Etiam dui sem, fermentum vitae, sagittis id, malesuada in, quam. Integer imperdiet lectus quis justo.\r\n\r\nDuis ante orci, molestie vitae vehicula venenatis, tincidunt ac pede. Cras elementum. Maecenas aliquet accumsan leo. Pellentesque ipsum. Duis viverra diam non justo. Integer lacinia. Nullam lectus justo, vulputate eget mollis sed, tempor sed magna. Duis condimentum augue id magna semper rutrum. Fusce consectetuer risus a nunc. Fusce tellus. In convallis. Proin mattis lacinia justo. Fusce consectetuer risus a nunc. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam erat volutpat. Aenean placerat.', 'admin', '2023-06-07 19:56:33'),
(2, 'Second post!!!1!!1!', 'This is the second post on this forum.', 'mod', '2023-06-15 15:38:37'),
(3, 'Third post!', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam posuere lacus quis dolor. Duis pulvinar. Aliquam erat volutpat. Maecenas libero. Fusce tellus odio, dapibus id fermentum quis, suscipit id erat. Cras elementum. Suspendisse nisl. Duis bibendum, lectus ut viverra rhoncus, dolor nunc faucibus libero, eget facilisis enim ipsum id lacus. Etiam dui sem, fermentum vitae, sagittis id, malesuada in, quam. Integer imperdiet lectus quis justo.\r\n\r\nDuis ante orci, molestie vitae vehicula venenatis, tincidunt ac pede. Cras elementum. Maecenas aliquet accumsan leo. Pellentesque ipsum. Duis viverra diam non justo. Integer lacinia. Nullam lectus justo, vulputate eget mollis sed, tempor sed magna. Duis condimentum augue id magna semper rutrum. Fusce consectetuer risus a nunc. Fusce tellus. In convallis. Proin mattis lacinia justo. Fusce consectetuer risus a nunc. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam erat volutpat. Aenean placerat.', 'admin', '2023-06-07 19:56:55'),
(6, 'Fourth post', 'blabla', '2023-06-18 09:48:19', '2023-06-18 09:48:19'),
(16, 'Fitth post', 'Hey there! This is a post!', 'admin', '2023-06-20 12:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int NOT NULL,
  `user` varchar(127) NOT NULL,
  `email` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date_created` datetime NOT NULL,
  `parent_id` int DEFAULT NULL,
  `article_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `user`, `email`, `text`, `date_created`, `parent_id`, `article_id`) VALUES
(1, 'user1', 'user1@email.com', 'Nice article!', '2023-06-16 14:35:30', NULL, 1),
(2, 'user2', 'user2@email.com', 'Nice comment!', '2023-06-16 14:35:30', 1, 1),
(3, 'aa', 'aa', 'aa', '2023-06-19 14:18:47', NULL, 1),
(4, 'test', 't', 't', '2023-06-19 16:31:54', NULL, 1),
(5, 'user3', 'us@mail.com', 'Even nicer comment!', '2023-06-19 16:41:53', NULL, 1),
(6, 'user4', 'aa', 'Replying now!', '2023-06-19 16:52:10', NULL, 1),
(7, 'test1', '', 'test1', '2023-06-19 17:02:35', NULL, 1),
(8, 'test2', 'test2', 'test2', '2023-06-19 17:02:52', NULL, 1),
(9, 'bla', 'bla', 'bla', '2023-06-19 17:41:19', NULL, 1),
(10, 'reply testest!', 'aa', 'reply test!', '2023-06-19 17:52:16', NULL, 1),
(11, 'test3', 'a', 'a', '2023-06-19 17:58:38', 2, 1),
(12, 'test4', 'tt', 'blablabla', '2023-06-19 18:06:17', 11, 1),
(13, 'a', 'a', 'a', '2023-06-19 18:22:39', 4, 1),
(14, 'a', 'a', 'a', '2023-06-19 18:26:19', NULL, 2),
(15, 'b', 'b', 'b', '2023-06-19 18:27:20', NULL, 2),
(16, 'cd', 'cd', 'cd', '2023-06-19 18:27:32', NULL, 2),
(17, 'a', 'a', 'a', '2023-06-19 18:29:06', NULL, 2),
(18, 'r', 'r', 'r', '2023-06-19 18:30:10', NULL, 2),
(19, 'aa', 'a', 'a', '2023-06-19 18:30:56', NULL, 2),
(20, 'qqq', 'qq', 'q', '2023-06-19 18:31:05', NULL, 2),
(21, 'q', 'q', 'q', '2023-06-19 18:31:16', NULL, 2),
(22, 'a', 'a', 'a', '2023-06-19 18:34:18', 18, 2),
(23, 'a', 'a', 'a', '2023-06-19 18:34:24', NULL, 2),
(24, 'reply', 'reply', 'reply!', '2023-06-20 11:58:30', 10, 1),
(25, 'user', 'usr@mail.com', 'This is a comment!', '2023-06-20 12:27:00', NULL, 16),
(26, 'user2', 'usr2@mail.com', 'This is a reply to a comment!', '2023-06-20 12:27:34', 25, 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
