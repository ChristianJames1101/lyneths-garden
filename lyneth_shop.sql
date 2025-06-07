-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 05:17 PM
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
-- Database: `lyneth_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_requests`
--

CREATE TABLE `cancellation_requests` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `request_date` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancellation_requests`
--

INSERT INTO `cancellation_requests` (`id`, `order_id`, `customer_name`, `reason`, `request_date`, `status`) VALUES
(1, 81, 'Eiron', 'asd', '2025-05-21 21:43:50', 'Pending'),
(2, 82, 'Eiron', 'wqeweqqwe', '2025-05-21 21:45:28', 'Pending'),
(3, 81, 'Eiron', 'fghgfhfgh', '2025-05-21 21:48:21', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(72, 14, 42, 1, '2025-05-21 20:13:26'),
(73, 14, 54, 1, '2025-05-21 20:13:32'),
(74, 14, 50, 1, '2025-05-21 20:13:35'),
(75, 14, 52, 1, '2025-05-21 20:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `completed_orders`
--

CREATE TABLE `completed_orders` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `completed_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `completed_orders`
--

INSERT INTO `completed_orders` (`id`, `name`, `contact_number`, `address`, `product_name`, `quantity`, `total_price`, `status`, `total`, `completed_date`) VALUES
(1, 'Ana Cruz', '09181234567', 'Pasig', 'Succulent Plant', 4, 120.00, 'completed', 480.00, '2025-05-10 12:00:00'),
(2, 'Luis Gonzales', '09091234567', 'Makati', 'Hanging Basket', 2, 200.00, 'completed', 400.00, '2025-05-09 16:45:00'),
(3, 'Pedro Mabini', '09171239999', 'Taguig', 'Bonsai Tree', 1, 1000.00, 'completed', 1000.00, '2025-05-08 11:30:00'),
(4, 'Clara David', '09191234567', 'Parañaque', 'Rose Plant', 5, 180.00, 'completed', 900.00, '2025-05-07 15:00:00'),
(5, 'Rafael Reyes', '09291234567', 'Las Piñas', 'Compost', 3, 220.00, 'completed', 660.00, '2025-05-06 13:20:00'),
(6, 'Benny Cruz', '09381234567', 'San Juan', 'Orchid Plant', 2, 300.00, 'completed', 600.00, '2025-05-05 10:00:00'),
(7, 'Tina Ramos', '09561234567', 'Muntinlupa', 'Garden Netting', 1, 450.00, 'completed', 450.00, '2025-05-04 09:30:00'),
(8, 'Oscar Medina', '09421234567', 'Calamba', 'Herb Seeds', 5, 75.00, 'completed', 375.00, '2025-05-03 14:25:00'),
(9, 'Isabel Flores', '09211234567', 'Antipolo', 'Soil Mix', 3, 110.00, 'completed', 330.00, '2025-05-02 12:50:00'),
(10, 'Greg Morales', '09671234567', 'Batangas', 'Lettuce Kit', 2, 280.00, 'completed', 560.00, '2025-05-01 11:10:00'),
(12, 'Christian', '123', 'asd 123', 'Watering Can', 1, 100.00, 'completed', 100.00, '2025-05-11 11:15:37'),
(16, 'Juan', '09234576', 'Taguig City Manila', 'Dwarf umbrella tree', 1, 450.00, 'completed', 450.00, '2025-05-11 13:38:20'),
(17, 'Juan', '09234576', 'Taguig City Manila', 'Dwarf umbrella tree', 1, 450.00, 'completed', 450.00, '2025-05-11 13:38:17'),
(18, 'Juan', '09234576', 'Taguig City Manila', 'Bonsai', 1, 500.00, 'completed', 500.00, '2025-05-11 13:38:14'),
(36, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Chinese Evergreen', 1, 700.00, 'completed', 700.00, '2025-05-21 17:04:01'),
(38, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Acalypha wilkesiana', 1, 460.00, 'completed', 460.00, '2025-05-11 15:38:18'),
(39, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Bougainvillea spectabilis', 1, 430.00, 'completed', 430.00, '2025-05-11 15:38:20'),
(40, 'Eiron', '0912345678', 'Balagtas Bulacan', 'West Indian Lantana', 1, 600.00, 'completed', 600.00, '2025-05-11 15:38:21'),
(45, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Chinese Evergreen', 1, 700.00, 'completed', 700.00, '2025-05-20 08:34:24'),
(50, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Miracle fruit', 1, 500.00, 'completed', 500.00, '2025-05-20 08:41:05'),
(51, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Peperomia argyreia', 1, 350.00, 'completed', 350.00, '2025-05-21 17:19:50'),
(53, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Broadleaf lady palm', 1, 780.00, 'completed', 780.00, '2025-05-21 18:22:47'),
(55, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Dwarf umbrella tree', 1, 450.00, 'completed', 450.00, '2025-05-21 18:26:05'),
(56, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Peperomia argyreia', 1, 350.00, 'completed', 350.00, '2025-05-21 17:57:51'),
(64, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Mountain mahogany', 1, 350.00, 'completed', 350.00, '2025-05-21 19:23:38'),
(67, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Peacock plant', 1, 600.00, 'completed', 600.00, '2025-05-21 19:28:08'),
(68, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Broadleaf lady palm', 1, 780.00, 'completed', 780.00, '2025-05-21 19:30:47'),
(69, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Podocarpus macrophyllus', 1, 150.00, 'completed', 150.00, '2025-05-21 19:33:52'),
(72, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Podocarpus macrophyllus', 1, 150.00, 'completed', 150.00, '2025-05-21 20:11:50'),
(73, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Podocarpus macrophyllus', 1, 150.00, 'completed', 150.00, '2025-05-21 20:11:54'),
(74, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Podocarpus macrophyllus', 1, 150.00, 'completed', 150.00, '2025-05-21 20:12:18'),
(77, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Podocarpus macrophyllus', 1, 150.00, 'completed', 150.00, '2025-05-21 20:29:19'),
(79, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Bougainvillea spectabilis', 1, 430.00, 'completed', 430.00, '2025-05-21 21:03:53'),
(80, 'Eiron', '0912345678', 'Balagtas Bulacan', 'West Indian Lantana', 1, 600.00, 'completed', 600.00, '2025-05-21 20:11:58'),
(81, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Peperomia argyreia', 3, 1050.00, 'completed', 3150.00, '2025-05-21 22:45:40'),
(88, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Breynia', 1, 300.00, 'completed', 300.00, '2025-05-21 21:03:48');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `order_id`, `name`, `product`, `message`, `is_read`, `created_at`) VALUES
(4, 78, 'Eiron', 'Podocarpus macrophyllus', 'Changes in estimated delivery: \"Eiron\", your order #78 (Podocarpus macrophyllus) is being processed. Estimated delivery: 2025-05-30.', 0, '2025-05-21 13:03:43'),
(5, 88, 'Eiron', 'Breynia', 'Your order #88 (Breynia) has been completed.', 0, '2025-05-21 13:03:48'),
(6, 78, 'Eiron', 'Podocarpus macrophyllus', 'Your order #78 (Podocarpus macrophyllus) has been deleted.', 0, '2025-05-21 13:03:49'),
(7, 79, 'Eiron', 'Bougainvillea spectabilis', 'Your order #79 (Bougainvillea spectabilis) has been completed.', 0, '2025-05-21 13:03:53'),
(8, 81, 'Eiron', 'Peperomia argyreia', 'Changes in estimated delivery: \"Eiron\", your order #81 (Peperomia argyreia) is being processed. Estimated delivery: 2025-05-22.', 0, '2025-05-21 13:03:56'),
(9, 81, 'Eiron', 'Peperomia argyreia', 'Your order #81 (Peperomia argyreia) has been completed.', 0, '2025-05-21 14:45:40'),
(10, 82, 'Eiron', 'Peperomia argyreia', 'Changes in estimated delivery: \"Eiron\", your order #82 (Peperomia argyreia) is being processed. Estimated delivery: 2025-05-29.', 0, '2025-05-21 14:45:44'),
(11, 83, 'Eiron', 'Dwarf umbrella tree', 'Changes in estimated delivery: \"Eiron\", your order #83 (Dwarf umbrella tree) is being processed. Estimated delivery: 2025-05-23.', 0, '2025-05-21 14:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estimated_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `contact_number`, `address`, `product_name`, `quantity`, `total_price`, `status`, `order_date`, `total`, `estimated_date`) VALUES
(1, 'Juan Dela Cruz', '09171234567', 'Manila', 'Garden Soil', 2, 250.00, 'pending', '2025-05-10 10:15:00', 500.00, NULL),
(2, 'Maria Santos', '09981234567', 'Quezon City', 'Flower Pot', 3, 150.00, 'pending', '2025-05-09 14:30:00', 450.00, NULL),
(3, 'Jose Rizal', '09221234567', 'Cavite', 'Cactus Plant', 1, 300.00, 'pending', '2025-05-08 09:45:00', 300.00, NULL),
(4, 'Andrea Reyes', '09181237890', 'Pasay', 'Watering Can', 2, 350.00, 'pending', '2025-05-10 13:25:00', 700.00, NULL),
(5, 'Mark Lopez', '09391234567', 'Taguig', 'Organic Fertilizer', 5, 120.00, 'pending', '2025-05-07 08:00:00', 600.00, NULL),
(6, 'Liza Tan', '09451234567', 'Mandaluyong', 'Hose', 1, 500.00, 'pending', '2025-05-06 12:10:00', 500.00, NULL),
(7, 'Karla Lim', '09611234567', 'Valenzuela', 'Garden Shears', 2, 450.00, 'pending', '2025-05-05 17:30:00', 900.00, NULL),
(8, 'Carlos Diaz', '09771234567', 'Marikina', 'Peat Moss', 4, 90.00, 'pending', '2025-05-04 11:05:00', 360.00, NULL),
(9, 'Nina Gomez', '09081234567', 'Navotas', 'Mulch', 3, 80.00, 'pending', '2025-05-03 15:15:00', 240.00, NULL),
(10, 'Emil Santiago', '09101234567', 'Malabon', 'Plant Food', 6, 95.00, 'pending', '2025-05-02 16:40:00', 570.00, NULL),
(13, 'Juan', '09234576', 'Taguig City Manila', 'Acalypha wilkesiana', 1, 460.00, 'Pending', '2025-05-11 13:19:00', 460.00, NULL),
(14, 'Juan', '09234576', 'Taguig City Manila', 'Spider plant', 2, 1600.00, 'Pending', '2025-05-11 13:21:32', 1600.00, NULL),
(15, 'Juan', '09234576', 'Taguig City Manila', 'Peperomia argyreia', 1, 350.00, 'Pending', '2025-05-11 13:22:23', 350.00, NULL),
(19, 'Juan', '09234576', 'Taguig City Manila', 'Chinese Evergreen', 1, 700.00, 'Pending', '2025-05-11 13:41:44', 700.00, NULL),
(20, 'Juan', '09234576', 'Taguig City Manila', 'Bougainvillea spectabilis', 1, 430.00, 'Pending', '2025-05-11 13:41:47', 430.00, NULL),
(21, 'Juan', '09234576', 'Taguig City Manila', 'Podocarpus macrophyllus', 1, 150.00, 'Pending', '2025-05-11 13:59:31', 150.00, NULL),
(22, 'Juan', '09234576', 'Taguig City Manila', 'Peperomia argyreia', 1, 350.00, 'Pending', '2025-05-11 14:01:45', 350.00, NULL),
(23, 'Juan', '09234576', 'Taguig City Manila', 'Spider plant', 1, 800.00, 'Pending', '2025-05-11 14:03:17', 800.00, NULL),
(24, 'Juan', '09234576', 'Taguig City Manila', 'Miracle fruit', 1, 500.00, 'Pending', '2025-05-11 14:04:38', 500.00, NULL),
(25, 'Juan', '09234576', 'Taguig City Manila', 'Bougainvillea spectabilis', 1, 430.00, 'Pending', '2025-05-11 14:08:37', 430.00, NULL),
(26, 'Juan', '09234576', 'Taguig City Manila', 'Acalypha wilkesiana', 1, 460.00, 'Pending', '2025-05-11 14:11:03', 460.00, NULL),
(27, 'Juan', '09234576', 'Taguig City Manila', 'Bougainvillea spectabilis', 1, 430.00, 'Pending', '2025-05-11 14:13:04', 430.00, NULL),
(28, 'Juan', '09234576', 'Taguig City Manila', 'Acmena', 1, 360.00, 'Pending', '2025-05-11 14:13:51', 360.00, NULL),
(29, 'Juan', '09234576', 'Taguig City Manila', 'Peperomia argyreia', 1, 350.00, 'Pending', '2025-05-11 14:14:23', 350.00, NULL),
(30, 'Juan', '09234576', 'Taguig City Manila', 'Dwarf umbrella tree', 1, 450.00, 'Pending', '2025-05-11 14:14:45', 450.00, NULL),
(31, 'Juan', '09234576', 'Taguig City Manila', 'Peperomia argyreia', 1, 350.00, 'Pending', '2025-05-11 14:16:14', 350.00, NULL),
(32, 'Juan', '09234576', 'Taguig City Manila', 'Mountain mahogany', 1, 350.00, 'Pending', '2025-05-11 14:18:33', 350.00, NULL),
(33, 'Juan', '09234576', 'Taguig City Manila', 'Peperomia argyreia', 1, 350.00, 'Pending', '2025-05-11 14:22:13', 350.00, NULL),
(34, 'Juan', '09234576', 'Taguig City Manila', 'Ficus altissima', 1, 340.00, 'Pending', '2025-05-11 14:22:26', 340.00, NULL),
(35, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Dwarf umbrella tree', 1, 450.00, 'Pending', '2025-05-11 15:37:32', 450.00, '2025-05-24'),
(42, 'Eiron', '0912345678', 'Balagtas Bulacan', '0', 1, 450.00, 'processing', '2025-05-20 08:31:28', 450.00, '2025-05-28'),
(43, 'Eiron', '0912345678', 'Balagtas Bulacan', '0', 1, 500.00, 'processing', '2025-05-20 08:32:01', 500.00, '2025-05-22 to 2025-05-26'),
(46, 'Eiron', '0912345678', 'Balagtas Bulacan', '0', 1, 400.00, 'processing', '2025-05-20 08:32:18', 400.00, '2025-05-22 to 2025-05-25'),
(48, 'Eiron', '0912345678', 'Balagtas Bulacan', '0', 1, 350.00, 'processing', '2025-05-20 08:39:18', 350.00, '2025-05-23 to 2025-05-26'),
(52, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Peacock plant', 1, 600.00, 'Pending', '2025-05-21 17:19:34', 600.00, '2025-05-23'),
(54, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Terminalia ivorensis', 1, 300.00, 'Pending', '2025-05-21 17:19:34', 300.00, '2025-05-22'),
(55, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Dwarf umbrella tree', 1, 450.00, 'Pending', '2025-05-21 17:25:49', 450.00, '2025-05-26'),
(57, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Miracle fruit', 1, 500.00, 'Pending', '2025-05-21 18:31:05', 500.00, '2025-05-22'),
(59, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Chinese Evergreen', 1, 600.00, 'Pending', '2025-05-21 18:47:27', 600.00, NULL),
(60, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Chinese Evergreen', 1, 600.00, 'Pending', '2025-05-21 18:47:54', 600.00, NULL),
(61, 'Eiron', '0912345678', 'Balagtas Bulacan', 'West Indian Lantana', 1, 600.00, 'Pending', '2025-05-21 19:03:07', 600.00, NULL),
(62, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Dwarf umbrella tree', 1, 450.00, 'Pending', '2025-05-21 19:15:35', 450.00, NULL),
(65, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Peperomia argyreia', 1, 350.00, 'Pending', '2025-05-21 19:26:48', 350.00, NULL),
(66, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Broadleaf lady palm', 1, 780.00, 'Pending', '2025-05-21 19:26:57', 780.00, NULL),
(82, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Peperomia argyreia', 1, 350.00, 'Pending', '2025-05-21 19:27:05', 350.00, '2025-05-29'),
(83, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Dwarf umbrella tree', 1, 450.00, 'Pending', '2025-05-21 19:27:05', 450.00, '2025-05-23'),
(84, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Podocarpus macrophyllus', 1, 150.00, 'Pending', '2025-05-21 19:27:05', 150.00, NULL),
(85, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Spider plant', 1, 800.00, 'Pending', '2025-05-21 19:27:05', 800.00, NULL),
(86, 'Eiron', '0912345678', 'Balagtas Bulacan', 'Peperomia argyreia', 1, 350.00, 'Pending', '2025-05-21 19:27:05', 350.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `created_at`) VALUES
(15, 'Peperomia argyreia', 'A commonly known as the watermelon peperomia, is recognizable by its distinctive foliage. The leaves are round to oval-shaped with striking silver or light green bands alternating with darker green, resembling the pattern on a watermelon rind. These plants are typically small, growing up to about 8 inches tall, with a compact and bushy growth habit. Peperomia argyreia is often grown in hanging baskets or pots, as seen in the image, showcasing its attractive leaves.', 350.00, 5, 'a3bfff7e-5c5e-4b35-84e4-920d0f153f56.jpg', '2025-05-11 03:41:16'),
(16, 'Podocarpus macrophyllus', 'It is a conifer in the genus Podocarpus, family Podocarpaceae. It is the northernmost species of the genus, native to southern Japan and southern and eastern China. Common names in English include yew plum pine, Buddhist pine, fern pine and Japanese yew.', 150.00, 10, '2795fc29-1c5b-47d8-8158-0b2f8acdc09d.jpg', '2025-05-11 03:42:22'),
(17, 'Miracle fruit', 'Synsepalum dulcificum is a plant in the Sapotaceae family, native to tropical Africa. It is known for its berry that, when eaten, causes sour foods subsequently consumed to taste sweet. This effect is due to miraculin', 500.00, 15, 'd5b8fd26-6f3a-4970-8cc0-3da7a0769e40.jpg', '2025-05-11 03:43:22'),
(18, 'Dwarf umbrella tree', 'Heptapleurum arboricola is a flowering plant in the family Araliaceae, native to Hainan Province, China and Taiwan. Its common name is dwarf umbrella tree, as it resembles a smaller version of the umbrella tree, Heptapleurum actinophyllum.', 450.00, 13, '2530ba99-cf6b-47b7-bd57-c3e26ea1a55a.jpg', '2025-05-11 03:44:12'),
(19, 'Chinese Evergreen', 'Chinese evergreen (Aglaonema), is one of the best plants for beginners (or folks too busy to keep most houseplants alive). This sturdy plant is wonderfully easy to grow; it tolerates just about every indoor condition. While it\'s one of the toughest plants, it\'s also beautiful. Most varieties have rich green leaves attractively patterned with silver. It also has cute, calla-lily-like blooms in spots where it gets enough light.', 700.00, 6, '08d6c379-57c9-4b7a-bcde-20a47a8107ed.jpg', '2025-05-11 03:45:18'),
(20, 'Broadleaf lady palm', 'Rhapis excelsa, also known as broadleaf lady palm or bamboo palm, is a species of fan palm in the genus Rhapis. It is native to southern China and northern Vietnam. The genus name is Greek - rhapis, meaning \"needle\"; and the species name is Latin for \"tall\", though R. excelsa is not the tallest in the genus.', 500.00, 8, '2a4af917-c483-4cb7-b459-ba43733a0ff2.jpg', '2025-05-11 03:46:16'),
(21, 'Bonsai', 'Bonsai have long been respected in the ancient art of Feng Shui for their ability to draw life energies into a room, sharing them gladly with all who pass through. As a focus of sight, conversation, and living forces, a Bonsai can quickly spread joy and contentment to all who see it.', 500.00, 25, '8b701781-13fd-4396-b0ef-324f0b42323d.jpg', '2025-05-11 03:47:18'),
(22, 'Mountain mahogany', 'Cercocarpus betuloides is a shrub or small tree in the rose family. Its common names include mountain mahogany and birch leaf mountain mahogany The common name \"mahogany\" comes from the hardness and color of the wood, although the genus is not a true mahogany.', 350.00, 10, '6e999f73-bcfc-42ac-b33c-095463c27b7d.jpg', '2025-05-11 03:47:55'),
(23, 'Spider plant', 'Chlorophytum comosum, usually called spider plant or common spider plant due to its spider-like look, also known as spider ivy, airplane plant, ribbon plant, and hen and chickens, is a species of evergreen perennial flowering plant of the family Asparagaceae.', 800.00, 5, '73632720-183f-4a13-8594-3069a5397dd2.jpg', '2025-05-11 03:48:36'),
(24, 'Broadleaf lady palm', 'Rhapis excelsa, also known as broadleaf lady palm or bamboo palm, is a species of fan palm in the genus Rhapis. It is native to southern China and northern Vietnam. The genus name is Greek - rhapis, meaning \"needle\"; and the species name is Latin for \"tall\", though R. excelsa is not the tallest in the genus.', 780.00, 8, '681c1090-50b6-4deb-8f5f-3896b25ae9da.jpg', '2025-05-11 03:49:32'),
(25, 'Chinese Evergreen', 'Chinese evergreen (Aglaonema), is one of the best plants for beginners (or folks too busy to keep most houseplants alive). This sturdy plant is wonderfully easy to grow; it tolerates just about every indoor condition. While it\'s one of the toughest plants, it\'s also beautiful. Most varieties have rich green leaves attractively patterned with silver. It also has cute, calla-lily-like blooms in spots where it gets enough light.', 600.00, 3, 'cf8d5c7d-924b-451a-9c98-994d6a430a43.jpg', '2025-05-11 03:50:38'),
(26, 'Peacock plant', 'Goeppertia makoyana, also known as peacock plant or cathedral windows, is a species of plant belonging to the genus Goeppertia in the family Marantaceae, native to Espírito Santo state of eastern Brazil. It has gained the Royal Horticultural Society\'s Award of Garden Merit.', 600.00, 18, 'f8dbb4c6-87cb-490d-9afb-ef6f276a149c.jpg', '2025-05-11 03:51:29'),
(31, 'Terminalia ivorensis', 'Terminalia ivorensis is a species of tree in the family Combretaceae, and is known by the common names of Ivory Coast almond, idigbo, black afara, framire and emeri.', 300.00, 6, '9ef952b8-ee39-437d-bfdd-98efdc51d091.jpg', '2025-05-11 03:53:42'),
(32, 'Crepe-myrtle', 'Lagerstroemia indica, commonly known as a crape myrtle, is a species of flowering plant in the genus Lagerstroemia of the family Lythraceae. It originated in China. It is an often multi-stemmed, deciduous tree with a wide spreading, flat topped, rounded, or even spike shaped open habit.', 900.00, 3, '3d512257-06da-4db4-89e4-4c751e3d1fd3.jpg', '2025-05-11 03:54:49'),
(33, 'Cherry laurel', 'Prunus laurocerasus, also known as cherry laurel, common laurel and sometimes English laurel in North America, is an evergreen species of cherry, native to regions bordering the Black Sea in southwestern', 400.00, 5, 'da065c65-9f88-4198-883e-3315e65b5618.jpg', '2025-05-11 03:55:34'),
(34, 'Acmena', 'Acmena was formerly the name of a genus of shrubs and trees in the myrtle family Myrtaceae. The genus was first formally described in 1828 by Augustin Pyramus de Candolle in his Prodromus Systematis Naturalis Regni Vegetabilis.', 360.00, 7, '3808d4d2-087c-4484-add4-003ebc781317.jpg', '2025-05-11 03:56:28'),
(35, 'Acalypha wilkesiana', 'Acalypha wilkesiana, common names copperleaf, Jacob\'s coat and Flamengueira, is an evergreen shrub growing to 3 metres high and 2 metres across. It has a closely arranged crown, with an erect stem and many branches. Both the branches and the leaves are covered in fine hairs.', 460.00, 18, 'bc27257e-878b-4283-86e9-cb47efbd98d9.jpg', '2025-05-11 03:57:11'),
(36, 'Ficus altissima', 'Ficus altissima, commonly known as the council tree and lofty fig, is a species of flowering plant, a fig tree in the family Moraceae. It is a large, stately evergreen hemiepiphyte and is native to southeastern Asia.', 340.00, 12, 'dcf3eb49-6746-450f-86e2-61271b16906d.jpg', '2025-05-11 03:58:26'),
(37, 'Bougainvillea spectabilis', 'Bougainvillea spectabilis, also known as great bougainvillea, is a species of flowering plant. It is native to Brazil, Bolivia, Peru, and Argentina\'s Chubut Province. It is widely grown as an ornamental plant.', 430.00, 15, '8e30cc29-ea94-4006-8af0-fe523d560215.jpg', '2025-05-11 03:58:59'),
(38, 'Golden Pothos', 'Epipremnum aureum is a species in the arum family Araceae, native to Mo\'orea in the Society Islands of French Polynesia', 200.00, 23, 'a59375f4-170f-42cf-91e7-7f77c016a723.jpg', '2025-05-11 03:59:35'),
(39, 'Shrimp plant', 'Justicia brandegeeana, the Mexican shrimp plant, shrimp plant or false hop, is an evergreen shrub in the genus Justicia of the acanthus family Acanthaceae, native to Mexico, and also naturalized in Florida. It grows to 1 m tall with spindly limbs. The leaves are oval, green, 3–7.5 cm long', 320.00, 12, '98965e90-d669-4316-8136-51e41e2b6b4b.jpg', '2025-05-11 04:00:29'),
(40, 'Iboga', 'Tabernanthe iboga is an evergreen rainforest shrub native to Central Africa. A member of the Apocynaceae family indigenous to Gabon, the Democratic Republic of Congo, and the Republic of Congo, it is cultivated across Central Africa for its medicinal and other effects.', 600.00, 10, '0c6f6f8c-2e30-4ae1-b37d-6c31cd1768fa.jpg', '2025-05-11 04:01:06'),
(41, 'Terminalia ivorensis', 'Terminalia ivorensis is a species of tree in the family Combretaceae, and is known by the common names of Ivory Coast almond, idigbo, black afara, framire and emeri.', 250.00, 15, '7fb7c431-a9f1-4a09-bb15-a9d33fc05c87.jpg', '2025-05-11 04:01:48'),
(42, 'Bougainvillea', 'Bougainvillea is a genus of thorny ornamental vines, bushes, and trees belonging to the four o\' clock family, Nyctaginaceae. They are native to Brazil, Bolivia, Paraguay, Peru, and Argentina. There are between 4 and 22 species in the genus.', 700.00, 4, '681db7a6-990e-4e1e-b989-54bceafab41f.jpg', '2025-05-11 04:02:19'),
(43, 'Tree philodendron', 'Thaumatophyllum bipinnatifidum is a plant in the genus Thaumatophyllum, in the family Araceae. Previously it was classified in the genus Philodendron within subgenus Meconostigma. The commonly used names Philodendron bipinnatifidum and Philodendron selloanum are synonyms.', 300.00, 5, '0721817d-24d2-4585-a2e1-76ae9440c0cc.jpg', '2025-05-11 04:03:01'),
(44, 'Goeppertia loeseneri', 'Goeppertia loeseneri, the Brazilian star calathea, is a species of plant belonging to the Marantaceae family. It is native to Peru, northern Brazil, Colombia, and Ecuador. It can grow to a height of 1.2m.', 350.00, 14, '8df85acf-3f92-461e-a174-07a43a98f53f.jpg', '2025-05-11 04:03:33'),
(45, 'Ravenala', 'Ravenala madagascariensis, commonly known as the traveller\'s tree, traveller\'s palm or East-West palm, is a species of monocotyledonous flowering plant found in Madagascar. It is not a true palm but a member of the family Strelitziaceae.', 480.00, 10, '5794b3c9-af78-44d8-87ff-6a85f14be92b.jpg', '2025-05-11 04:04:13'),
(46, 'West Indian Lantana', 'Lantana camara is a species of flowering plant in the verbena family, native to the American tropics. It is a very adaptable species, which can inhabit a wide variety of ecosystems; once it has been introduced into a habitat it spreads rapidly; between 45ºN and 45ºS and less than 1,400 metres in altitude', 600.00, 18, '3645f34a-4545-41d7-a951-7c7feb32471d.jpg', '2025-05-11 04:05:00'),
(47, 'Golden Pothos', 'Epipremnum aureum is a species in the arum family Araceae, native to Mo\'orea in the Society Islands of French Polynesia.', 150.00, 32, 'c3c120de-08a5-45e7-837e-6c33efa96b0c.jpg', '2025-05-11 04:05:42'),
(48, 'Common ninebark', 'Physocarpus opulifolius, known as common ninebark, Eastern ninebark, Atlantic ninebark, or simply ninebark, is a species of flowering plant in the rose family Rosaceae, native to eastern North America, named for its peeling multi-hued bark', 460.00, 15, 'a4a9a6d6-f2db-4e26-b563-bb4c21e7ec28.jpg', '2025-05-11 04:06:10'),
(49, 'Pilea nummulariifolia', 'Pilea nummulariifolia is a perennial evergreen herbaceous plant commonly known as creeping charlie native to the Caribbean and northern South America. It can be grown indoors, for example in a hanging pot.', 300.00, 15, '03c89872-96d7-4a55-9abf-18474fe9c467.jpg', '2025-05-11 04:07:01'),
(50, 'Firebush', 'Hamelia patens is a large evergreen perennial shrub or small tree in the family Rubiaceae, that is native to the American subtropics and tropics. Its range extends from Florida in the southern United States to as far south as Argentina. Common names include firebush, hummingbird bush, scarlet bush, and redhead.', 650.00, 9, 'c34d2c76-2220-4ca0-a9ec-98a933adbbd8.jpg', '2025-05-11 04:08:30'),
(51, ' Mangroves', 'Conocarpus is a genus of two species of flowering plants in the family Combretaceae, native to tropical regions of the world. One of the species is a widespread mangrove species, and the other is restricted to a small area around the southern Red Sea coasts, where it grows alongside seasonal rivers', 500.00, 5, '043e9921-a637-40a7-a83c-5c67752e1dff.jpg', '2025-05-11 04:08:59'),
(52, 'Bleeding-heart vine', 'Clerodendrum thomsoniae is a species of flowering plant in the genus Clerodendrum of the family Lamiaceae, native to tropical west Africa from Cameroon west to Senegal. It is an evergreen liana growing to 4 m tall, with ovate to oblong leaves 8–17 cm cm long.', 450.00, 10, '888e1073-efda-4bc8-ac30-1984aa3ab4ff.jpg', '2025-05-11 04:09:40'),
(53, 'Breynia', 'Breynia is a genus in the flowering plant family Phyllanthaceae, first described in 1776. It is native to Southeast Asia, China, Réunion, the Indian Subcontinent, Papuasia and Australia.', 300.00, 15, '60efa815-e26c-45fb-9b24-8bfacdcfed83.jpg', '2025-05-11 04:10:09'),
(54, 'Duranta erecta', 'Duranta erecta is a species of flowering shrub in the verbena family Verbenaceae, native from Mexico to South America and the Caribbean. It is widely cultivated as an ornamental plant in tropical and subtropical gardens throughout the world, and has become naturalized in many places.', 700.00, 12, '2764bc2c-8771-4042-a856-235207facbae.jpg', '2025-05-11 04:10:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `last_name`, `first_name`, `middle_name`, `contact_number`, `address`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin', '0912345678', 'admin', 'admin@mail.com', '$2y$10$qjJ.bxNchTJCNOe/q6qq4OXl.8bmE45cqzeVSM.NGANWliz70vq6K', 'admin'),
(14, 'Santos', 'Eiron', 'Riley', '0912345678', 'Balagtas Bulacan', 'eiron@mail.com', '$2y$10$nLSuQzSzkXEhcJy/A4F/1uDD0SSKPtVQLv5uPGdNnCpAuStEbRwC.', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `completed_orders`
--
ALTER TABLE `completed_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `completed_orders`
--
ALTER TABLE `completed_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
