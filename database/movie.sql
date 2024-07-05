-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2024 at 01:19 AM
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
-- Database: `movie`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `about_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`about_id`, `title`, `description`) VALUES
(1, 'WELCOME TO CINEEASE', '\r\nCineEase is the most innovative online movie ticket booking platform in Malaysia, setting high standards in &#039;Convenience&#039;, &#039;Service&#039; &amp; &#039;Technology&#039;.\r\n\r\nWith an extensive network covering 100+ cities, CineEase offers a seamless and user-friendly experience for booking movie tickets online. Originating from a country with a deep passion for cinema, we are dedicated to bringing the joy of the big screen to our users.\r\n\r\nCineEase has revolutionized digital ticketing in Malaysia, making the process efficient, reliable, and secure. Our platform is available 24/7, ensuring that the love for movies is just a click away.');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `book_date` date NOT NULL,
  `book_time` time NOT NULL,
  `payment_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `card_name` varchar(255) NOT NULL,
  `card_number` varchar(255) NOT NULL,
  `ex_date` varchar(255) NOT NULL,
  `cvv` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `movie_id`, `book_date`, `book_time`, `payment_date`, `total_amount`, `card_name`, `card_number`, `ex_date`, `cvv`) VALUES
(1, 1, 6, '2024-06-26', '10:00:00', '2024-06-25 22:53:10', 25.00, 'ikhmal', '546513156', '2301-12-31', 333),
(2, 1, 6, '2024-06-29', '10:00:00', '2024-06-28 03:06:43', 25.00, 'ikhmal', '1231432423', '3333-02-23', 111),
(3, 1, 6, '2024-06-30', '15:00:00', '2024-06-28 03:08:01', 25.00, 'ikhmal', '34123412341', '1234-04-23', 3243),
(4, 4, 6, '2024-06-30', '10:00:00', '2024-06-28 23:45:28', 25.00, 'afiq', '4352345454', '34534-05-31', 524),
(5, 9, 6, '2024-07-01', '12:30:00', '2024-06-29 06:02:41', 25.00, 'ghfsgsrth', '67856534633', '2555-03-04', 777),
(6, 1, 6, '2024-07-01', '12:30:00', '2024-06-29 22:03:41', 25.00, 'afiq', '2365456435135', '4346-02-13', 233);

-- --------------------------------------------------------

--
-- Table structure for table `cinema`
--

