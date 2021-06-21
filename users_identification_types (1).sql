-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 21-06-2021 a las 12:18:56
-- Versión del servidor: 8.0.25
-- Versión de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `users_identification_types`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_user_id` (IN `id` INT)  DELETE FROM users WHERE users.id = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user` (IN `identification_type_id` INT, IN `first_name` VARCHAR(255), IN `last_name` VARCHAR(255), IN `email` VARCHAR(255), IN `phone` CHAR(255), IN `birthday` DATE, IN `created_at` TIMESTAMP, IN `password` VARCHAR(255))  INSERT INTO users (identification_type_id,first_name,last_name,email,phone,birthday,created_at,password) VALUES (identification_type_id,first_name,last_name,email,phone,birthday,created_at,password)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `select_all_users` ()  SELECT users.*, identification_types.description as identification_types_description,identification_types.name as identification_types_name FROM users JOIN identification_types ON users.identification_type_id = identification_types.id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `select_last_user` ()  SELECT * FROM users ORDER BY id DESC LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `select_user_id` (IN `id` INT)  SELECT users.*, identification_types.id as identification_types_id  FROM users JOIN identification_types ON users.identification_type_id = identification_types.id WHERE users.id = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_id` (IN `id` INT(255), IN `identification_type_id` INT(255), IN `first_name` VARCHAR(255), IN `last_name` VARCHAR(255), IN `email` VARCHAR(255), IN `inphone` VARCHAR(255), IN `inbirthday` DATE, IN `updated_at` TIMESTAMP)  UPDATE users SET identification_type_id = identification_type_id, first_name = first_name, last_name = last_name, email = email, 
phone= inphone, birthday = inbirthday, updated_at = updated_at WHERE users.id = id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `identification_types`
--

CREATE TABLE `identification_types` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `identification_types`
--

INSERT INTO `identification_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'CD', 'Cedula', NULL, NULL),
(2, 'DNIee', 'Cedula DNE', NULL, '2021-06-21 08:00:52'),
(4, 'PEPttt', 'Permiso de permanencia', NULL, NULL),
(8, 'PEP', 'Permiso de permanencia', NULL, NULL),
(12, 'dd', 'Permiso de permanencia', '2021-06-21 08:43:47', '2021-06-21 08:43:47'),
(14, 'Sally', 'algo', '2021-06-21 19:41:49', '2021-06-21 19:41:49'),
(15, 'dolore', 'quia', '2021-06-21 19:52:22', '2021-06-21 19:52:22'),
(16, 'fuga', 'fuga', '2021-06-21 19:56:13', '2021-06-21 19:56:13'),
(17, 'aut', 'eum', '2021-06-21 19:57:57', '2021-06-21 19:57:57'),
(18, 'fugiat', 'dolor', '2021-06-21 20:04:09', '2021-06-21 20:04:09'),
(19, 'omnis', 'alias', '2021-06-21 20:06:15', '2021-06-21 20:06:15'),
(20, 'aut', 'sit', '2021-06-21 20:09:44', '2021-06-21 20:09:44'),
(21, 'dolore', 'reprehenderit', '2021-06-21 20:09:44', '2021-06-21 20:09:44'),
(22, 'ex', 'quas', '2021-06-21 20:10:18', '2021-06-21 20:10:18'),
(23, 'fugiat', 'explicabo', '2021-06-21 20:10:18', '2021-06-21 20:10:18'),
(24, 'magnam', 'dignissimos', '2021-06-21 20:11:33', '2021-06-21 20:11:33'),
(25, 'quo', 'ullam', '2021-06-21 20:11:33', '2021-06-21 20:11:33'),
(26, 'aut', 'quis', '2021-06-21 20:11:40', '2021-06-21 20:11:40'),
(27, 'minima', 'similique', '2021-06-21 20:11:40', '2021-06-21 20:11:40'),
(28, 'eius', 'nihil', '2021-06-21 20:12:30', '2021-06-21 20:12:30'),
(29, 'totam', 'rem', '2021-06-21 20:12:30', '2021-06-21 20:12:30'),
(31, 'praesentium', 'impedit', '2021-06-21 20:12:47', '2021-06-21 20:12:47'),
(32, 'mollitia', 'culpa', '2021-06-21 20:12:47', '2021-06-21 20:12:47'),
(34, 'ducimus', 'sequi', '2021-06-21 20:29:42', '2021-06-21 20:29:42'),
(35, 'consequatur', 'harum', '2021-06-21 20:42:16', '2021-06-21 20:42:16'),
(36, 'ea', 'atque', '2021-06-21 20:42:40', '2021-06-21 20:42:40'),
(37, 'iusto', 'sit', '2021-06-21 20:43:37', '2021-06-21 20:43:37'),
(38, 'sunt', 'vel', '2021-06-21 20:54:26', '2021-06-21 20:54:26'),
(39, 'ducimus', 'et', '2021-06-21 20:54:40', '2021-06-21 20:54:40'),
(40, 'aut', 'at', '2021-06-21 20:56:10', '2021-06-21 20:56:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `identification_type_id` int UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `identification_type_id`, `first_name`, `last_name`, `email`, `phone`, `birthday`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(7, 1, 'Alvaro', 'Padilla', 'ajpadilla88@gmail.com', '3103106185', '1988-07-07', NULL, 'eeeeee', NULL, '2021-06-21 03:13:59', NULL),
(9, 8, 'Larry', 'Bustamanate', 'tego_07_07@hotmail.com', '4567890', '1988-07-08', NULL, '123456', NULL, '2021-06-21 08:44:38', '2021-06-21 09:03:12'),
(10, 34, 'Edmund Schumm', 'Kuphal', 'jesus.kerluke@langworth.com', '1-623-890-1755', '2009-12-19', NULL, '123456', NULL, '2021-06-21 20:29:42', NULL),
(11, 35, 'Kieran Heathcote', 'Sauer', 'virginia41@gmail.com', '936-252-6132', '2006-09-21', NULL, '123456', NULL, '2021-06-21 20:42:16', NULL),
(12, 36, 'Jillian Legros Jr.', 'Mraz', 'hintz.dominic@hahn.net', '+16513944107', '2014-04-26', NULL, '123456', NULL, '2021-06-21 20:42:40', NULL),
(13, 37, 'Amy Balistreri', 'Hamill', 'monica60@gmail.com', '862.815.4322', '1987-01-17', NULL, '123456', NULL, '2021-06-21 20:43:37', NULL),
(14, 38, 'Prof. Susanna Lubowitz', 'Miller', 'medhurst.destinee@predovic.com', '1-925-572-1326', '1992-05-11', NULL, '123456', NULL, '2021-06-21 20:54:26', NULL),
(17, 4, 'Paul', 'Alcantara', 'lenyn@gmail.com', '444444', '1988-09-08', NULL, '123456', NULL, '2021-06-21 21:04:17', '2021-06-21 21:04:29');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `identification_types`
--
ALTER TABLE `identification_types`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_identification_type_id_foreign` (`identification_type_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `identification_types`
--
ALTER TABLE `identification_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_identification_type_id_foreign` FOREIGN KEY (`identification_type_id`) REFERENCES `identification_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
