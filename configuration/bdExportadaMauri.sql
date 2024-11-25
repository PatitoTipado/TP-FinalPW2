-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS preguntados;

-- Usar la base de datos preguntados
USE preguntados;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 22:44:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `preguntados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
                              `id` int(11) NOT NULL,
                              `nombre_de_categoria` varchar(100) NOT NULL,
                              `color_de_categoria` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre_de_categoria`, `color_de_categoria`) VALUES
                                                                                 (1, 'Historia de Argentina', '#FF5733'),
                                                                                 (2, 'deporte argentino', '#81D4FA'),
                                                                                 (3, 'Arte y Literatura', '#8E24AA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE `opciones` (
                            `id` int(11) NOT NULL,
                            `pregunta_id` int(11) NOT NULL,
                            `opcion1` varchar(255) NOT NULL,
                            `opcion2` varchar(255) NOT NULL,
                            `opcion3` varchar(255) NOT NULL,
                            `opcion_correcta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `opciones`
--

INSERT INTO `opciones` (`id`, `pregunta_id`, `opcion1`, `opcion2`, `opcion3`, `opcion_correcta`) VALUES
                                                                                                     (1, 1, 'Samba', 'Cumbia', 'Zamba', 'Tango'),
                                                                                                     (2, 2, 'Dólar', 'Euro', 'Real', 'Peso argentino'),
                                                                                                     (3, 3, 'Simón Bolívar', 'Manuel Belgrano', 'Eva Perón', 'José de San Martín'),
                                                                                                     (4, 4, 'Mendoza', 'Córdoba', 'Rosario', 'Buenos Aires'),
                                                                                                     (5, 5, 'Paella', 'Pizza', 'Empanadas', 'Asado'),
                                                                                                     (6, 6, 'Monte Everest', 'Cerro Torre', 'Mont Blanc', 'Aconcagua'),
                                                                                                     (7, 7, 'Montevideo', 'Santiago', 'La Paz', 'Buenos Aires'),
                                                                                                     (8, 8, 'Julio Cortázar', 'Adolfo Bioy Casares', 'Ricardo Piglia', 'Jorge Luis Borges'),
                                                                                                     (9, 9, 'Independiente', 'River Plate', 'San Lorenzo', 'Boca Juniors'),
                                                                                                     (10, 10, '1810', '1821', '1806', '1816'),
                                                                                                     (11, 11, 'Domingo Faustino Sarmiento', 'Manuel de Rosas', 'Bartolomé Mitre', 'Bernardino Rivadavia'),
                                                                                                     (12, 12, 'Guerra de la Triple Alianza', 'Guerra Civil', 'Guerra del Pacífico', 'Guerra de Malvinas'),
                                                                                                     (13, 13, 'Buenos Aires', 'Salta', 'Santiago del Estero', 'Córdoba'),
                                                                                                     (14, 14, 'Poblador de las pampas', 'Vigilante de los gauchos', 'Habitante de las montañas', 'Vaquero de las pampas'),
                                                                                                     (15, 15, 'Madre Teresa', 'Eva Perón', 'María Eva Duarte', 'Madre de Plaza de Mayo'),
                                                                                                     (16, 16, '1985', '2001', '1978', '1994'),
                                                                                                     (17, 17, 'Radicalismo', 'Socialismo', 'Liberalismo', 'Peronismo'),
                                                                                                     (18, 18, 'La Avenida de Mayo', 'La Calle Florida', 'La Plaza San Martín', 'La Avenida 9 de Julio'),
                                                                                                     (19, 19, '1974', '1930', '1990', '1978'),
                                                                                                     (20, 20, 'Claudio Caniggia', 'Jorge Valdano', 'Daniel Passarella', 'Diego Maradona'),
                                                                                                     (21, 21, 'Lionel Messi', 'Manu Ginóbili', 'Gabriel Batistuta', 'Carlos Tévez'),
                                                                                                     (22, 22, 'San Lorenzo', 'River Plate', 'Independiente', 'Boca Juniors'),
                                                                                                     (23, 23, 'Lanús', 'Banfield', 'River Plate', 'Independiente'),
                                                                                                     (24, 24, 'Mendoza', 'Buenos Aires', 'Córdoba', 'Rosario'),
                                                                                                     (25, 25, 'Andrés Nocioni', 'Luis Scola', 'Walter Herrmann', 'Manu Ginóbili'),
                                                                                                     (26, 26, 'Lionel Messi', 'Carlos Tévez', 'Ángel Di María', 'Maxi Rodríguez'),
                                                                                                     (27, 27, '15', '18', '10', '12'),
                                                                                                     (28, 28, 'José Froilán González', 'Carlos Reutemann', 'Oscar Larrauri', 'Juan Manuel Fangio'),
                                                                                                     (30, 30, '1', '2', '3', '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
                            `id` int(11) NOT NULL,
                            `usuario_id` int(11) NOT NULL,
                            `fecha_de_partida` datetime NOT NULL,
                            `fecha_de_finalizacion` datetime DEFAULT NULL,
                            `puntaje_total` int(11) DEFAULT 0,
                            `nivel` enum('facil','normal','dificil') NOT NULL,
                            `estado` enum('finalizada','en curso','en revision') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partidas`
--

INSERT INTO `partidas` (`id`, `usuario_id`, `fecha_de_partida`, `fecha_de_finalizacion`, `puntaje_total`, `nivel`, `estado`) VALUES
                                                                                                                                 (1, 3, '2024-11-25 17:12:09', NULL, 1, 'normal', 'en curso'),
                                                                                                                                 (2, 3, '2024-11-25 17:17:53', '2024-11-25 17:18:11', 0, 'normal', 'finalizada'),
                                                                                                                                 (3, 4, '2024-11-25 17:32:17', NULL, 2, 'normal', 'en curso'),
                                                                                                                                 (4, 5, '2024-11-25 17:37:18', NULL, 2, 'normal', 'en curso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
                             `id` int(11) NOT NULL,
                             `categoria_id` int(11) NOT NULL,
                             `pregunta` varchar(255) NOT NULL,
                             `nivel` enum('facil','normal','dificil') DEFAULT 'normal',
                             `tipo_pregunta` enum('creada','sugerida') NOT NULL,
                             `cantidad_apariciones` int(11) DEFAULT 0,
                             `cantidad_veces_respondidas` int(11) DEFAULT 0,
                             `estado` enum('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
                             `fecha_creacion` datetime NOT NULL,
                             `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `categoria_id`, `pregunta`, `nivel`, `tipo_pregunta`, `cantidad_apariciones`, `cantidad_veces_respondidas`, `estado`, `fecha_creacion`, `usuario_id`) VALUES
                                                                                                                                                                                         (1, 1, '¿Cual es el baile tradicional de Argentina que se caracteriza por el abrazo entre los bailarines?', 'facil', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (2, 3, '¿Cual es la moneda Oficial de Argentina?', 'facil', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (3, 1, '¿Quien fue la figura historica argentina conocida como \"El libertador\"?', 'facil', 'creada', 1, 1, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (4, 1, '¿En qué ciudad argentina se encuentra el famoso teatro Colón, conocido por ser uno de los mejores teatros de ópera del mundo?', 'facil', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (5, 3, '¿Cuál es el plato nacional de Argentina que consiste en carne asada a la parrilla?', 'facil', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (6, 1, '¿Qué montaña, ubicada en la frontera entre Argentina y Chile, es la montaña más alta de América del Sur?', 'facil', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (7, 1, '¿Cuál es la capital de Argentina?', 'facil', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (8, 1, '¿Qué escritor argentino es uno de los más famosos del mundo, conocido por su obra \"Ficciones\"?', 'normal', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (9, 2, '¿Cuál es el equipo de fútbol más exitoso de Argentina, con numerosos títulos nacionales e internacionales?', 'facil', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (10, 1, '¿Cuál fue el año en que Argentina declaró su independencia?', 'normal', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (11, 1, '¿Quién fue el primer presidente de Argentina?', 'normal', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (12, 1, '¿Qué conflicto bélico tuvo lugar entre Argentina y el Reino Unido en 1982?', 'normal', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (13, 1, '¿Qué ciudad fue la primera capital de Argentina?', 'normal', 'creada', 1, 1, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (14, 3, '¿Cuál es el significado de la palabra \"gaucho\"?', 'normal', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (15, 1, '¿Qué figura histórica es conocida como \"La madre de la plaza\"?', 'normal', 'creada', 1, 1, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (16, 1, '¿En qué año se realizó la reforma constitucional que estableció la autonomía de la Ciudad de Buenos Aires?', 'normal', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (17, 1, '¿Qué movimiento social y político surgió en Argentina en la década de 1940 bajo el liderazgo de Juan Domingo Perón?', 'normal', 'creada', 0, 0, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (18, 1, '¿Cuál es el nombre del famoso recorrido turístico que incluye la Casa Rosada y el Obelisco?', 'normal', 'creada', 2, 1, 'aprobada', '2024-11-25 17:05:03', 1),
                                                                                                                                                                                         (19, 2, '¿En qué año ganó Argentina su primer Mundial de Fútbol?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-01 14:20:32', 1),
                                                                                                                                                                                         (20, 2, '¿Quién fue el máximo goleador de la Selección Argentina en el Mundial de 1986?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-03 10:15:45', 1),
                                                                                                                                                                                         (21, 2, '¿Qué jugador argentino ganó el premio Olimpia de Oro en 2004?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-05 16:42:12', 1),
                                                                                                                                                                                         (22, 2, '¿Cuál es el club argentino con más títulos internacionales ganados hasta la fecha?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-07 12:30:08', 1),
                                                                                                                                                                                         (23, 2, '¿Qué equipo argentino protagonizó el “Maracanazo” en la final de la Copa Libertadores de 2017?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-09 19:50:22', 1),
                                                                                                                                                                                         (24, 2, '¿En qué ciudad se celebraron los primeros Juegos Panamericanos en los que participó Argentina?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-12 08:15:55', 1),
                                                                                                                                                                                         (25, 2, '¿Quién es el basquetbolista argentino con más puntos anotados en la NBA?', 'dificil', 'creada', 13, 13, 'aprobada', '2024-11-14 20:05:18', 1),
                                                                                                                                                                                         (26, 2, '¿Qué jugador argentino marcó un gol desde mitad de cancha contra Serbia y Montenegro en el Mundial de 2006?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-18 11:40:27', 1),
                                                                                                                                                                                         (27, 2, '¿Cuál es el récord de medallas olímpicas obtenidas por Argentina en un solo evento?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-21 15:25:14', 1),
                                                                                                                                                                                         (28, 2, '¿Qué piloto argentino ganó cinco campeonatos de Fórmula 1?', 'dificil', 'creada', 12, 12, 'aprobada', '2024-11-25 17:05:02', 1),
                                                                                                                                                                                         (30, 2, '1', 'facil', 'sugerida', 0, 0, 'rechazada', '2024-11-25 18:42:34', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta_partida`
--

CREATE TABLE `pregunta_partida` (
                                    `pregunta_id` int(11) NOT NULL,
                                    `partida_id` int(11) NOT NULL,
                                    `respuesta_usuario` varchar(255) DEFAULT NULL,
                                    `respondio_correctamente` enum('bien','mal') DEFAULT NULL,
                                    `usuario_id` int(11) DEFAULT NULL,
                                    `fecha_inicio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pregunta_partida`
--

INSERT INTO `pregunta_partida` (`pregunta_id`, `partida_id`, `respuesta_usuario`, `respondio_correctamente`, `usuario_id`, `fecha_inicio`) VALUES
                                                                                                                                               (3, 4, 'José de San Martín', 'bien', 5, '2024-11-25 17:37:35'),
                                                                                                                                               (13, 1, 'Córdoba', 'bien', 3, '2024-11-25 17:12:09'),
                                                                                                                                               (15, 4, 'Madre de Plaza de Mayo', 'bien', 5, '2024-11-25 17:37:18'),
                                                                                                                                               (18, 2, 'La Plaza San Martín', 'mal', 3, '2024-11-25 17:17:53'),
                                                                                                                                               (18, 3, 'La Avenida 9 de Julio', 'bien', 4, '2024-11-25 17:32:17'),
                                                                                                                                               (25, 3, 'Manu Ginóbili', 'bien', 4, '2024-11-25 17:33:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
                            `id` int(11) NOT NULL,
                            `pregunta_id` int(11) NOT NULL,
                            `usuario_realiza_id` int(11) NOT NULL,
                            `usuario_atiende_id` int(11) DEFAULT NULL,
                            `fecha_reporte` datetime NOT NULL,
                            `fecha_atencion` datetime DEFAULT NULL,
                            `descripcion` text DEFAULT NULL,
                            `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
                            `categoria` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
                            `id` int(11) NOT NULL,
                            `nombre_de_usuario` varchar(50) NOT NULL,
                            `nombre` varchar(100) NOT NULL,
                            `anio_de_nacimiento` int(11) NOT NULL,
                            `email` varchar(100) NOT NULL,
                            `contrasena` varchar(255) NOT NULL,
                            `sexo` enum('M','F','Otro') NOT NULL,
                            `imagen_url` varchar(255) DEFAULT NULL,
                            `fecha_registro` datetime(6) DEFAULT NULL,
                            `estado` enum('activo','inactivo','bloqueado') DEFAULT 'inactivo',
                            `rol` enum('administrador','editor','jugador') DEFAULT 'jugador',
                            `nivel` enum('facil','normal','dificil') DEFAULT 'normal',
                            `cantidad_respuestas_correctas` int(11) DEFAULT 0,
                            `cantidad_preguntas_respondidas` int(11) DEFAULT 0,
                            `hash` int(11) DEFAULT NULL,
                            `puntaje_maximo` int(11) DEFAULT 0,
                            `partida_actual` int(11) DEFAULT 0,
                            `pregunta_actual` int(11) DEFAULT 0,
                            `latitud` varchar(255) DEFAULT NULL,
                            `longitud` varchar(255) DEFAULT NULL,
                            `pais` varchar(255) DEFAULT NULL,
                            `ciudad` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_de_usuario`, `nombre`, `anio_de_nacimiento`, `email`, `contrasena`, `sexo`, `imagen_url`, `fecha_registro`, `estado`, `rol`, `nivel`, `cantidad_respuestas_correctas`, `cantidad_preguntas_respondidas`, `hash`, `puntaje_maximo`, `partida_actual`, `pregunta_actual`, `latitud`, `longitud`, `pais`, `ciudad`) VALUES
                                                                                                                                                                                                                                                                                                                                                           (1, 'Mauri', 'Mauri', 2000, 'mauri@gmail.com', '$2y$10$4qteNWPRGcoasFk7p/A6QelQUAVRnDHXV1DIAVfffvjs.BkqJPXgO', 'M', 'public/Mauri.jpg', '2024-11-25 16:55:34.000000', 'activo', 'administrador', 'normal', 0, 0, 1000, 0, 0, 0, '-34.70081423448705', '-58.628271921348016', 'argentina', 'buenos aires'),
                                                                                                                                                                                                                                                                                                                                                           (2, 'Mariel', 'Mariel', 1996, 'mariel@gmail.com', '$2y$10$bcZ0YFiyWezCBRi0BuR7Bu/Xjn.qDGyMklwt.yWwrm/aPpRIhBJIG', 'F', 'public/Mariel.jpg', '2024-11-25 16:59:33.000000', 'activo', 'editor', 'normal', 0, 0, 444, 0, 0, 0, '-34.64980244483853', '-58.56279814130115', 'argentina', 'buenos aires'),
                                                                                                                                                                                                                                                                                                                                                           (3, 'Usuario', 'Usuario', 2000, 'usuario@gmail.com', '$2y$10$UUZxm0j7Dvhd5eWrzlvu..jwHmxd4t4t0LDQJFiZskHoTBzFvpcHG', 'Otro', 'public/Usuario.jpg', '2024-11-25 17:11:47.000000', 'activo', 'jugador', 'normal', 1, 2, 299, 0, 2, 18, '-34.67109683801353', '-58.567579599781745', 'argentina', 'buenos aires'),
                                                                                                                                                                                                                                                                                                                                                           (4, 'BuenJuagdor', 'BuenJugador', 2010, 'bueno@gmail.com', '$2y$10$uyfrr1dEl1hactANit/pAe5ublaSWjXa6T1U3R/iVGrJc1O71w3B2', 'F', 'public/BuenJuagdor.jpg', '2024-11-25 17:22:18.000000', 'activo', 'jugador', 'normal', 14, 14, 951, 12, 3, 25, '-15.707662769583505', '-48.32937905177136', 'brasil', 'goiás'),
                                                                                                                                                                                                                                                                                                                                                           (5, 'MalJugador', 'malJugador', 1910, 'malJugador@gmail.com', '$2y$10$F9m0gn1UmXnmDMEuGBBweeS6hHtDkJcFXpCq8Y5gAMIMpD671EMSW', 'M', 'public/MalJugador.jpg', '2024-11-25 17:35:30.000000', 'activo', 'jugador', 'normal', 2, 20, 282, 0, 4, 3, '-38.873928539236296', '-72.46944229649839', 'chile', 'región de la araucanía');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
    ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `opciones`
--
ALTER TABLE `opciones`
    ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
-- Indices de la tabla `partidas`
--
ALTER TABLE `partidas`
    ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
    ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pregunta_partida`
--
ALTER TABLE `pregunta_partida`
    ADD PRIMARY KEY (`pregunta_id`,`partida_id`),
  ADD KEY `partida_id` (`partida_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
    ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`),
  ADD KEY `usuario_realiza_id` (`usuario_realiza_id`),
  ADD KEY `usuario_atiende_id` (`usuario_atiende_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_de_usuario` (`nombre_de_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `hash` (`hash`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `opciones`
--
ALTER TABLE `opciones`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `opciones`
--
ALTER TABLE `opciones`
    ADD CONSTRAINT `opciones_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`);

--
-- Filtros para la tabla `partidas`
--
ALTER TABLE `partidas`
    ADD CONSTRAINT `partidas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
    ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `preguntas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pregunta_partida`
--
ALTER TABLE `pregunta_partida`
    ADD CONSTRAINT `pregunta_partida_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  ADD CONSTRAINT `pregunta_partida_ibfk_2` FOREIGN KEY (`partida_id`) REFERENCES `partidas` (`id`),
  ADD CONSTRAINT `pregunta_partida_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
    ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  ADD CONSTRAINT `reportes_ibfk_2` FOREIGN KEY (`usuario_realiza_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reportes_ibfk_3` FOREIGN KEY (`usuario_atiende_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
