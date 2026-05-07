-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2026 at 10:32 AM
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
-- Database: `milkdairy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `target` enum('all','farmers','staff') DEFAULT 'all',
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `posted_by` varchar(20) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `expires_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `target`, `priority`, `posted_by`, `is_active`, `expires_at`, `created_at`) VALUES
(1, 'Welcome to GDMS v2', 'Upgraded system with enhanced quality testing and spoilage detection now live.', 'all', 'high', 'STF001', 1, NULL, '2026-03-14 16:07:01'),
(2, 'Updated Milk Prices 2024', 'Grade A: KES 55/L + KES 5 bonus. Grade B: KES 45/L. Grade C: KES 35/L.', 'farmers', 'high', 'STF001', 1, NULL, '2026-03-14 16:07:01'),
(3, 'Quality Standards Reminder', 'Milk must be cooled below 6°C before delivery. Antibiotic withdrawal periods must be observed.', 'farmers', 'normal', 'STF003', 1, NULL, '2026-03-14 16:07:01');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'kamau john', 'kamau@gmail.com', '0743434343', 'Payment Query', 'Hi, iam Kamau a farmer from Kiambaa and my payments are not yet processed.', 'unread', '2026-03-21 15:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `farmer_id` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `subcounty` varchar(100) DEFAULT 'Githunguri',
  `county` varchar(100) DEFAULT 'Kiambu',
  `farm_size` decimal(10,2) DEFAULT 0.00,
  `number_of_cows` int(11) DEFAULT 0,
  `cow_breeds` varchar(255) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account` varchar(50) DEFAULT NULL,
  `mpesa_number` varchar(20) DEFAULT NULL,
  `registration_date` date DEFAULT curdate(),
  `approval_status` enum('pending','approved','rejected') DEFAULT 'approved',
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `performance_score` int(11) DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `total_deliveries` int(11) DEFAULT 0,
  `total_litres` decimal(10,2) DEFAULT 0.00,
  `total_earnings` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `farmer_id`, `full_name`, `email`, `phone`, `id_number`, `password`, `location`, `ward`, `subcounty`, `county`, `farm_size`, `number_of_cows`, `cow_breeds`, `bank_name`, `bank_account`, `mpesa_number`, `registration_date`, `approval_status`, `status`, `performance_score`, `created_at`, `last_login`, `total_deliveries`, `total_litres`, `total_earnings`) VALUES
(5, 'FRM005', 'david kim', 'davidkim@gmail.com', '0740605523', '32456789', '$2y$10$7eBFyL1SYRqL/eS0IdntvOdHNLjVB6l65t9u36VJDsn7PSngsoTsS', 'githunguri', NULL, 'Githunguri', 'Kiambu', 0.00, 3, NULL, NULL, NULL, '0740605523', '2026-03-14', 'approved', 'active', 100, '2026-03-14 16:42:41', '2026-03-16 12:58:04', 5, 102.00, 4560.00),
(6, 'FRM006', 'david kim', 'davidk@gmail.com', '0745323547', '42456789', '$2y$10$xFvO4tYhH48DPyjx.Xr4TeegjNPRRuY5Y3iwkkBeyY41cVr0wPgnu', 'githunguri', NULL, 'Githunguri', 'Kiambu', 0.00, 4, NULL, NULL, NULL, '0745323547', '2026-03-14', 'approved', 'active', 100, '2026-03-14 20:07:48', '2026-03-14 20:08:10', 0, 0.00, 0.00),
(7, 'FRM007', 'Otieno', 'otieno@gmail.com', '0756455454', '23456667', '$2y$10$98uFvAAaXUcQ90Bv.IRwtehAVlAp0rYg99EA8n.4VC0jo7KyW.90i', 'githunguri', 'kiambaa', 'Githunguri', 'Kiambu', 0.00, 3, 'Fresian', 'kcb', 'A01234567', '0756455454', '2026-03-14', 'approved', 'active', 100, '2026-03-14 20:50:10', NULL, 5, 109.00, 3420.00),
(8, 'FRM008', 'david kim', 'admin1@gmail.com', '0740605541', '42456782', '$2y$10$W1O/7bR3YW0IpKE.gewSsu6P6/f26mkyKteFA5BJ03s414KQjMlii', 'githunguri', NULL, 'Githunguri', 'Kiambu', 0.00, 2, NULL, NULL, NULL, '0740605541', '2026-03-15', 'approved', 'active', 100, '2026-03-15 12:12:08', NULL, 0, 0.00, 0.00),
(9, 'FRM009', 'simon', 'simon@gmail.com', '0723456643', '36787878', '$2y$10$YF3mnJePqKY.gjsjash6V.OT/DcEwU9xAnHtFIO23o98UTyqta73O', 'githunguri', NULL, 'Githunguri', 'Kiambu', 0.00, 2, NULL, NULL, NULL, '0723456643', '2026-03-16', 'approved', 'active', 100, '2026-03-16 13:00:06', '2026-03-16 13:29:37', 0, 0.00, 0.00),
(10, 'FRM010', 'henry', 'henry@gmail.com', '0776765432', '23456756', '$2y$10$fsDKpcG446N/iBja9Z2G4upYf3YculLeAg80DPnGEoUc84tuAFTqK', 'githunguri', NULL, 'Githunguri', 'Kiambu', 0.00, 3, NULL, NULL, NULL, '0776765432', '2026-03-20', 'approved', 'active', 100, '2026-03-20 11:27:04', '2026-03-20 23:45:08', 2, 55.00, 3615.00),
(14, 'FRM011', 'mark', 'mark@gmail.com', '0743434343', '43434366', '$2y$10$YH2VtUmJdDWoi6WeHlendODvYK9I6RQCMQP4lrXRqbRtuSax6Iuoa', 'kiambaa', NULL, 'Githunguri', 'Kiambu', 0.00, 4, NULL, NULL, NULL, '0743434343', '2026-03-21', 'approved', 'active', 100, '2026-03-21 00:03:38', '2026-03-21 00:04:02', 0, 0.00, 0.00),
(15, 'FRM012', 'lane', 'lane@gmail.com', '0711232323', '31272727', '$2y$10$QDzi7WZeXLGt0/q9FeFP0u1sfM1S34uGqjimTN51NwRFmcfnRM2dq', '', NULL, 'Githunguri', 'Kiambu', 0.00, 5, NULL, NULL, NULL, '0711232323', '2026-03-21', 'approved', 'active', 100, '2026-03-21 00:07:03', NULL, 0, 0.00, 0.00),
(16, 'FRM013', 'kamau john', 'kamau@gmail.com', '0743127128', '24676778', '$2y$10$d8e1l/dF7AevJ9LdNyJhReoPRz5ILZABb56TR3gHRm4uWhkbjMh1S', 'githunguri', NULL, 'Githunguri', 'Kiambu', 0.00, NULL, NULL, NULL, NULL, '0743127128', '2026-03-21', 'approved', 'active', 100, '2026-03-21 00:19:44', '2026-03-21 00:20:07', 1, 43.00, 0.00),
(17, 'FRM014', 'Grace', 'Grace@gmail.com', '0712312312', '42363674', '$2y$10$csUepVjxtv8tlwJ7xPZ78.QevZ1Agdw5okYLfw4jfulYTQFmHlqkq', 'kiambaa', NULL, 'Githunguri', 'Kiambu', 0.00, NULL, NULL, NULL, NULL, '0712312312', '2026-03-21', 'approved', 'active', 100, '2026-03-21 15:27:38', '2026-03-21 15:28:01', 1, 20.00, 0.00),
(18, 'FRM015', 'Mercy', 'mercy@gmail.com', '0710111111', '21343434', '$2y$10$Akb7tGn35xkWMmAaUVoAGu05BWiIzlwxc7oYHzU7LEGm4jh8A65fS', 'kiambaa', 'kiambaa', 'Githunguri', 'Kiambu', 0.00, 4, 'Fresian', '', '', '0710111111', '2026-03-21', 'approved', 'active', 100, '2026-03-21 15:32:43', NULL, 0, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `milk_deliveries`
--

CREATE TABLE `milk_deliveries` (
  `id` int(11) NOT NULL,
  `delivery_id` varchar(30) NOT NULL,
  `farmer_id` varchar(20) NOT NULL,
  `recorded_by` varchar(20) NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_time` time NOT NULL,
  `session` enum('morning','evening') NOT NULL,
  `quantity_litres` decimal(8,2) NOT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `smell_check` enum('normal','slightly_off','sour','bad') DEFAULT 'normal',
  `visual_check` enum('normal','slightly_off','clotted','watery','yellow') DEFAULT 'normal',
  `storage_hours` decimal(5,2) DEFAULT 0.00,
  `fat_content` decimal(5,2) DEFAULT NULL,
  `protein_content` decimal(5,2) DEFAULT NULL,
  `acidity` decimal(5,3) DEFAULT NULL,
  `titratable_acidity` decimal(5,2) DEFAULT NULL,
  `snf` decimal(5,2) DEFAULT NULL,
  `density` decimal(7,4) DEFAULT NULL,
  `water_content` decimal(5,2) DEFAULT NULL,
  `lactose` decimal(5,2) DEFAULT NULL,
  `conductivity` decimal(5,2) DEFAULT NULL,
  `freezing_point` decimal(6,3) DEFAULT NULL,
  `somatic_cell_count` enum('low','medium','high') DEFAULT 'low',
  `antibiotic_test` enum('negative','positive','pending') DEFAULT 'pending',
  `alcohol_test` enum('pass','fail','pending') DEFAULT 'pending',
  `added_water_test` enum('pass','fail','pending') DEFAULT 'pass',
  `quality_grade` enum('A','B','C','rejected') DEFAULT NULL,
  `quality_score` int(11) DEFAULT NULL,
  `quality_status` varchar(20) DEFAULT NULL,
  `quality_issues` text DEFAULT NULL,
  `spoilage_risk` enum('low','medium','high','critical') DEFAULT 'low',
  `spoilage_score` int(11) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `storage_tank` varchar(50) DEFAULT NULL,
  `price_per_litre` decimal(6,2) DEFAULT 45.00,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('pending','paid','processing') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `ai_analysis` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `milk_deliveries`
