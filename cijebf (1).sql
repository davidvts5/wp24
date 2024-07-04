-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 04, 2024 at 04:47 PM
-- Server version: 8.0.37-0ubuntu0.20.04.3
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cijebf`
--

-- --------------------------------------------------------

--
-- Table structure for table `breeds`
--

CREATE TABLE `breeds` (
  `breed_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breeds`
--

INSERT INTO `breeds` (`breed_id`, `category_id`, `name`) VALUES
(100, 10, 'Pug'),
(155, 20, 'Persian cat'),
(156, 10, 'Beagle'),
(181, 51, 'Koi'),
(184, 10, 'Labrador'),
(189, 10, 'Chihuahua'),
(190, 20, 'Bengal cat'),
(191, 51, 'Gold Fish'),
(192, 10, 'Bulldog'),
(193, 10, 'Jackrussel'),
(194, 10, 'Nova rasa');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(10, 'Dog'),
(20, 'Cat'),
(51, 'Fish'),
(52, 'Hamster');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `listing_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `listing_id`) VALUES
(1, 15, 10130),
(2, 15, 10130),
(3, 15, 10130),
(4, 15, 10136),
(5, 15, 10146),
(6, 23, 10136),
(7, 23, 10136),
(8, 23, 10136),
(9, 23, 10142),
(10, 23, 10142),
(32, 12, 10128),
(33, 12, 10128),
(34, 12, 10128),
(35, 12, 10128),
(42, 23, 10150),
(43, 23, 10152),
(45, 23, 10155),
(46, 23, 10153),
(47, 32, 10153),
(49, 12, 10130),
(55, 23, 12),
(57, 23, 8),
(58, 23, 8),
(59, 23, 18),
(61, 23, 19),
(62, 12, 12),
(63, 23, 26),
(64, 23, 27);

-- --------------------------------------------------------

--
-- Table structure for table `listings1`
--

