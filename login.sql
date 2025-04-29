-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-04-2025 a las 16:54:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `login`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigo`
--

CREATE TABLE `codigo` (
  `id_codigo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `codigo` varchar(6) NOT NULL,
  `expiracion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `codigo`
--

INSERT INTO `codigo` (`id_codigo`, `id_usuario`, `codigo`, `expiracion`) VALUES
(1, 22, '959057', '2024-09-23 22:21:45'),
(2, 22, '873734', '2024-09-23 22:21:57'),
(3, 22, '492746', '2024-09-23 22:28:03'),
(4, 22, '653220', '2024-09-23 22:30:44'),
(5, 22, '456876', '2024-09-23 22:30:51'),
(6, 22, '837681', '2024-09-23 22:31:02'),
(7, 22, '448990', '2024-09-24 19:41:14'),
(8, 22, '389474', '2024-09-24 19:41:33'),
(9, 22, '706687', '2024-09-24 19:41:34'),
(10, 22, '423938', '2024-09-24 19:41:35'),
(11, 22, '479550', '2024-09-24 19:41:36'),
(12, 22, '303493', '2024-09-24 19:41:36'),
(13, 22, '741315', '2024-09-24 19:41:37'),
(14, 22, '854122', '2024-09-24 19:41:37'),
(15, 22, '189218', '2024-09-24 19:41:38'),
(16, 22, '325978', '2024-09-24 19:41:38'),
(17, 22, '402427', '2024-09-24 19:41:38'),
(18, 22, '909715', '2024-09-24 19:41:40'),
(19, 22, '455385', '2024-09-24 19:41:40'),
(20, 22, '630568', '2024-09-24 19:41:58'),
(21, 22, '100770', '2024-09-24 19:41:58'),
(22, 22, '332216', '2024-09-24 19:41:58'),
(23, 22, '465332', '2024-09-24 19:44:09'),
(24, 22, '973295', '2024-09-24 19:52:50'),
(25, 22, '601067', '2024-09-24 19:54:06'),
(26, 22, '334973', '2024-09-24 19:57:21'),
(27, 22, '950175', '2024-09-24 19:59:55'),
(28, 22, '546591', '2024-09-24 20:04:13'),
(29, 22, '334417', '2024-09-24 20:06:14'),
(30, 22, '629701', '2024-09-24 20:10:34'),
(31, 22, '395380', '2024-09-24 20:10:44'),
(32, 22, '423010', '2024-09-24 20:12:05'),
(33, 22, '305921', '2024-09-24 20:41:13'),
(34, 22, '406635', '2024-09-24 20:41:19'),
(69, 22, '150240', '2024-09-30 21:29:53'),
(73, 22, '900467', '2024-10-08 20:38:54'),
(76, 48, '674154', '2024-10-25 22:35:29'),
(78, 43, '267044', '2024-11-25 18:08:50'),
(80, 22, '745936', '2024-11-25 19:15:06'),
(81, 22, '117051', '2024-11-26 17:50:17'),
(82, 22, '581252', '2024-11-26 17:51:28'),
(83, 49, '168036', '2024-11-28 16:36:38'),
(0, 1, '924291', '2025-03-27 23:25:50'),
(0, 4, '180452', '2025-04-07 15:15:03'),
(0, 1, '641264', '2025-04-08 10:53:11'),
(0, 1, '491582', '2025-04-08 11:49:09'),
(0, 1, '417922', '2025-04-08 11:51:07'),
(0, 11, '449956', '2025-04-08 11:52:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_dispositivos`
--

