-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 12:17 PM
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
-- Database: `userdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `expires_at` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `user_id`, `message`, `expires_at`, `created_at`) VALUES
(15, 10, 'poggi sige na', '2025-12-03', '2025-12-02 10:19:41'),
(16, 10, 'sige na pogi', '2025-12-03', '2025-12-02 10:20:04'),
(19, 10, 'fedominus file', '2025-12-03', '2025-12-02 10:55:59'),
(20, 10, 'geh kaya pa toh', '2025-12-03', '2025-12-02 11:14:39'),
(21, 10, 'Successful presentations!!! Congrats Group 6 you made it', '2025-12-04', '2025-12-02 11:15:39');

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learning_materials`
--

INSERT INTO `learning_materials` (`id`, `subject_code`, `file_name`, `file_path`, `file_type`, `file_size`, `uploaded_by`, `upload_date`) VALUES
(5, 'ENV-201', 'ACTIVITY2PRELIM_FY2526.pdf', 'uploads/692d02503cc5c0.97966669_ACTIVITY2PRELIM_FY2526.pdf', 'application/pdf', 451193, '1', '2025-12-01 02:49:52'),
(16, 'PATHFit-3', 'Delos RReyes ye_8040-3-3_20251110_115122.pdf', 'uploads/692e723be7cf35.76859496_Delos RReyes ye_8040-3-3_20251110_115122.pdf', 'application/pdf', 70162, '10', '2025-12-02 04:59:39'),
(17, 'IT-221', 'ACTIVITY2PRELIM_FY2526 (1).pdf', 'uploads/692eaf0e46af16.51177889_ACTIVITY2PRELIM_FY2526 (1).pdf', 'application/pdf', 451193, '10', '2025-12-02 09:19:10'),
(18, 'IT-221', 'img001.pdf', 'uploads/692eb945c49aa3.48212511_img001.pdf', 'application/pdf', 244560, '10', '2025-12-02 10:02:45');

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE `userdata` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `section` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdata`
--

INSERT INTO `userdata` (`id`, `full_name`, `student_id`, `section`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'Taulo, Jhoncen ', '2024- 00001', '2IT9', 'jcen', '$2y$10$dl/nlQW99EFz4uhCuMbQVOb6Up1WF9bL8QGSfWwZcM8OqrMjFLO5W', 'User', '2025-11-28 10:16:54'),
(4, 'Magante, Justine ', '2024- 00002', '2IT9', 'fed', '$2y$10$uav4/0LD13mPe8vMxmCLBeeXiOYjlqBFySF4P/VtWwRbGkhRa.6vq', 'User', '2025-11-28 10:51:20'),
(6, 'Dones, Bea Faye S.', '2024-00003', '2IT9', 'beafaye', '$2y$10$.b76QEhHpOLcsmi9afPi..XBjSqwngYwfjsUIZcackxrGwYaTUhCu', '', '2025-12-01 08:42:06'),
(7, 'Briliante, Jennell', '2024-00023', '2IT9', 'Class Rep', '$2y$10$E/CjmHZv9WRQRS0WyIeF6uDOD0xZQH2Op8y1MrNgZ3D9JcVSdV5vi', '', '2025-12-01 08:46:56'),
(10, 'Delos Reyes,Jhon Salvador D.', '2024-00032', '2IT9', 'Jhon', '$2y$10$BNanCDWi/s3kgvdTlEYZP.cY68MDQ..DQUURpcD6IuCw43EtmMgk.', 'Admin', '2025-12-01 09:48:12'),
(11, 'Caponga, Trishtan Mark', '2024-00234', '2IT9', 'Tan', '$2y$10$2i5OQJ59mYUoAIqVGpUuku23ZH.0FAdQe0.4CrS7qL2I7ErPKdHga', 'User', '2025-12-02 09:56:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_code` (`subject_code`);

--
-- Indexes for table `userdata`
--
ALTER TABLE `userdata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `userdata`
--
ALTER TABLE `userdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `delete_expired_announcements` ON SCHEDULE EVERY 1 DAY STARTS '2025-12-02 19:11:54' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM announcements WHERE expiry_date < CURDATE()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