CREATE TABLE `listings1` (
  `listing_id` int NOT NULL,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `breed_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(4096) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  `age` int DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `approved` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listings1`
--

INSERT INTO `listings1` (`listing_id`, `user_id`, `category_id`, `breed_id`, `title`, `email`, `phone`, `description`, `price`, `age`, `image`, `approved`) VALUES
(26, 23, 10, 100, 'Pug', 'd@d', '1231231231', 'Pugs are adorable dogs, and the Black Pug is no different. The dog is moderately easy to train, loves children, and is playful, friendly, and loyal. They can also be stubborn and independent, but they do great with proper training. You can expect your Black Pug to weigh between 14 and 18 pounds.', 500, 1, 'uploads/dog-123722_1280.jpg', 1),
(27, 23, 10, 100, 'Beagle', 'd@d', '1231231231', 'It looks like a small foxhound and has large brown eyes, hanging ears, and a short coat, usually a combination of black, tan, and white. The Beagle is a solidly built dog, heavy for its height. It generally excels as a rabbit hunter and is typically an alert, affectionate dog', 300, 1, 'uploads/beagle.png', 2),
(28, 23, 20, 190, 'Cat', 'd@d', '1231231231', 'Cats come in many sizes averaging around 8 to 10 pounds (3.6 to 4.5 kilograms). Moreover, cats can have many different colored coats, often have long tails and agile spins, different colored eyes that can see well at night, long whiskers on either side of their face, and sharp retractable claws.', 150, 1, 'uploads/macka.png', 2),
(29, 23, 51, 191, 'Gold fish', 'd@d', '1231231231', 'Goldfish have two sets of paired fins and three sets of single fins. They don\'t have barbels, sensory organs some fish have that act like taste buds. Nor do they have scales on their heads. They also don\'t have teeth and instead crush their food in their throats.', 15, 0, 'uploads/fish.png', 1),
(30, 23, 10, 189, 'Chihuahua', 'd@d', '1231231231', 'The Chihuahua is a balanced, graceful dog of terrier-like demeanor, weighing no more than 6 pounds. The rounded \"apple\" head is a breed hallmark. The erect ears and full, luminous eyes are acutely expressive. Coats come in many colors and patterns, and can be long or short.', 450, 2, 'uploads/chihuahua-466236_1280.jpg', 2),
(31, 23, 10, 192, 'Bulldog', 'd@d', '1231231231', 'Bulldog is a dog', 400, 1, 'uploads/bulldog1.jpg', 1),
(32, 23, 10, 193, 'Jack-Russel', 'd@d', '1231231231', 'The Jack Russell Terrier is a British breed of small terrier. It is principally white-bodied and smooth-, rough- or broken-coated, and can be any colour.', 250, 1, 'uploads/jackrussel.jpg', 1),
(33, 23, 10, 156, 'BEagle', 'd@d', '1231231231', 'It looks like a small foxhound and has large brown eyes, hanging ears, and a short coat, usually a combination of black, tan, and white. The Beagle is a solidly built dog, heavy for its height. It generally excels as a rabbit hunter and is typically an alert, affectionate dog.', 350, 1, 'uploads/beagle.png', 1),
(34, 15, 10, 194, 'oglas', 'n@n', '', 'hgh', 55, 1, 'uploads/smile.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `first_name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `confirmed` int NOT NULL DEFAULT '1',
  `status` int NOT NULL DEFAULT '1',
  `account_activation_hash` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `role`, `confirmed`, `status`, `account_activation_hash`) VALUES
(1, '123', '123', '123', '123', '', '', 0, 1, NULL),
(2, 'David', 'S', '132@sdfa.com', '$2y$10$bVva/uJReRaaAJU8/1Lu0OnKLU2s0msQrbyRe2YpSqIQzWBTfXIkG', '', '', 0, 1, NULL),
(5, 'David', '123', '13d2@sdfa.com', '$2y$10$6Miws2VroRDu2dzcaVDf9uoUa3UyGDktfabxQKrI6w0ALpClTLy1a', '', '', 0, 1, NULL),
(8, 'David', 'Skola', '123@g', '$2y$10$A/5fw4OF1tGcULiQy2zgbuJsJZMueSDjOClmA8J04zWTE7OuIjVk2', '', '', 0, 1, NULL),
(12, 'Stefan', 'Hladik', 'c@c', '$2y$10$p8kTXGuf5jWaplhPOaGM7egzg9Yu0jvG67CKS38YRBbUke4SzZppq', '', 'user', 0, 0, NULL),
(14, 'Petar', 'Puaca', 'petar.laptop.rodjendan@gmail.com', '$2y$10$kTNBn2Py3zXfCnjOBfL.z.81jYpUC6XyuLhYIZQo5pqXT90VbguQ6', '', 'user', 0, 0, NULL),
(15, 'Nikola', 'Skola', 'n@n', '$2y$10$oxEx77TKWuhNGpssNnYZSeV6il.YRGnAVNm0zijJl5SBsv6fN7Q2G', '34434343', 'user', 0, 1, NULL),
(16, 'Cijeb', 'Foha', 'cjieb@foha', '$2y$10$WMWBfCOb0d4zYaRnPt1iLuGOHcrIB2ML.JqeV4R/MoU14rEFZzmBa', '', 'user', 0, 1, NULL),
(17, 'k', 'k', 'a2@2', '$2y$10$4Jst24/f4J55OThMpUv7FOFeC6jNs6J/zSCMBou83/DpWmu75KBA2', '', 'user', 0, 1, NULL),
(18, 'cjiebfohabvurbsl', 'stefan', 'cjieb@foha.bvurbsl', '$2y$10$4iAFMiM6gZXAj9uINwwqdu5MHwVItf2fTBVurfed9BI5VplC/wUqG', '', 'user', 0, 1, NULL),
(20, 'Nikola', 'Virus', '111@11', '$2y$10$Y7UZklU2twjeg95UM0.vPe2AfGVtDen2mYb597zewttp4B1uNfaCq', '35353', 'user', 0, 1, NULL),
(22, 'Nikola', '123', '1@12', '$2y$10$1fTWhpDuE043JtWBzZMjM.G.JQjY88qTJeKNJgge9TkDSCI.Sl/gy', '555444', 'user', 0, 1, NULL),
(23, 'David', 'Simokovic', 'd@d', '$2y$10$RbsHAKBwnEOf9zYN.pt4detWjDwZiRkHrvQ8bzwdTIlfWLKdJKNEK', '1231231231', 'admin', 0, 1, NULL),
(24, 'David', 'e', 'k@k', '$2y$10$vP5u0q4DB.8VOrC1ZFVU2ekntH4CqnCqso9ZJt2yN7FKwBXbfjW0K', '1231231231', 'user', 0, 1, NULL),
(25, 'David', '123', 'd@da', '$2y$10$g2r4..7cc7AjWid2rHD5XeLL05P/HvtSPA.BwtOiuAxdupkHPkSiC', '2222222222', 'user', 0, 1, NULL),
(26, 'David', '123', 'da@d', '$2y$10$bzEDlIme9nF3qeUdgQ4Cl.ChocHEjgj3zISZL/TvSZOs4Ef5jMr2C', '6666666666', 'user', 0, 1, NULL),
(27, 'David', 'Simokovic', '5@5', '$2y$10$J0iHBCoY3sXfUEeeQona5uk7yqpOOUMzANEqDSAvy8h6Ja74TsK3W', '5556666666', 'user', 0, 1, NULL),
(28, 'Petar', 'Maras', 'maras@maras', '$2y$10$VpuB0KDVtuNHqY/ecSzoXOWWD5WzolGDHRo3y328sAHRgnXOVjAS2', '5555555555', 'user', 0, 1, NULL),
(29, 'Nenad', 'Maras', 'maras@nenad', '$2y$10$SJFeVfX5ZOh7pRQ5VLYqU.ZSDjpo6hkBn981wXaPbfyVVvLxBAf7.', '6512515251', 'user', 0, 1, NULL),
(30, 'Igor', 'Maras', 'igor@maras', '$2y$10$6zCVAu6qpOwDCT7PIOteRO44u3tLmPy75npC0M7zzoaGnbC0E.Y4S', '1312131212', 'user', 0, 1, NULL),
(31, 'Baca', 'Maras', 'maras@jv', '$2y$10$L8WR9GB6.FdBr3tJk.uKYOq3Pce7q1.CftfxznvIXPQdbXb6rgrpO', '5555555555', 'user', 0, 1, NULL),
(32, 'Matija', 'Lazic', 'flex@team.com', '$2y$10$xM31Hd3Jdws5I0i.r0DYE.DpaKMVlHsG3HFolBvoMO1QxQO1RJQMW', '2412121242', 'user', 0, 1, NULL),
(33, 'David', 'Program', 'oo@oo', '$2y$10$fjr8ODWkQxzwIm7vwABHwu3qJhSHu2mB/Df6H87846ae77C.b0CTq', '2423536363', 'user', 0, 1, '756af054aa4d8e11d601c05720345a612cd97cba293103bdaf819e043c53c04c'),
(34, 'Igor', 'Maras', 'igor@maras1', '$2y$10$Cdy3FWH7egdsGCh/lLAs6O6iKzhQ7nhn1wSqeM0nvxD1m2zD54qiG', '2312313131', 'user', 1, 1, '61e87a0107e928d6d8035d88df43fa8419419ae05e6f442fbc75e8f0b3fe960b');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `breeds`
--
ALTER TABLE `breeds`
  ADD PRIMARY KEY (`breed_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listings1`
--
ALTER TABLE `listings1`
  ADD PRIMARY KEY (`listing_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `breed_id` (`breed_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `account_activation_hash` (`account_activation_hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `breed_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `listings1`
--
ALTER TABLE `listings1`
  MODIFY `listing_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `breeds`
--
ALTER TABLE `breeds`
  ADD CONSTRAINT `breeds_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `listings1`
--
ALTER TABLE `listings1`
  ADD CONSTRAINT `listings1_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `listings1_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `listings1_ibfk_3` FOREIGN KEY (`breed_id`) REFERENCES `breeds` (`breed_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