CREATE TABLE `cinema` (
  `cinema_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `negeri` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cinema`
--

INSERT INTO `cinema` (`cinema_id`, `location`, `city`, `negeri`) VALUES
(1, 'IOI Mall', 'Puchong', 'Selangor');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `user_comment` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `user_id`, `movie_id`, `user_comment`, `rating`, `time`) VALUES
(1, 1, 6, 'this is one of the best movie i\'ve seen', 50, '2024-06-26 18:25:23'),
(2, 1, 6, 'test', 30, '2024-06-26 20:02:50'),
(3, 4, 6, 'test 2', 10, '2024-06-26 20:24:26'),
(4, 4, 6, 'test 3', 20, '2024-06-26 20:24:36');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `contact_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `massage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`contact_id`, `fullname`, `email`, `massage`) VALUES
(1, 'afiq haziqal', 'afiq@gmail.com', 'test'),
(2, 'afiq haziqal', 'afiq@gmail.com', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `movie_detail`
--

CREATE TABLE `movie_detail` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `writers` varchar(255) NOT NULL,
  `stars` varchar(255) NOT NULL,
  `date_start_air` date NOT NULL,
  `date_end_air` date NOT NULL,
  `genre` varchar(255) NOT NULL,
  `hour` int(10) NOT NULL,
  `minutes` int(10) NOT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `img_link` varchar(255) NOT NULL,
  `img_banner` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie_detail`
--

INSERT INTO `movie_detail` (`movie_id`, `title`, `description`, `director`, `writers`, `stars`, `date_start_air`, `date_end_air`, `genre`, `hour`, `minutes`, `price`, `img_link`, `img_banner`) VALUES
(6, 'Bad Boys: Ride or Die', 'This Summer, the world\'s favorite Bad Boys are back with their iconic mix of edge-of-your seat action and outrageous comedy but this time with a twist: Miami\'s finest are now on the run', 'Adil El Arbi, Bilall Fallah', 'Chris Bremner, Will Beall, George Gallo', 'Will Smith, Martin Lawrence, Vanessa Hudgens', '2024-06-15', '2024-07-05', 'Action, Comedy, Adventure', 1, 55, 25.00, 'img/Bad_Boys_Ride_or_Die_2024.jpg', 'img/Bad_Boys_Ride_or_Die_banner.jpeg'),
(7, 'The Fall Guy', 'A down-and-out stuntman must find the missing star of his ex-girlfriend\'s blockbuster film', 'David Leitch', 'Glen A. Larson, Drew Pearce', 'Ryan Gosling, Emily Blunt, Aaron Taylor-Johnson', '2024-06-05', '2024-06-25', 'Action, Comedy, Drama', 2, 6, 23.00, 'img/The_Fall_Guy_2024.jpg', 'img/The_Fall_Guy_2024_banner.jpg'),
(10, 'The Garfield Movie', 'After Garfield\'s unexpected reunion with his long-lost father, ragged alley cat Vic, he and his canine friend Odie are forced from their perfectly pampered lives to join Vic on a risky heist', 'Mark Dindal', 'Paul A. Kaplan, Mark Torgove, David Reynolds', 'Chris Pratt, Samuel L. Jackson, Hannah Waddingham', '2024-07-04', '2024-07-24', 'Comedy, Animation, Adventure', 1, 41, 22.00, 'img/The_Garfield_Movie_poster.jpg', 'img/The_Garfield_Movie_banner.jpg'),
(11, 'Venom: The Last Dance', 'Eddie and Venom are on the run. Hunted by both of their worlds and with the net closing in, the duo are forced into a devastating decision that will bring the curtains down on Venom and Eddie\'s last dance', 'Kelly Marcel', 'Kelly Marcel, Tom Hardy', 'Tom Hardy, Juno Temple, Alanna Ubach', '2024-08-31', '2024-09-30', 'Action, Science Fiction, Animation', 1, 41, 26.00, 'img/venom_the_last_dance_poster.jpg', 'img/Venom-The-Last-Dance-banner.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `team_member`
--

CREATE TABLE `team_member` (
  `team_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `date_of_birth` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `negeri` varchar(255) NOT NULL,
  `studying` varchar(255) NOT NULL,
  `university` varchar(255) NOT NULL,
  `student_id` int(30) NOT NULL,
  `img_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_member`
--

INSERT INTO `team_member` (`team_id`, `fullname`, `description`, `age`, `date_of_birth`, `status`, `genre`, `city`, `negeri`, `studying`, `university`, `student_id`, `img_link`) VALUES
(2, 'Muhammad Ikhmal Firdaus Bin Khairul Rizam', 'I\'m a type of person like to listen to music.', 23, '2001-03-30', 'Single', 'Metal, Punk, Rock', 'Puchong', 'Selangor', 'Diploma in IT', 'MMU Cyberjaya', 1211211301, 'img/ikhmal.JPG'),
(3, 'Afiq Haziqal Bin Haiqal Kenneth Lim', 'I\'m a Type of Person Who Loves Cars', 20, '2004-08-26', 'Single', 'Emo, Pop, Rock', 'Bandar Enstek', 'Negeri Sembilan', 'Diploma in IT', 'MMU Cyberjaya', 1211211327, 'img/img_667355a3d33f4.jpeg'),
(4, 'Gautam A/l Gopi Venkatesan', 'I have a profound love for monster fish; they captivate me with their elegance and beauty.', 21, '2003-10-03', 'Single', 'Melody, Pop', 'Karak', 'Pahang', 'Diploma in IT', 'MMU Cyberjaya', 1211206349, 'img/img_667356212c17f.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phone_no` varchar(12) NOT NULL,
  `role` int(5) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fullname`, `username`, `phone_no`, `role`, `email`, `password`, `profile_picture`) VALUES
(1, 'Ikhmal Firdaus', 'mallsahaja', '0175972511', 1, 'ikhmalfirdaus391@gmail.com', '$2y$10$CsnBjIvx771134sOGEhSg.LeBLtTfTNuVNfhZ7A9rVO3t8GgUj8by', ''),
(4, 'afiq haziqal', 'afiq', '00123456789', 3, 'afiq@gmail.com', '$2y$10$J8rXYGgegtRKVxaJAidCkeRvoZXC8cUlcSYvJd4.Uz4eCiAsiIZlG', ''),
(5, 'cap ibu anak', 'ibu', '1234567890', 2, 'capibu@gmail.com', '$2y$10$2GRnxy9SUkB8cTXty3oRWOZ/reRsFImJbRDa6FTe7RObcgQv92SVy', ''),
(6, 'cap kapak', 'kapak', '2345678901', 2, 'capkapak@gmail.com', '$2y$10$DR1qcWRA3pEsmG5DxqdD2uKS1BG3505ZJwlKQkBxcgIVdLpPigqJ2', ''),
(7, 'cap rambutan', 'rambutan', '3456789012', 3, 'caprambutan@gmail.com', '$2y$10$boIjfjvbdbesn/OgaiZuE.UL/o1KuWaxWFLNBUf9nm6P4i82ZeWGu', ''),
(8, 'abu abu', 'abu', '74102589630', 3, 'abuabu@gmail.com', '$2y$10$LcfYt4xjdsZBvH8vOWEDzuyNM4.oen1F6dKFPh3N9YoYsMMCR68VK', ''),
(9, 'asia asia', 'asia', '01234566521', 2, 'asia@gmail.com', '$2y$10$SvEcH8euhR7AN6DfmGxXXemGmovRhTCB6oYYqlbHRo/FppwxbBbyS', ''),
(10, 'botol air', 'botolair', '847685416847', 2, 'botol@gmail.com', '$2y$10$uCe.thEULDQ/taP6F1GJFOYM0wn3Dgi4Va1gJbOCxNcuipV8n2aha', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`about_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cinema`
--
ALTER TABLE `cinema`
  ADD PRIMARY KEY (`cinema_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `movie_detail`
--
ALTER TABLE `movie_detail`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `team_member`
--
ALTER TABLE `team_member`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `about_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cinema`
--
ALTER TABLE `cinema`
  MODIFY `cinema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `movie_detail`
--
ALTER TABLE `movie_detail`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `team_member`
--
ALTER TABLE `team_member`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie_detail` (`movie_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie_detail` (`movie_id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