CREATE TABLE `configuracion_dispositivos` (
  `id_configuracion` int(11) NOT NULL,
  `id_dispositivo` int(11) NOT NULL,
  `parametro` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `direccion_id` int(11) NOT NULL,
  `calle` varchar(255) NOT NULL,
  `numero` int(11) NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `codigo postal` int(11) NOT NULL,
  `pais` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivos`
--

CREATE TABLE `dispositivos` (
  `id_dispositivo` int(11) NOT NULL,
  `id_unico` varchar(50) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `tipo_medidor` varchar(50) NOT NULL,
  `parametros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parametros`)),
  `estado` enum('activo','inactivo','mantenimiento') DEFAULT 'activo',
  `id_usuario` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invitaciones`
--

CREATE TABLE `invitaciones` (
  `id_invitacion` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `estado` enum('pendiente','aceptada','rechazada','expirada') DEFAULT 'pendiente',
  `fecha_expiracion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mediciones`
--

CREATE TABLE `mediciones` (
  `id_medicion` int(11) NOT NULL,
  `id_dispositivo` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `unidad` varchar(20) NOT NULL,
  `fecha_medicion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`, `created_at`) VALUES
(1, 'admin', 'Administrador del sistema con acceso total', '2025-03-28 01:49:49'),
(2, 'usuario', 'Usuario normal con acceso limitado', '2025-03-28 01:49:49'),
(3, 'supervisor', 'Supervisor con acceso a gestión de usuarios', '2025-03-28 01:49:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `direccion_id` int(11) NOT NULL,
  `rol` float NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `email`, `contrasena`, `direccion_id`, `rol`, `id_rol`, `created_at`, `updated_at`) VALUES
(1, 'brunito', 'barber', 'brunocameille@alumnos.itr3.edu.ar', '$2y$10$F4xQfULyrVhG1JD8Y21QR.vgn9Fzd257acxjmQqHQ3OYfEqBaAPb6', 0, 0, 1, '2025-04-07 16:37:30', '2025-04-07 23:16:03'),
(2, 'Enzito', 'Guerreiro', 'enzito@gmail.com', '$2y$10$jzwxYVyrdKct.o/37a.m0eeN.rK3Z.1f0oDqD0VgRTIfsSO6Kyccy', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(3, 'mateo', 'cameille', 'mateito@gmail.com', '$2y$10$E3dKXizu.mLU5j0cUk/Iw.I.j1SsGZidsYiHGXZmvT/YHFf6FrQ0C', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(4, 'Enzo', 'Guerreiro', 'enzoguerreiro76@gmail.com', '$2y$10$7LyF68fAWDiExMusIFa3.ex0H5Kfc4/rUjYLh8T6Udr3B4hy.6IVi', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(5, 'ivo', 'Ferrer', 'ivojaja@gmail.com', '$2y$10$CqbMhLsdXSOPn1W3w3ngu.HIyM3puLJaoxIRIw4KSjlRPaZgdnvAu', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 17:35:44'),
(6, 'Enzito', 'Guerreiro', 'enzoguerreiro@alumnos.itr3.edu.ar', '$2y$10$6L7CavehkQe/oKqbpaKwMuDB22Krdw4lgWhKwSzHh4TgCt45LHY4u', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 17:36:07'),
(7, 'manu', 'urrutia', 'manuel@gmail.com', '$2y$10$imD4jb17C1jcSSPJ.B9tfOlNtt/Dr7QRorjXSQppr3w6Yv0qaNz2u', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 17:36:40'),
(8, 'holanda', 'jaja', 'Holanda@gmail.com', '$2y$10$oMLV.8SEaFDrTxquJXZt5eSlje65FJ/.9R9JvViUIhdVDCoSXV4hG', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(10, 'enzo', 'guerreiro', 'ennnzo757@gmail.com', '$2y$10$.REx8jQNh91IsVy8ty56h.fjWJ83mqTDYx1eVOHz19DvEACdmv3je', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(11, 'cristal', 'banza', 'cricri123@gmail.com', '$2y$10$aUqNJgJVxNz9OUP/vIHmSucKxJLvIZN/v4SkCbbcK6sskctN5W8Ee', 0, 0, 1, '2025-04-07 16:37:30', '2025-04-07 23:24:29'),
(12, 'coker', 'basile', 'cocobasile@gmail.com', '$2y$10$l8L8/qExxJRt1VnwaifymeD8jNTDFf9a0DDFNvuk3xxbco./vu5Fe', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(13, 'dario', 'BERAGALIA', 'dariobergagna@gmail.com', '$2y$10$Xdki8XC1qjdrXhXX/Yd48uQdtBiwiLMeTo5HvQMDxXj7KiR2shDoC', 0, 0, 1, '2025-04-07 16:37:30', '2025-04-08 14:51:19'),
(14, 'cristian ', 'JAJAJAJ', 'cristian@gmail.com', '$2y$10$YPAM8gcoLySBVuxY3zUp7OdT1bwOUpaYAnHPw4cVVMobiw4cQyWwC', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(15, 'serfio', 'campo', 'sergio@gmail.com', '$2y$10$AxTvxLy7T2S6hx/n7uT3Ye0i8iCHVyUNlpfIzd9Z7muNmyzt.S/Nq', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(16, 'jose', 'luis', 'josesinnn@gmail.com', '$2y$10$anBDz9Q87s3O9hEFFwCE/OmFcnCDczNXl4DXKrdHZxmf/vCCdKiwC', 0, 0, 2, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(17, 'yayin', 'cameille', 'yayin@gmail.com', '$2y$10$OAzh7jiRiYF/XRZQatD68O6P30e28OWqMPJumIa8W2WOk/8C39ipO', 0, 0, 1, '2025-04-07 16:37:30', '2025-04-07 16:37:30'),
(20, 'sasha', 'ferro', 'sasha@gmail.com', '$2y$10$fjJnPro4sAhNdAdVCMRppum3dL/uFApMBvKrSO5vpSL7/MQwKhDGi', 0, 0, 1, '2025-04-07 18:01:22', '2025-04-08 14:51:28'),
(22, 'ennzo', 'fernandez', 'enzof@gmail.com', '$2y$10$7Jefk2Lft0.raSv9aMh6b.nm0.htn5tOXNWtW/JjwrvDyA/hcjeJa', 0, 0, 1, '2025-04-08 14:09:37', '2025-04-08 14:09:37');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion_dispositivos`
--
ALTER TABLE `configuracion_dispositivos`
  ADD PRIMARY KEY (`id_configuracion`),
  ADD KEY `id_dispositivo` (`id_dispositivo`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`direccion_id`);

--
-- Indices de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`id_dispositivo`),
  ADD UNIQUE KEY `id_unico` (`id_unico`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `invitaciones`
--
ALTER TABLE `invitaciones`
  ADD PRIMARY KEY (`id_invitacion`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indices de la tabla `mediciones`
--
ALTER TABLE `mediciones`
  ADD PRIMARY KEY (`id_medicion`),
  ADD KEY `id_dispositivo` (`id_dispositivo`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion_dispositivos`
--
ALTER TABLE `configuracion_dispositivos`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `direccion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  MODIFY `id_dispositivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invitaciones`
--
ALTER TABLE `invitaciones`
  MODIFY `id_invitacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mediciones`
--
ALTER TABLE `mediciones`
  MODIFY `id_medicion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`