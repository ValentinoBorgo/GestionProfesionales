-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-02-2025 a las 16:55:38
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `laravel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ausencias`
--

CREATE TABLE `ausencias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ausencias`
--

INSERT INTO `ausencias` (`id`, `motivo`, `fecha_inicio`, `fecha_fin`, `id_usuario`, `created_at`, `updated_at`) VALUES
(1, 'vacaciones', '2025-02-19', '2025-02-28', 5, '2025-02-13 22:53:59', '2025-02-13 22:53:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_turnos`
--

CREATE TABLE `estado_turnos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estado_turnos`
--

INSERT INTO `estado_turnos` (`id`, `codigo`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'ASIGNADO', 'Asignado', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(2, 'REPROGRAMADO', 'Reprogramado', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(3, 'CANCELADO_CLIENTE', 'Cancelado por el cliente', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(4, 'CANCELADO_PROFESIONAL', 'Cancelado por el profesional', '2025-02-13 22:10:29', '2025-02-13 22:10:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha_medica`
--

CREATE TABLE `ficha_medica` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `edad` varchar(255) DEFAULT NULL,
  `fecha_nac` datetime DEFAULT NULL,
  `ocupacion` varchar(255) DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `localidad` varchar(255) DEFAULT NULL,
  `provincia` varchar(255) DEFAULT NULL,
  `persona_responsable` varchar(255) DEFAULT NULL,
  `vinculo` varchar(255) DEFAULT NULL,
  `dni` varchar(255) DEFAULT NULL,
  `telefono_persona_responsable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ficha_medica`
--

INSERT INTO `ficha_medica` (`id`, `created_at`, `updated_at`, `nombre`, `apellido`, `email`, `edad`, `fecha_nac`, `ocupacion`, `domicilio`, `telefono`, `localidad`, `provincia`, `persona_responsable`, `vinculo`, `dni`, `telefono_persona_responsable`) VALUES
(1, '2025-02-13 22:17:01', '2025-02-13 22:17:01', 'Gustavo', 'Rodriguez', 'mekacozzicalvo@gmail.com', '24', '2000-05-13 00:00:00', 'plomero', 'domicilio de jaume 1212', '12334567', 'santo tome', 'santa fe', 'fulana', 'mama', '41941045', '12334567'),
(2, '2025-02-13 22:17:54', '2025-02-13 22:17:54', 'Facunda', 'Perez', 'osvaldocozzicalvo@gmail.com', '27', '1997-05-13 00:00:00', 'desempleado', 'domicilio de jaume 1212', '12334567', 'santo tome', 'santa fe', 'pepito', 'hermano', '37893845', '353221334'),
(3, '2025-02-13 22:19:49', '2025-02-13 22:19:49', 'Lucas', 'Jaume', 'mekacozzicalvo1@gmail.com', '22', '2002-12-11 00:00:00', 'estudiante', 'calle ejemplo 123', '3243243223', 'Parana', 'Entre Rios', 'Pedro Sanchez', 'tio', '45438409', '3434323234'),
(4, '2025-02-13 22:33:04', '2025-02-13 22:33:04', 'Jose', 'Cardilli', 'meka__cozzi@hotmail.com', '58', '1966-09-26 00:00:00', 'Docente', 'domicilio 123', '3424410698', 'Santa Fe', 'santa fe', 'No tiene', '-', '12388732', '-');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_08_19_204942_create_roles_table', 1),
(6, '2024_08_19_205231_create_permisos_table', 1),
(7, '2024_08_19_205317_create_rol_permiso_table', 1),
(8, '2024_08_19_205318_create_secretarios_table', 1),
(9, '2024_08_19_205325_create_profesionales_table', 1),
(10, '2024_08_28_133725_create_sucursals_table', 1),
(11, '2024_08_28_134546_create_sucursal_user_table', 1),
(12, '2024_08_28_165507_create_ficha_medicas_table', 1),
(13, '2024_08_28_165508_create_pacientes_table', 1),
(14, '2024_08_28_170346_create_usuario_rol_table', 1),
(15, '2024_11_26_204109_create_tipo_persona_table', 1),
(16, '2024_12_04_134136_create_tipo_turnos_table', 1),
(17, '2024_12_04_134158_create_estado_turnos_table', 1),
(18, '2024_12_04_134208_create_turnos_table', 1),
(19, '2024_12_06_124151_insertar_turnos_table', 1),
(20, '2024_12_07_195020_create_ausencias_table', 1),
(21, '2024_12_11_124520_create_salas_table', 1),
(22, '2024_12_11_135428_add_id_sala_to_turnos_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_alta` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_ficha_medica` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id`, `fecha_alta`, `created_at`, `updated_at`, `id_ficha_medica`) VALUES
(1, '2025-02-13 19:17:01', '2025-02-13 22:17:01', '2025-02-13 22:17:01', 1),
(2, '2025-02-13 19:17:54', '2025-02-13 22:17:54', '2025-02-13 22:17:54', 2),
(3, '2025-02-13 19:19:49', '2025-02-13 22:19:49', '2025-02-13 22:19:49', 3),
(4, '2025-02-13 19:33:04', '2025-02-13 22:33:04', '2025-02-13 22:33:04', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesional`
--

CREATE TABLE `profesional` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_persona` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesional`
--

INSERT INTO `profesional` (`id`, `titulo`, `created_at`, `updated_at`, `id_persona`) VALUES
(1, 'Hematologo', '2025-02-13 22:14:19', '2025-02-13 22:14:19', 3),
(2, 'Deportologo', '2025-02-13 22:45:35', '2025-02-13 22:45:35', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'ROLE_ADMIN', '2025-02-13 22:10:28', '2025-02-13 22:10:28'),
(2, 'ROLE_SECRETARIO', '2025-02-13 22:10:28', '2025-02-13 22:10:28'),
(3, 'ROLE_PROFESIONAL', '2025-02-13 22:10:28', '2025-02-13 22:10:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `id_permiso` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `id_sucursal` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id`, `tipo`, `id_sucursal`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Consulta', 1, 'Sala de Consulta 1', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(2, 'Cirugía', 1, 'Sala de Cirugía 1', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(3, 'Rehabilitación', 1, 'Sala de Rehabilitación 1', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(4, 'Consulta', 2, 'Sala de Consulta 2', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(5, 'Cirugía', 2, 'Sala de Cirugía 2', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(6, 'Rehabilitación', 2, 'Sala de Rehabilitación 2', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(7, 'Consulta', 3, 'Sala de Consulta 3', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(8, 'Cirugía', 3, 'Sala de Cirugía 3', '2025-02-13 22:10:29', '2025-02-13 22:10:29'),
(9, 'Rehabilitación', 3, 'Sala de Rehabilitación 3', '2025-02-13 22:10:29', '2025-02-13 22:10:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secretario`
--

CREATE TABLE `secretario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `fecha_baja` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secretario`
--

INSERT INTO `secretario` (`id`, `id_usuario`, `fecha_baja`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, '2025-02-13 22:13:02', '2025-02-13 22:13:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `provincia` varchar(255) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `codigo_postal` varchar(255) DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `horario_apertura` time DEFAULT NULL,
  `horario_cierre` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id`, `created_at`, `updated_at`, `nombre`, `direccion`, `ciudad`, `provincia`, `razon_social`, `codigo_postal`, `telefono`, `horario_apertura`, `horario_cierre`) VALUES
(1, '2025-02-13 22:10:28', '2025-02-13 22:10:28', 'Sucursal Centro', 'Av. Principal 123', 'Ciudad Central', 'Provincia Central', 'Sucursal Centro S.A.', '1000', 123456789, '07:00:00', '18:00:00'),
(2, '2025-02-13 22:10:28', '2025-02-13 22:10:28', 'Sucursal Norte', 'Calle Norte 456', 'Ciudad Norteña', 'Provincia Norte', 'Sucursal Norte S.A.', '2000', 987654321, '07:00:00', '18:00:00'),
(3, '2025-02-13 22:10:28', '2025-02-13 22:10:28', 'Sucursal Sur', 'Camino Sur 789', 'Ciudad Sureña', 'Provincia Sur', 'Sucursal Sur S.A.', '3000', 123987456, '07:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal_usuario`
--

CREATE TABLE `sucursal_usuario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `id_sucursal` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sucursal_usuario`
--

INSERT INTO `sucursal_usuario` (`id`, `id_usuario`, `id_sucursal`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 3, 1, NULL, NULL),
(3, 4, 1, NULL, NULL),
(4, 5, 1, NULL, NULL),
(5, 5, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_persona`
--

CREATE TABLE `tipo_persona` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` enum('ADMINISTRADOR','SECRETARIO','PROFESIONAL','PACIENTE') NOT NULL DEFAULT 'PACIENTE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_persona`
--

INSERT INTO `tipo_persona` (`id`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 'ADMINISTRADOR', '2025-02-13 22:10:28', '2025-02-13 22:10:28'),
(2, 'SECRETARIO', '2025-02-13 22:10:28', '2025-02-13 22:10:28'),
(3, 'PROFESIONAL', '2025-02-13 22:10:28', '2025-02-13 22:10:28'),
(4, 'PACIENTE', '2025-02-13 22:10:28', '2025-02-13 22:10:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_turnos`
--

CREATE TABLE `tipo_turnos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_turnos`
--

INSERT INTO `tipo_turnos` (`id`, `codigo`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Consulta', 'Consulta', '2024-12-17 01:23:18', '2024-12-17 01:23:18'),
(2, 'Cirugía', 'Cirugía', '2024-12-17 01:23:18', '2024-12-17 01:23:18'),
(3, 'Rehabilitación', 'Rehabilitación', '2024-12-17 01:23:18', '2024-12-17 01:23:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hora_fecha` datetime NOT NULL,
  `id_profesional` bigint(20) UNSIGNED NOT NULL,
  `id_paciente` bigint(20) UNSIGNED NOT NULL,
  `id_secretario` bigint(20) UNSIGNED NOT NULL,
  `id_tipo_turno` bigint(20) UNSIGNED NOT NULL,
  `id_estado` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_sala` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `hora_fecha`, `id_profesional`, `id_paciente`, `id_secretario`, `id_tipo_turno`, `id_estado`, `created_at`, `updated_at`, `id_sala`) VALUES
(1, '2025-02-14 09:00:32', 1, 4, 1, 1, 4, '2025-02-13 22:34:25', '2025-02-13 23:04:23', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `fecha_nac` datetime DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `apellido`, `telefono`, `edad`, `fecha_nac`, `domicilio`, `id_tipo`, `nombre_usuario`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'vale', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vale@gmail.com', NULL, '$2y$12$kfWLoeJcHUScXzOqEJft6e29FkGzzbT2ZpN9NW1nPVCNe61AdIdZ2', NULL, '2025-02-13 22:10:28', '2025-02-13 22:10:28'),
(2, 'Osvaldo', 'Cozzi', '3425564466', 25, '1999-10-24 19:12:12', '1ro de mayov1059', 2, 'osvaldousuario', 'osvaldo@gmail.com', NULL, '$2y$12$jiGDb6fvKTirs3vB4x64VOBbmFkzJ76fBZ.ruOg.vqxT6mV54Aupm', NULL, '2025-02-13 22:13:02', '2025-02-13 22:13:02'),
(3, 'Valentino', 'Borgo', '342565445', 30, '1994-06-13 22:13:24', 'aristobulo 7240', 3, 'valentinoborgo', 'valentino@gmail.com', NULL, '$2y$12$Zru5PN6AWnfmqVDQW8DzCOUUBAL/Rlif6jsLwFUHDw1f0oagTbdoW', NULL, '2025-02-13 22:14:19', '2025-02-13 22:14:19'),
(4, 'Jose', 'Bay', '3464324345', 38, '1986-06-13 19:14:42', '7 jefes 1000', 1, 'joseusuario', 'jose@gmail.com', NULL, '$2y$12$aMyKEurr20zvwLX1YskdNuv1XocQm08BoPnRKsMEKPYRSvU3xPf4K', NULL, '2025-02-13 22:15:36', '2025-02-13 22:15:36'),
(5, 'Santiago', 'gimenez', '3425567766', 33, '1991-06-13 19:44:23', 'domicilio de santiago 123', 3, 'santiagogimenez', 'santiago@gmail.com', NULL, '$2y$12$eliVB9FAKJXLN0UKFUsy0.RDUP7/9tybed5bRUS8RFP653CKzLz2O', NULL, '2025-02-13 22:45:35', '2025-02-13 22:45:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`id`, `id_usuario`, `id_rol`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-02-13 22:10:28', '2025-02-13 22:10:28'),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 4, 1, NULL, NULL),
(5, 5, 3, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ausencias`
--
ALTER TABLE `ausencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ausencias_id_usuario_foreign` (`id_usuario`);

--
-- Indices de la tabla `estado_turnos`
--
ALTER TABLE `estado_turnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estado_turnos_codigo_unique` (`codigo`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `ficha_medica`
--
ALTER TABLE `ficha_medica`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ficha_medica_email_unique` (`email`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id_ficha_medica_foreign` (`id_ficha_medica`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `profesional`
--
ALTER TABLE `profesional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profesional_id_persona_foreign` (`id_persona`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `rol_permiso_id_permiso_foreign` (`id_permiso`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salas_id_sucursal_foreign` (`id_sucursal`);

--
-- Indices de la tabla `secretario`
--
ALTER TABLE `secretario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `secretario_id_usuario_foreign` (`id_usuario`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sucursal_usuario`
--
ALTER TABLE `sucursal_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sucursal_usuario_id_usuario_foreign` (`id_usuario`),
  ADD KEY `sucursal_usuario_id_sucursal_foreign` (`id_sucursal`);

--
-- Indices de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_turnos`
--
ALTER TABLE `tipo_turnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turnos_id_profesional_foreign` (`id_profesional`),
  ADD KEY `turnos_id_paciente_foreign` (`id_paciente`),
  ADD KEY `turnos_id_secretario_foreign` (`id_secretario`),
  ADD KEY `turnos_id_tipo_turno_foreign` (`id_tipo_turno`),
  ADD KEY `turnos_id_estado_foreign` (`id_estado`),
  ADD KEY `turnos_id_sala_foreign` (`id_sala`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_rol_id_usuario_foreign` (`id_usuario`),
  ADD KEY `usuario_rol_id_rol_foreign` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ausencias`
--
ALTER TABLE `ausencias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estado_turnos`
--
ALTER TABLE `estado_turnos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ficha_medica`
--
ALTER TABLE `ficha_medica`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesional`
--
ALTER TABLE `profesional`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `secretario`
--
ALTER TABLE `secretario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sucursal_usuario`
--
ALTER TABLE `sucursal_usuario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_persona`
--
ALTER TABLE `tipo_persona`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_turnos`
--
ALTER TABLE `tipo_turnos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ausencias`
--
ALTER TABLE `ausencias`
  ADD CONSTRAINT `ausencias_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `paciente_id_ficha_medica_foreign` FOREIGN KEY (`id_ficha_medica`) REFERENCES `ficha_medica` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `profesional`
--
ALTER TABLE `profesional`
  ADD CONSTRAINT `profesional_id_persona_foreign` FOREIGN KEY (`id_persona`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_id_permiso_foreign` FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rol_permiso_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `salas_id_sucursal_foreign` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `secretario`
--
ALTER TABLE `secretario`
  ADD CONSTRAINT `secretario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `sucursal_usuario`
--
ALTER TABLE `sucursal_usuario`
  ADD CONSTRAINT `sucursal_usuario_id_sucursal_foreign` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sucursal_usuario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_id_estado_foreign` FOREIGN KEY (`id_estado`) REFERENCES `estado_turnos` (`id`),
  ADD CONSTRAINT `turnos_id_paciente_foreign` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`),
  ADD CONSTRAINT `turnos_id_profesional_foreign` FOREIGN KEY (`id_profesional`) REFERENCES `profesional` (`id`),
  ADD CONSTRAINT `turnos_id_sala_foreign` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turnos_id_secretario_foreign` FOREIGN KEY (`id_secretario`) REFERENCES `secretario` (`id`),
  ADD CONSTRAINT `turnos_id_tipo_turno_foreign` FOREIGN KEY (`id_tipo_turno`) REFERENCES `tipo_turnos` (`id`);

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `usuario_rol_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_rol_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