--

INSERT INTO `milk_deliveries` (`id`, `delivery_id`, `farmer_id`, `recorded_by`, `delivery_date`, `delivery_time`, `session`, `quantity_litres`, `temperature`, `smell_check`, `visual_check`, `storage_hours`, `fat_content`, `protein_content`, `acidity`, `titratable_acidity`, `snf`, `density`, `water_content`, `lactose`, `conductivity`, `freezing_point`, `somatic_cell_count`, `antibiotic_test`, `alcohol_test`, `added_water_test`, `quality_grade`, `quality_score`, `quality_status`, `quality_issues`, `spoilage_risk`, `spoilage_score`, `rejection_reason`, `storage_tank`, `price_per_litre`, `total_amount`, `payment_status`, `notes`, `ai_analysis`, `created_at`, `updated_at`) VALUES
(11, 'DLV202603147484', 'FRM005', 'STF001', '2026-03-14', '21:22:00', 'morning', 23.00, 9.00, 'normal', 'normal', 23.00, 4.50, 6.70, 6.500, 9.80, 6.70, 1.0345, 23.00, 9.80, 0.00, 0.000, 'low', 'negative', 'pass', 'pass', 'rejected', 0, 'rejected', 'Excessive water adulteration (>23%)', 'critical', 80, NULL, 'TK001', 0.00, 0.00, 'paid', '', NULL, '2026-03-14 20:23:33', '2026-03-14 20:47:15'),
(12, 'DLV202603140311', 'FRM005', 'STF001', '2026-03-14', '21:22:00', 'morning', 23.00, 9.00, 'normal', 'normal', 0.00, 4.50, 6.70, 6.500, NULL, 6.70, NULL, 23.00, NULL, NULL, NULL, 'low', 'negative', 'pass', 'pass', 'rejected', NULL, 'rejected', 'Excessive water adulteration (>23%)', 'high', NULL, NULL, 'TK001', 0.00, 0.00, 'paid', '', NULL, '2026-03-14 20:25:46', '2026-03-14 20:47:15'),
(13, 'DLV202603144210', 'FRM007', 'STF001', '2026-03-14', '21:50:00', 'morning', 12.00, 9.00, 'normal', 'normal', 0.00, 6.70, 5.60, 4.500, NULL, 7.80, NULL, 12.00, NULL, NULL, NULL, 'low', 'negative', 'pass', 'pass', 'rejected', NULL, 'rejected', 'pH critically out of range (4.5)', 'high', NULL, NULL, 'TK001', 0.00, 0.00, 'paid', '', NULL, '2026-03-14 20:52:01', '2026-03-14 20:53:28'),
(14, 'DLV202603146095', 'FRM007', 'STF001', '2026-03-14', '21:53:00', 'morning', 34.00, 9.00, 'normal', 'normal', 0.00, 3.50, 5.60, 7.000, NULL, 7.80, NULL, 23.00, NULL, NULL, NULL, 'low', 'negative', 'pass', 'pass', 'rejected', NULL, 'rejected', 'Excessive water adulteration (>23%)', 'high', NULL, NULL, 'TK003', 0.00, 0.00, 'pending', '', NULL, '2026-03-14 21:02:01', '2026-03-14 21:02:01'),
(15, 'DLV202603147847', 'FRM005', 'STF001', '2026-03-14', '22:02:00', 'morning', 32.00, 4.00, 'normal', 'normal', 0.00, 6.70, 6.50, 6.600, NULL, 8.80, NULL, 4.00, NULL, NULL, NULL, 'low', 'negative', 'pass', 'pass', 'B', NULL, 'good', 'Slight water content (4%)', 'low', NULL, NULL, 'TK001', 45.00, 1440.00, 'paid', '', NULL, '2026-03-14 21:05:04', '2026-03-14 21:05:35'),
(16, 'DLV202603156026', 'FRM007', 'STF001', '2026-03-15', '00:18:00', 'morning', 12.00, 9.00, 'normal', 'normal', 0.00, 3.40, 5.60, 7.600, NULL, 7.80, NULL, 4.00, NULL, NULL, NULL, 'low', 'negative', 'pending', 'pass', 'rejected', NULL, 'rejected', 'pH critically out of range (7.6)', 'high', NULL, NULL, 'TK001', 0.00, 0.00, 'pending', '', 'Based on the provided parameters:\n\n1. QUALITY VERDICT: A (assuming standard composition parameters are within acceptable limits)\n2. SPOILAGE RISK: Medium (without specific temperature and sensory data, a moderate risk is assumed)\n3. KEY ISSUES: None specified, but potential issues may include high bacterial counts, improper temperature control, or sensory defects.\n4. SPECIFIC RECOMMENDATIONS: Regularly monitor temperature (below 4°C), implement good hygiene practices, and perform sensory evaluations to minimize spoilage risk.\n5. SAFE STORAGE TIME estimate: 7-10 days (assuming proper storage conditions and handling)\n\nPlease provide specific parameters for a detailed assessment.', '2026-03-14 23:21:37', '2026-03-20 11:53:58'),
(17, 'DLV202603155765', 'FRM007', 'STF001', '2026-03-15', '00:21:00', 'morning', 25.00, 4.00, 'normal', 'normal', 0.00, 6.70, 6.50, 6.600, NULL, 8.90, NULL, 3.00, NULL, NULL, NULL, 'low', 'negative', 'pending', 'pass', 'B', NULL, 'good', 'Slight water content (3%)', 'low', NULL, NULL, 'TK001', 45.00, 1125.00, 'paid', '', NULL, '2026-03-14 23:23:26', '2026-03-14 23:24:08'),
(18, 'DLV202603204091', 'FRM010', 'STF001', '2026-03-20', '12:28:00', 'morning', 32.00, 8.00, 'normal', 'normal', 0.00, 3.60, 3.50, 6.800, NULL, 8.90, NULL, 2.00, NULL, NULL, NULL, 'low', 'negative', 'pending', 'pass', 'A', NULL, 'excellent', '', 'medium', NULL, NULL, 'TK001', 55.00, 1760.00, 'paid', '', NULL, '2026-03-20 11:29:09', '2026-03-20 11:33:01'),
(19, 'DLV202603200011', 'FRM010', 'STF001', '2026-03-20', '13:34:00', 'evening', 23.00, 13.00, 'normal', 'normal', 0.00, 5.60, 5.60, 7.500, NULL, 8.50, NULL, 4.00, NULL, NULL, NULL, 'low', 'negative', 'pending', 'pass', 'rejected', NULL, 'rejected', 'pH critically out of range (7.5)', 'critical', NULL, NULL, 'TK001', 0.00, 0.00, 'pending', '', NULL, '2026-03-20 12:20:35', '2026-03-20 12:20:35'),
(20, 'DLV202603206852', 'FRM005', 'STF001', '2026-03-20', '14:33:00', 'evening', 12.00, 9.00, 'normal', 'normal', 0.00, 4.50, 6.70, 6.900, 0.00, 8.90, 0.0000, 3.00, 0.00, 0.00, 0.000, 'low', 'negative', 'pending', 'pass', 'C', 31, 'acceptable', 'pH slightly off (6.9); Titratable acidity abnormal (0°T); Density abnormal (0 g/ml); Slight water content (3%); Lactose out of range (0%)', 'critical', 70, NULL, 'TK001', 35.00, 420.00, 'paid', '', NULL, '2026-03-20 13:35:09', '2026-03-21 00:27:55'),
(21, 'DLV202603207753', 'FRM005', 'STF001', '2026-03-20', '14:33:00', 'evening', 12.00, 9.00, 'normal', 'normal', 0.00, 4.50, 6.70, 6.900, 0.00, 8.90, 0.0000, 3.00, 0.00, 0.00, 0.000, 'low', 'negative', 'pending', 'pass', 'C', 31, 'acceptable', 'pH slightly off (6.9); Titratable acidity abnormal (0°T); Density abnormal (0 g/ml); Slight water content (3%); Lactose out of range (0%)', 'critical', 70, NULL, 'TK001', 35.00, 420.00, 'paid', '', NULL, '2026-03-20 13:35:26', '2026-03-21 00:27:55'),
(22, 'DLV202603206854', 'FRM007', 'STF001', '2026-03-20', '14:39:00', 'morning', 26.00, 9.00, 'sour', 'yellow', 0.00, 5.70, 4.50, 6.900, NULL, 8.90, NULL, 2.00, NULL, NULL, NULL, 'low', 'negative', 'pending', 'pass', 'B', NULL, 'good', 'pH slightly off (6.9)', 'high', NULL, NULL, 'TK001', 45.00, 1170.00, 'pending', '', NULL, '2026-03-20 13:50:41', '2026-03-20 13:50:41'),
(23, 'DLV202603216193', 'FRM013', 'STF001', '2026-03-21', '01:20:00', 'morning', 43.00, 8.00, 'slightly_off', 'yellow', 0.00, 4.50, 6.80, 7.900, NULL, 7.80, NULL, 7.00, NULL, NULL, NULL, 'low', 'negative', 'pending', 'pass', 'rejected', NULL, 'rejected', 'pH critically out of range (7.9)', 'medium', NULL, NULL, 'TK003', 0.00, 0.00, 'pending', '', '**Analysis:**\n\n*Composition:*\nFat: 3.5%, Protein: 3.2%, Lactose: 4.8%, Water: 88%\n\n*Quality Verdict:* Grade A\n*Quality Parameters are within acceptable limits.\n\n*Spoilage Parameters:*\nTemperature: 4°C, Odor: Neutral, Slime: Absent\n\n*Spoilage Risk:* Low\nTemperature is within safe range, and sensory checks indicate no spoilage.\n\n*Key Issues:* None found\n*Specific Recommendations:* Maintain proper storage conditions to preserve quality.\n*Safe Storage Time estimate:* 7-10 days\n\nThe milk sample meets Grade A quality standards. Spoilage risk is low, and the sample can be safely stored for 7-10 days if maintained at 4°C. Regular monitoring of temperature and sensory checks will help prevent spoilage.', '2026-03-21 00:21:41', '2026-03-23 09:28:22'),
(24, 'DLV202603215440', 'FRM014', 'STF001', '2026-03-21', '16:29:00', 'morning', 20.00, 7.00, 'normal', 'normal', 0.00, 4.50, 5.60, 7.600, NULL, 8.60, NULL, 3.00, NULL, NULL, NULL, 'low', 'negative', 'pending', 'pass', 'rejected', NULL, 'rejected', 'pH critically out of range (7.6)', 'medium', NULL, NULL, 'TK001', 0.00, 0.00, 'pending', '', 'Based on the provided parameters, here is my assessment:\n\n1. QUALITY VERDICT: Grade B (assuming standard composition parameters are within acceptable limits, but exact values are not provided)\n2. SPOILAGE RISK: Medium (temperature and sensory checks indicate potential for spoilage, but exact values are not provided)\n3. KEY ISSUES: Inability to evaluate actual quality and spoilage risk due to missing data\n4. SPECIFIC RECOMMENDATIONS: Provide complete data, maintain proper storage conditions (4°C), and conduct regular sensory checks\n5. SAFE STORAGE TIME: Estimate 3-5 days, assuming proper storage conditions and handling\n\nPlease provide complete data to enable a more accurate assessment.', '2026-03-21 15:29:50', '2026-03-23 09:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `target_type` enum('farmer','staff','all') NOT NULL,
  `target_id` varchar(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','alert','success','payment','quality') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `payment_id` varchar(30) NOT NULL,
  `farmer_id` varchar(20) NOT NULL,
  `processed_by` varchar(20) NOT NULL,
  `payment_period_start` date NOT NULL,
  `payment_period_end` date NOT NULL,
  `total_litres` decimal(10,2) NOT NULL,
  `grade_a_litres` decimal(10,2) DEFAULT 0.00,
  `grade_b_litres` decimal(10,2) DEFAULT 0.00,
  `grade_c_litres` decimal(10,2) DEFAULT 0.00,
  `base_amount` decimal(10,2) NOT NULL,
  `quality_bonus` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `deduction_reason` text DEFAULT NULL,
  `net_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('bank_transfer','mpesa','cash','cheque') DEFAULT 'mpesa',
  `payment_status` enum('pending','processing','completed','failed') DEFAULT 'pending',
  `transaction_ref` varchar(100) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_id`, `farmer_id`, `processed_by`, `payment_period_start`, `payment_period_end`, `total_litres`, `grade_a_litres`, `grade_b_litres`, `grade_c_litres`, `base_amount`, `quality_bonus`, `deductions`, `deduction_reason`, `net_amount`, `payment_method`, `payment_status`, `transaction_ref`, `payment_date`, `notes`, `created_at`) VALUES
(1, 'PAY202411001', 'FRM001', 'STF002', '2024-11-01', '2024-11-02', 49.50, 49.50, 0.00, 0.00, 2722.50, 247.50, 0.00, NULL, 2970.00, 'mpesa', 'completed', 'MPE241116001', '2024-11-16 07:00:00', NULL, '2026-03-14 16:07:00'),
(2, 'PAY202411002', 'FRM002', 'STF002', '2024-11-01', '2024-11-02', 80.50, 80.50, 0.00, 0.00, 4427.50, 402.50, 0.00, NULL, 4830.00, 'bank_transfer', 'completed', 'BNK241116002', '2024-11-16 07:30:00', NULL, '2026-03-14 16:07:00'),
(3, 'PAY202411003', 'FRM003', 'STF002', '2024-11-01', '2024-11-01', 18.00, 0.00, 18.00, 0.00, 810.00, 0.00, 0.00, NULL, 810.00, 'mpesa', 'completed', 'MPE241116003', '2024-11-16 08:00:00', NULL, '2026-03-14 16:07:00'),
(4, 'PAY202603902', 'FRM005', 'STF001', '2026-03-01', '2026-03-14', 46.00, 0.00, 0.00, 46.00, 0.00, 0.00, 290.00, 'lower milk quality', 0.00, 'mpesa', 'completed', NULL, '2026-03-14 20:47:15', '', '2026-03-14 20:47:15'),
(5, 'PAY202603966', 'FRM007', 'STF001', '2026-03-01', '2026-03-14', 12.00, 0.00, 0.00, 12.00, 0.00, 0.00, 234.00, 'lower milk quality', 0.00, 'mpesa', 'completed', NULL, '2026-03-14 20:53:28', '', '2026-03-14 20:53:28'),
(6, 'PAY202603498', 'FRM005', 'STF001', '2026-03-01', '2026-03-14', 32.00, 0.00, 32.00, 0.00, 1440.00, 0.00, 0.00, '', 1440.00, 'mpesa', 'completed', NULL, '2026-03-14 21:05:35', '', '2026-03-14 21:05:35'),
(7, 'PAY202603890', 'FRM007', 'STF001', '2026-03-01', '2026-03-15', 25.00, 0.00, 25.00, 0.00, 1125.00, 0.00, 0.00, '', 1125.00, 'mpesa', 'completed', NULL, '2026-03-14 23:24:08', '', '2026-03-14 23:24:08'),
(8, 'PAY202603310', 'FRM010', 'STF001', '2026-03-01', '2026-03-20', 32.00, 32.00, 0.00, 0.00, 1760.00, 160.00, 65.00, 'water content slightly high', 1855.00, 'mpesa', 'completed', NULL, '2026-03-20 11:33:01', '', '2026-03-20 11:33:01'),
(9, 'PAY202603253', 'FRM005', 'STF001', '2026-03-01', '2026-03-21', 24.00, 0.00, 0.00, 24.00, 840.00, 0.00, 0.00, '', 840.00, 'mpesa', 'completed', NULL, '2026-03-21 00:27:55', '', '2026-03-21 00:27:55');

-- --------------------------------------------------------

--
-- Table structure for table `price_config`
--

CREATE TABLE `price_config` (
  `id` int(11) NOT NULL,
  `grade` enum('A','B','C') NOT NULL,
  `price_per_litre` decimal(6,2) NOT NULL,
  `quality_bonus` decimal(6,2) DEFAULT 0.00,
  `effective_date` date NOT NULL,
  `updated_by` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_config`
--

INSERT INTO `price_config` (`id`, `grade`, `price_per_litre`, `quality_bonus`, `effective_date`, `updated_by`, `status`, `created_at`) VALUES
(1, 'A', 55.00, 5.00, '2024-01-01', NULL, 'active', '2026-03-14 16:07:00'),
(2, 'B', 45.00, 0.00, '2024-01-01', NULL, 'active', '2026-03-14 16:07:00'),
(3, 'C', 35.00, 0.00, '2024-01-01', NULL, 'active', '2026-03-14 16:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','manager','quality_inspector','data_entry','accountant') DEFAULT 'data_entry',
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_code` varchar(20) NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `profile_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_id`, `full_name`, `email`, `phone`, `role`, `username`, `password`, `access_code`, `status`, `profile_notes`, `created_at`, `last_login`) VALUES
(1, 'STF001', 'David Kimani', 'admin@githunguri.coop', '0712345678', 'admin', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'GDFC2024', 'active', NULL, '2026-03-14 16:07:00', '2026-03-23 09:19:26'),
(2, 'STF002', 'Mary Wanjiku', 'manager@githunguri.coop', '0723456789', 'manager', 'manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'GDFC2024', 'active', NULL, '2026-03-14 16:07:00', NULL),
(3, 'STF003', 'Peter Kamau', 'quality@githunguri.coop', '0734567890', 'quality_inspector', 'quality', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'GDFC2024', 'active', NULL, '2026-03-14 16:07:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `storage_tanks`
--

CREATE TABLE `storage_tanks` (
  `id` int(11) NOT NULL,
  `tank_id` varchar(20) NOT NULL,
  `tank_name` varchar(100) NOT NULL,
  `capacity_litres` decimal(10,2) NOT NULL,
  `current_volume` decimal(10,2) DEFAULT 0.00,
  `temperature` decimal(5,2) DEFAULT NULL,
  `status` enum('active','maintenance','full','empty') DEFAULT 'empty',
  `last_cleaned` date DEFAULT NULL,
  `next_cleaning_due` date DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `storage_tanks`
--

INSERT INTO `storage_tanks` (`id`, `tank_id`, `tank_name`, `capacity_litres`, `current_volume`, `temperature`, `status`, `last_cleaned`, `next_cleaning_due`, `location`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'TK001', 'Tank A — Primary Cooling', 5000.00, 1200.00, 5.60, 'active', '2024-11-01', '2024-11-08', 'Main Collection Center', 'temperature flactuating', '2026-03-14 16:07:00', '2026-03-21 00:33:08'),
(2, 'TK002', 'Tank B — Secondary Cooling', 3000.00, 800.00, 4.80, 'active', '2024-10-28', '2024-11-04', 'Main Collection Center', NULL, '2026-03-14 16:07:00', '2026-03-14 16:07:00'),
(3, 'TK003', 'Tank C — Buffer', 2000.00, 0.00, 5.00, 'empty', '2024-10-20', '2024-11-03', 'Secondary Center', NULL, '2026-03-14 16:07:00', '2026-03-14 16:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_type` enum('staff','farmer') NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_type`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-14 16:17:35'),
(2, 'farmer', 'FRM005', 'Login', 'Farmer logged in', '::1', '2026-03-14 16:42:57'),
(3, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-14 16:43:41'),
(4, 'farmer', 'FRM006', 'Login', 'Farmer logged in', '::1', '2026-03-14 20:08:10'),
(5, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-14 20:22:13'),
(6, 'staff', 'STF001', 'Record Delivery', 'DLV202603147484 for FRM005: 23L Grade:rejected Spoilage:critical', '::1', '2026-03-14 20:23:33'),
(7, 'staff', 'STF001', 'Record Delivery', 'DLV202603140311 for FRM005: 23L Grade:rejected Spoilage:high', '::1', '2026-03-14 20:25:46'),
(8, 'staff', 'STF001', 'Process Payment', 'PAY202603902 for FRM005: KES 0', '::1', '2026-03-14 20:47:15'),
(9, 'staff', 'STF001', 'Add Farmer', 'Farmer FRM007: Otieno', '::1', '2026-03-14 20:50:10'),
(10, 'staff', 'STF001', 'Record Delivery', 'DLV202603144210 for FRM007: 12L Grade:rejected Spoilage:high', '::1', '2026-03-14 20:52:01'),
(11, 'staff', 'STF001', 'Process Payment', 'PAY202603966 for FRM007: KES 0', '::1', '2026-03-14 20:53:28'),
(12, 'staff', 'STF001', 'Record Delivery', 'DLV202603146095 for FRM007: 34L Grade:rejected Spoilage:high', '::1', '2026-03-14 21:02:01'),
(13, 'staff', 'STF001', 'Record Delivery', 'DLV202603147847 for FRM005: 32L Grade:B Spoilage:low', '::1', '2026-03-14 21:05:04'),
(14, 'staff', 'STF001', 'Process Payment', 'PAY202603498 for FRM005: KES 1440', '::1', '2026-03-14 21:05:35'),
(15, 'farmer', 'FRM005', 'Login', 'Farmer logged in', '::1', '2026-03-14 23:08:50'),
(16, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-14 23:18:53'),
(17, 'staff', 'STF001', 'Record Delivery', 'DLV202603156026 for FRM007: 12L Grade:rejected Spoilage:high', '::1', '2026-03-14 23:21:37'),
(18, 'staff', 'STF001', 'Record Delivery', 'DLV202603155765 for FRM007: 25L Grade:B Spoilage:low', '::1', '2026-03-14 23:23:26'),
(19, 'staff', 'STF001', 'Process Payment', 'PAY202603890 for FRM007: KES 1125', '::1', '2026-03-14 23:24:08'),
(20, 'farmer', 'FRM005', 'Login', 'Farmer logged in', '::1', '2026-03-15 10:06:34'),
(21, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-15 10:08:01'),
(22, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-15 11:38:01'),
(23, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-15 12:31:25'),
(24, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-15 12:32:12'),
(25, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-15 13:40:49'),
(26, 'farmer', 'FRM005', 'Login', 'Farmer logged in', '::1', '2026-03-16 12:11:14'),
(27, 'farmer', 'FRM005', 'Login', 'Farmer logged in', '::1', '2026-03-16 12:11:47'),
(28, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-16 12:12:20'),
(29, 'farmer', 'FRM005', 'Login', 'Farmer logged in', '127.0.0.1', '2026-03-16 12:43:36'),
(30, 'farmer', 'FRM005', 'Login', 'Farmer logged in', '::1', '2026-03-16 12:43:56'),
(31, 'farmer', 'FRM005', 'Login', 'Farmer logged in', '::1', '2026-03-16 12:58:04'),
(32, 'farmer', 'FRM009', 'Login', 'Farmer logged in', '::1', '2026-03-16 13:00:36'),
(33, 'farmer', 'FRM009', 'Login', 'Farmer logged in successfully', '::1', '2026-03-16 13:13:52'),
(34, 'farmer', 'FRM009', 'Login', 'Farmer logged in successfully', '::1', '2026-03-16 13:20:11'),
(35, 'farmer', 'FRM009', 'Login', 'Farmer logged in successfully', '::1', '2026-03-16 13:29:37'),
(36, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-16 13:30:35'),
(37, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-16 13:40:00'),
(38, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-20 11:04:22'),
(39, 'farmer', 'FRM010', 'Login', 'Farmer logged in successfully', '::1', '2026-03-20 11:27:31'),
(40, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-20 11:28:15'),
(41, 'staff', 'STF001', 'Record Delivery', 'DLV202603204091 for FRM010: 32L Grade:A Spoilage:medium', '::1', '2026-03-20 11:29:09'),
(42, 'staff', 'STF001', 'Process Payment', 'PAY202603310 for FRM010: KES 1855', '::1', '2026-03-20 11:33:01'),
(43, 'staff', 'STF001', 'Record Delivery', 'DLV202603200011 for FRM010: 23L Grade:rejected Spoilage:critical', '::1', '2026-03-20 12:20:35'),
(44, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-20 12:37:29'),
(45, 'staff', 'STF001', 'Record Delivery', 'DLV202603206852 for FRM005: 12L Grade:C Spoilage:critical', '::1', '2026-03-20 13:35:09'),
(46, 'staff', 'STF001', 'Record Delivery', 'DLV202603207753 for FRM005: 12L Grade:C Spoilage:critical', '::1', '2026-03-20 13:35:26'),
(47, 'staff', 'STF001', 'Record Delivery', 'DLV202603206854 for FRM007: 26L Grade:B Spoilage:high', '::1', '2026-03-20 13:50:41'),
(48, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-20 19:50:29'),
(49, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-20 19:59:34'),
(50, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-20 20:12:23'),
(51, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-20 20:14:17'),
(52, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-20 20:25:03'),
(53, 'farmer', 'FRM010', 'Login', 'Farmer logged in successfully', '::1', '2026-03-20 23:45:08'),
(54, 'farmer', 'FRM011', 'Login', 'Farmer logged in successfully', '::1', '2026-03-21 00:04:02'),
(55, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-21 00:07:29'),
(56, 'farmer', 'FRM013', 'Login', 'Farmer logged in successfully', '::1', '2026-03-21 00:20:07'),
(57, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-21 00:20:34'),
(58, 'staff', 'STF001', 'Record Delivery', 'DLV202603216193 for FRM013: 43L Grade:rejected Spoilage:medium', '::1', '2026-03-21 00:21:41'),
(59, 'staff', 'STF001', 'Process Payment', 'PAY202603253 for FRM005: KES 840', '::1', '2026-03-21 00:27:55'),
(60, 'staff', 'STF001', 'Update Tank', 'Tank TK001: 1200L @ 5.6°C', '::1', '2026-03-21 00:33:08'),
(61, 'farmer', 'FRM014', 'Login', 'Farmer logged in successfully', '::1', '2026-03-21 15:28:01'),
(62, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-21 15:28:36'),
(63, 'staff', 'STF001', 'Record Delivery', 'DLV202603215440 for FRM014: 20L Grade:rejected Spoilage:medium', '::1', '2026-03-21 15:29:50'),
(64, 'staff', 'STF001', 'Add Farmer', 'Farmer FRM015: Mercy', '::1', '2026-03-21 15:32:43'),
(65, 'staff', 'STF001', 'Login', 'Staff logged in', '::1', '2026-03-23 09:19:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `farmer_id` (`farmer_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id_number` (`id_number`);

--
-- Indexes for table `milk_deliveries`
--
ALTER TABLE `milk_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_id` (`delivery_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_id` (`payment_id`);

--
-- Indexes for table `price_config`
--
ALTER TABLE `price_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `storage_tanks`
--
ALTER TABLE `storage_tanks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tank_id` (`tank_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `milk_deliveries`
--
ALTER TABLE `milk_deliveries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `price_config`
--
ALTER TABLE `price_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `storage_tanks`
--
ALTER TABLE `storage_tanks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
