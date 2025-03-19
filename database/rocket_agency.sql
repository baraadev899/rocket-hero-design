
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rocket_agency`
--
CREATE DATABASE IF NOT EXISTS `rocket_agency` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rocket_agency`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@rocket.com', '$2y$10$J2MXK5JUQ2uqNqY5RnqJ9O9qQHBNFZ/a1VBMBTUCFdI4UU/n2XFl2', '2023-05-10 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `category`, `image`, `link`, `created_at`) VALUES
(1, 'متجر إلكتروني متكامل', 'تصميم وتطوير متجر إلكتروني متكامل لبيع الملابس مع نظام دفع إلكتروني وإدارة مخزون.', 'تطوير مواقع', 'https://images.unsplash.com/photo-1519389950473-47ba0277781c', 'https://example.com', '2023-05-15 09:00:00'),
(2, 'تطبيق للهواتف الذكية', 'تطوير تطبيق للهواتف الذكية لتوصيل الطعام مع ميزات متقدمة للتتبع والدفع.', 'تطوير تطبيقات', 'https://images.unsplash.com/photo-1522542550221-31fd19575a2d', 'https://example.com', '2023-05-16 10:30:00'),
(3, 'حملة تسويقية شاملة', 'إدارة حملة تسويقية شاملة عبر منصات التواصل الاجتماعي لشركة عقارات.', 'تسويق رقمي', 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a', 'https://example.com', '2023-05-17 11:45:00'),
(4, 'موقع تعليمي تفاعلي', 'تصميم وتطوير منصة تعليمية تفاعلية مع نظام إدارة محتوى متكامل.', 'تطوير مواقع', 'https://images.unsplash.com/photo-1610563166150-b34df4f3bcd6', 'https://example.com', '2023-05-18 09:15:00'),
(5, 'هوية بصرية لشركة ناشئة', 'تصميم هوية بصرية متكاملة لشركة ناشئة تشمل الشعار والألوان والخطوط.', 'تصميم', 'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c', 'https://example.com', '2023-05-19 14:20:00'),
(6, 'تحسين محركات البحث', 'تحسين موقع شركة خدمات لتصدر نتائج البحث في محركات البحث المختلفة.', 'SEO', 'https://images.unsplash.com/photo-1562577308-9e66f0c65ce5', 'https://example.com', '2023-05-20 15:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `icon`, `created_at`) VALUES
(1, 'تطوير المواقع الإلكترونية', 'نقدم خدمات تصميم وتطوير مواقع الويب المتجاوبة والمتوافقة مع محركات البحث لتحقيق أهدافك التجارية.', 'fa-code', '2023-05-10 09:00:00'),
(2, 'تطوير تطبيقات الهاتف', 'نطور تطبيقات الجوال لأنظمة iOS وAndroid بتقنيات متطورة تتميز بسهولة الاستخدام والأداء العالي.', 'fa-mobile-alt', '2023-05-10 09:30:00'),
(3, 'التسويق الرقمي', 'نساعدك على الوصول إلى جمهورك المستهدف من خلال استراتيجيات تسويقية فعالة عبر مختلف القنوات الرقمية.', 'fa-bullhorn', '2023-05-10 10:00:00'),
(4, 'تحسين محركات البحث (SEO)', 'نعمل على تحسين ظهور موقعك في نتائج البحث لزيادة الزيارات وتحسين معدلات التحويل.', 'fa-search', '2023-05-10 10:30:00'),
(5, 'تصميم الهوية البصرية', 'نصمم هويات بصرية تعكس قيم وأهداف علامتك التجارية وتميزها عن المنافسين في السوق.', 'fa-paint-brush', '2023-05-10 11:00:00'),
(6, 'إدارة وسائل التواصل الاجتماعي', 'ندير حسابات علامتك التجارية على منصات التواصل الاجتماعي ونطور استراتيجيات محتوى فعالة.', 'fa-share-alt', '2023-05-10 11:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_title` varchar(100) NOT NULL,
  `site_description` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_title`, `site_description`, `email`, `phone`, `address`, `facebook`, `twitter`, `instagram`, `linkedin`) VALUES
(1, 'روكيت', 'شركة رائدة في مجال التسويق الإلكتروني وتطوير المواقع والتطبيقات', 'info@rocketagency.com', '+966 123 456 789', 'الرياض، المملكة العربية السعودية', 'https://facebook.com/rocketagency', 'https://twitter.com/rocketagency', 'https://instagram.com/rocketagency', 'https://linkedin.com/company/rocketagency');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `position`, `image`, `facebook`, `twitter`, `instagram`, `linkedin`) VALUES
(1, 'محمد أحمد', 'المدير التنفيذي', 'https://randomuser.me/api/portraits/men/1.jpg', '#', '#', '#', '#'),
(2, 'سارة العلي', 'مديرة التسويق', 'https://randomuser.me/api/portraits/women/1.jpg', '#', '#', '#', '#'),
(3, 'خالد محمد', 'مطور رئيسي', 'https://randomuser.me/api/portraits/men/2.jpg', '#', '#', '#', '#'),
(4, 'نورا السعيد', 'مصممة جرافيك', 'https://randomuser.me/api/portraits/women/2.jpg', '#', '#', '#', '#'),
(5, 'يوسف عبدالله', 'أخصائي تحسين محركات البحث', 'https://randomuser.me/api/portraits/men/3.jpg', '#', '#', '#', '#'),
(6, 'ليلى العمري', 'مديرة المشاريع', 'https://randomuser.me/api/portraits/women/3.jpg', '#', '#', '#', '#');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
