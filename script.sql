-- ####################################################################
-- #                       SCRIPT DE CREACIÓN DE BASE DE DATOS
-- #                         PROYECTO: TICO TRIPS (Ambiente WS)
-- ####################################################################

-- 1. Eliminación de la base de datos existente (solo si existe)
DROP DATABASE IF EXISTS `tico_trips_db`;

-- 2. Creación de la base de datos
CREATE DATABASE `tico_trips_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 3. Uso de la base de datos
USE `tico_trips_db`;

-- ####################################################################
-- #                         TABLAS DE LOOKUP Y GEOGRÁFICAS
-- ####################################################################

-- 4. Tabla de Roles (Lookup para permisos)
CREATE TABLE `roles` (
  `id_rol` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_rol` VARCHAR(50) NOT NULL UNIQUE, -- Ejemplo: 'Cliente', 'Negocio', 'Administrador'
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB;

-- 5. Tabla de Estatus (Para Reservas, Negocios, etc.)
CREATE TABLE `estatus` (
`id_estatus` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`nombre` VARCHAR(50) NOT NULL UNIQUE, -- Ejemplo: 'Activo', 'Inactivo', 'Pendiente'
PRIMARY KEY (`id_estatus`)
) ENGINE=InnoDB;

-- 6. Tabla de Categorías (Para Tipos de Servicios o Negocios)
CREATE TABLE `categorias` (
`id_categoria` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`nombre_categoria` VARCHAR(100) NOT NULL UNIQUE, -- Ejemplo: 'Hospedaje', 'Tour de Aventura', 'Gastronomía'
`descripcion` TEXT,
PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB;

-- 7. Tabla Geográfica (Ubicación: Provincia, Cantón, Distrito)
CREATE TABLE `ubicaciones` (
`id_ubicacion` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`provincia` VARCHAR(100) NOT NULL,
`canton` VARCHAR(100) NOT NULL,
`distrito` VARCHAR(100) NOT NULL,
PRIMARY KEY (`id_ubicacion`)
) ENGINE=InnoDB;

-- ####################################################################
-- #                               TABLAS PRINCIPALES
-- ####################################################################

-- 8. Tabla de Usuarios
CREATE TABLE `usuarios` (
`id_usuario` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`nombre_completo` VARCHAR(255) NOT NULL,
`email` VARCHAR(255) NOT NULL UNIQUE,
`telefono` VARCHAR(20),
`fecha_nacimiento` DATE,
`password_hash` VARCHAR(255) NOT NULL,
    
-- Campos de Perfil de Usuario
`foto_perfil` VARCHAR(500),
`biografia` TEXT,
    
-- Campos de Relación y Estatus
`id_rol` INT UNSIGNED NOT NULL DEFAULT 2, -- Por defecto 2 (Cliente)
`id_estatus` INT UNSIGNED NOT NULL DEFAULT 1, -- Por defecto 1 (Activo)

-- Campos de Auditoría
`fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`ultima_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
PRIMARY KEY (`id_usuario`),
FOREIGN KEY (`id_rol`) REFERENCES `roles`(`id_rol`),
FOREIGN KEY (`id_estatus`) REFERENCES `estatus`(`id_estatus`)
) ENGINE=InnoDB;

-- 9. Tabla de Negocios 
CREATE TABLE `negocios` (
`id_negocio` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`id_usuario_fk` BIGINT UNSIGNED NOT NULL, -- Dueño de la cuenta

-- 1. Datos del Negocio
`nombre_legal` VARCHAR(255) NOT NULL,
`nombre_publico` VARCHAR(255) NOT NULL,
`id_categoria_fk` INT UNSIGNED NOT NULL,   
`descripcion_corta` TEXT,
`telefono_contacto` VARCHAR(20) NOT NULL,
`correo_contacto` VARCHAR(255) NOT NULL,
    
-- 2. Documentación y Hacienda
`tipo_cedula` VARCHAR(20),
`cedula_hacienda` VARCHAR(50) NOT NULL UNIQUE, 
`nombre_representante` VARCHAR(255),
`no_licencia_municipal` VARCHAR(100),
    
-- Rutas de Archivos
`ruta_cedula_frente` VARCHAR(255),
`ruta_cedula_reverso` VARCHAR(255),
`foto_portada` VARCHAR(255),
    
-- 3. Ubicación
`id_ubicacion_fk` INT UNSIGNED NOT NULL,
`direccion_exacta` TEXT,
`link_google_maps` VARCHAR(500),
`link_waze` VARCHAR(500),
    
-- Auditoría
`fecha_solicitud` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`id_estatus` INT UNSIGNED NOT NULL DEFAULT 3, -- Por defecto 3 (Pendiente)
    
PRIMARY KEY (`id_negocio`),
    
-- Definiciones de Llaves Foráneas
FOREIGN KEY (`id_usuario_fk`) REFERENCES `usuarios`(`id_usuario`) ON DELETE CASCADE,
FOREIGN KEY (`id_categoria_fk`) REFERENCES `categorias`(`id_categoria`), 
FOREIGN KEY (`id_ubicacion_fk`) REFERENCES `ubicaciones`(`id_ubicacion`),
FOREIGN KEY (`id_estatus`) REFERENCES `estatus`(`id_estatus`)
) ENGINE=InnoDB;

ALTER TABLE `negocios` 
ADD COLUMN `motivo_rechazo` TEXT NULL AFTER `fecha_solicitud`;

ALTER TABLE `negocios` 
ADD COLUMN `fecha_aprobacion` TIMESTAMP NULL AFTER `fecha_solicitud`;

-- 10. Tabla de Servicios/Productos (Ofrendas del negocio)
CREATE TABLE `servicios` (
`id_servicio` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`id_negocio_fk` BIGINT UNSIGNED NOT NULL,
`id_categoria_fk` INT UNSIGNED NOT NULL,
`titulo` VARCHAR(255) NOT NULL,
`descripcion_corta` TEXT NOT NULL,
`precio_base` DECIMAL(10, 2) NOT NULL,
`duracion_dias` INT UNSIGNED,
`id_estatus` INT UNSIGNED NOT NULL,
PRIMARY KEY (`id_servicio`),
FOREIGN KEY (`id_negocio_fk`) REFERENCES `negocios`(`id_negocio`) ON DELETE CASCADE,
FOREIGN KEY (`id_categoria_fk`) REFERENCES `categorias`(`id_categoria`),
FOREIGN KEY (`id_estatus`) REFERENCES `estatus`(`id_estatus`)
) ENGINE=InnoDB;

-- 11. Tabla de Promociones (Descuentos en Servicios)
CREATE TABLE `promociones` (
`id_promocion` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`id_servicio_fk` BIGINT UNSIGNED NOT NULL,
`codigo_promo` VARCHAR(50) UNIQUE,
`tipo_descuento` ENUM('Porcentaje', 'MontoFijo') NOT NULL,
`valor_descuento` DECIMAL(5, 2) NOT NULL,
`fecha_inicio` DATE NOT NULL,
`fecha_fin` DATE NOT NULL,
`limite_usos` INT UNSIGNED,
PRIMARY KEY (`id_promocion`),
FOREIGN KEY (`id_servicio_fk`) REFERENCES `servicios`(`id_servicio`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 12. Tabla de Reservas
CREATE TABLE `reservas` (
`id_reserva` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`id_usuario_fk` BIGINT UNSIGNED NOT NULL,
`id_servicio_fk` BIGINT UNSIGNED NOT NULL,
`fecha_reserva` DATE NOT NULL,
`hora_reserva` TIME,
`fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`cantidad_personas` INT UNSIGNED NOT NULL,
`total_pagar` DECIMAL(10, 2),
`nombre_contacto` VARCHAR(255),
`email_contacto` VARCHAR(255),
`telefono_contacto` VARCHAR(20),
`tipo_identificacion` VARCHAR(50),
`numero_identificacion` VARCHAR(50),
`pais_residencia` VARCHAR(100),
`id_estatus` INT UNSIGNED NOT NULL, -- Estatus: 'Pendiente Pago', 'Confirmada', 'Cancelada'
PRIMARY KEY (`id_reserva`),
FOREIGN KEY (`id_usuario_fk`) REFERENCES `usuarios`(`id_usuario`),
FOREIGN KEY (`id_servicio_fk`) REFERENCES `servicios`(`id_servicio`),
FOREIGN KEY (`id_estatus`) REFERENCES `estatus`(`id_estatus`)
) ENGINE=InnoDB;

-- 13. Tabla de Pagos/Transacciones
CREATE TABLE `pagos` (
`id_pago` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`id_reserva_fk` BIGINT UNSIGNED NOT NULL,
`monto` DECIMAL(10, 2) NOT NULL,
`metodo_pago` VARCHAR(50),
`referencia_transaccion` VARCHAR(100) UNIQUE,
`fecha_pago` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id_pago`),
FOREIGN KEY (`id_reserva_fk`) REFERENCES `reservas`(`id_reserva`)
) ENGINE=InnoDB;

-- 14. Tabla de Imágenes (Para Servicios y Negocios, inferida de la conexión)
CREATE TABLE `imagenes` (
`id_imagen` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`id_servicio_fk` BIGINT UNSIGNED,
`id_negocio_fk` BIGINT UNSIGNED,
`url_imagen` VARCHAR(500) NOT NULL,
`descripcion` VARCHAR(255),
PRIMARY KEY (`id_imagen`),
FOREIGN KEY (`id_servicio_fk`) REFERENCES `servicios`(`id_servicio`) ON DELETE CASCADE,
FOREIGN KEY (`id_negocio_fk`) REFERENCES `negocios`(`id_negocio`) ON DELETE CASCADE,
-- Restricción para asegurar que solo se adjunte a un servicio O a un negocio
CHECK (`id_servicio_fk` IS NOT NULL OR `id_negocio_fk` IS NOT NULL)
) ENGINE=InnoDB;

-- 15. Tabla de Reseñas/Comentarios (Para calificaciones de servicios)
CREATE TABLE `resenas` (
`id_resena` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`id_usuario_fk` BIGINT UNSIGNED NOT NULL,
`id_servicio_fk` BIGINT UNSIGNED NOT NULL,
`calificacion` INT UNSIGNED NOT NULL, -- Escala 1 a 5 estrellas
`comentario` TEXT,
`fecha_resena` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id_resena`),
FOREIGN KEY (`id_usuario_fk`) REFERENCES `usuarios`(`id_usuario`) ON DELETE CASCADE,
FOREIGN KEY (`id_servicio_fk`) REFERENCES `servicios`(`id_servicio`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 16. Tabla de Cupones B2B (Descuentos para negocios)
CREATE TABLE `cupones_b2b` (
`id_cupon` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`id_negocio_fk` BIGINT UNSIGNED NOT NULL,
`codigo_cupon` VARCHAR(50) NOT NULL UNIQUE,
`tipo_descuento` ENUM('Porcentaje', 'MontoFijo') NOT NULL,
`valor_descuento` DECIMAL(10, 2) NOT NULL,
`fecha_inicio` DATE NOT NULL,
`fecha_fin` DATE NOT NULL,
`usos_restantes` INT UNSIGNED,
`id_estatus` INT UNSIGNED NOT NULL DEFAULT 1, -- Por defecto 1 (Activo)
`fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id_cupon`),
FOREIGN KEY (`id_negocio_fk`) REFERENCES `negocios`(`id_negocio`) ON DELETE CASCADE,
FOREIGN KEY (`id_estatus`) REFERENCES `estatus`(`id_estatus`)
) ENGINE=InnoDB;

-- ####################################################################
-- #                 SCRIPT DE INSERCIÓN DE DATOS INICIALES
-- #                           TABLA: roles
-- ####################################################################

INSERT INTO `roles` (`nombre_rol`) VALUES
('Administrador'),
('Cliente'),
('Comercio'),
('Hospedaje'),
('Tour'),
('Comercio Registrado');

-- ####################################################################
-- #                 SCRIPT DE INSERCIÓN DE DATOS INICIALES
-- #                           TABLA: estatus
-- ####################################################################

INSERT INTO `estatus` (`nombre`) VALUES
('Activo'),
('Inactivo'),
('Pendiente'),
('Rechazado');

-- ####################################################################
-- #                       SCRIPT DE INSERCIÓN DE DATOS INICIALES
-- #                           TABLA: usuarios
-- ####################################################################

-- ⚠️ IMPORTANTE: Este es un hash generado por PHP (password_hash) para la contraseña '123'.
-- En un entorno de desarrollo real, esta variable DEBERÍA generarse dinámicamente.
SET @hashed_password = '$2y$10$PUm.ZIeVnkZguA5BGlNeFuT0Tv63dSmFnZlf4DRtGFDDJ6x23Dp9.'; 

INSERT INTO `usuarios` 
(`nombre_completo`, `email`, `password_hash`, `telefono`, `id_rol`, `id_estatus`) 
VALUES
-- ------------------------------------------------------------------
-- Administradores (id_rol = 1)
-- ------------------------------------------------------------------
('Santi Perez', 'santi.admin@ticotrips.com', @hashed_password, '8888-0001', 1, 1), 
('Isra Arguedas', 'isra.admin@ticotrips.com', @hashed_password, '8888-0002', 1, 1), 
('Ale Delgado', 'ale.admin@ticotrips.com', @hashed_password, '8888-0003', 1, 1),

-- ------------------------------------------------------------------
-- Otros Roles de Prueba
-- ------------------------------------------------------------------
-- Cliente (id_rol = 2)
-- Cliente (id_rol = 2)
('Maria Lopez', 'maria.cliente@gmail.com', @hashed_password, '7000-0000', 2, 1),
('Carlos Salas', 'carlos.Salas@gmail.com', @hashed_password, '6300-0000', 2, 2), 

-- Negocio (id_rol = 3, 4, 5)
('Pedro Mora', 'pedro.comercio@business.com', @hashed_password, '6100-0000', 3, 1),
('Sofia Salas', 'sofia.restaurante@food.com', @hashed_password, '6200-0000', 4, 1), 
('Carlos Duarte', 'carlos.tour@adventures.com', @hashed_password, '6300-0000', 5, 1); 

-- ####################################################################
-- #                 SCRIPT DE INSERCIÓN DE DATOS INICIALES
-- #                        TABLA: categorias
-- ####################################################################

INSERT INTO `categorias` (`nombre_categoria`, `descripcion`) VALUES
('Hospedaje', 'Establecimientos que ofrecen alojamiento temporal a turistas (hoteles, cabañas, B&B, hostales, resorts, etc.).'),
('Tour / Experiencia', 'Proveedores de actividades guiadas o experiencias de aventura, culturales, ecoturísticas, o de bienestar.'),
('Restaurante / Gastronomía', 'Comercios dedicados principalmente a la venta y servicio de alimentos y bebidas (restaurantes, sodas, cafeterías, bares).'),
('Tienda de Artesanías / Souvenirs', 'Comercios minoristas que venden productos hechos a mano, recuerdos, regalos locales y artículos típicos de Costa Rica.'),
('Transporte', 'Negocios dedicados al transporte y movilización.'),
('Otros Comercios', 'Cualquier otro negocio relevante y de apoyo para la experiencia turística que no se clasifica en las categorías anteriores (ej: farmacias, lavanderías, alquiler de equipo, etc.).');

-- ####################################################################
-- #                     CREACIÓN DE ÍNDICES DE OPTIMIZACIÓN
-- ####################################################################

-- Índices para tabla usuarios
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_id_rol ON usuarios(id_rol);
CREATE INDEX idx_usuarios_id_estatus ON usuarios(id_estatus);

-- Índices para tabla negocios
CREATE INDEX idx_negocios_id_usuario ON negocios(id_usuario_fk);
CREATE INDEX idx_negocios_id_categoria ON negocios(id_categoria_fk);
CREATE INDEX idx_negocios_id_ubicacion ON negocios(id_ubicacion_fk);
CREATE INDEX idx_negocios_id_estatus ON negocios(id_estatus);

-- Índices para tabla servicios
CREATE INDEX idx_servicios_id_negocio ON servicios(id_negocio_fk);
CREATE INDEX idx_servicios_id_categoria ON servicios(id_categoria_fk);
CREATE INDEX idx_servicios_id_estatus ON servicios(id_estatus);

-- Índices para tabla reservas
CREATE INDEX idx_reservas_id_usuario ON reservas(id_usuario_fk);
CREATE INDEX idx_reservas_id_servicio ON reservas(id_servicio_fk);
CREATE INDEX idx_reservas_id_estatus ON reservas(id_estatus);
CREATE INDEX idx_reservas_fecha ON reservas(fecha_reserva);

-- Índices para tabla pagos
CREATE INDEX idx_pagos_id_reserva ON pagos(id_reserva_fk);
CREATE INDEX idx_pagos_fecha ON pagos(fecha_pago);

-- Índices para tabla promociones
CREATE INDEX idx_promociones_id_servicio ON promociones(id_servicio_fk);
CREATE INDEX idx_promociones_codigo ON promociones(codigo_promo);

-- Índices para tabla resenas
CREATE INDEX idx_resenas_id_usuario ON resenas(id_usuario_fk);
CREATE INDEX idx_resenas_id_servicio ON resenas(id_servicio_fk);
CREATE INDEX idx_resenas_fecha ON resenas(fecha_resena);

-- Índices para tabla cupones_b2b
CREATE INDEX idx_cupones_id_negocio ON cupones_b2b(id_negocio_fk);
CREATE INDEX idx_cupones_codigo ON cupones_b2b(codigo_cupon);
CREATE INDEX idx_cupones_id_estatus ON cupones_b2b(id_estatus);
CREATE INDEX idx_cupones_fechas ON cupones_b2b(fecha_inicio, fecha_fin);

-- Indice para tabla de ubicaciones
CREATE INDEX idx_ubicaciones_geografia ON ubicaciones (provincia, canton, distrito);

-- ####################################################################
-- #                  SCRIPT DE INSERCIÓN CON ID EXPLÍCITO
-- #                         TABLA: ubicaciones
-- ####################################################################
INSERT INTO ubicaciones (id_ubicacion, provincia, canton, distrito) VALUES
-- ------------------------------------------------------------------
-- PROVINCIA DE SAN JOSÉ (IDs 1-121)
-- ------------------------------------------------------------------
(1, 'San José', 'San José', 'Carmen'),
(2, 'San José', 'San José', 'Merced'),
(3, 'San José', 'San José', 'Hospital'),
(4, 'San José', 'San José', 'Catedral'),
(5, 'San José', 'San José', 'Zapote'),
(6, 'San José', 'San José', 'San Francisco de Dos Ríos'),
(7, 'San José', 'San José', 'Uruca'),
(8, 'San José', 'San José', 'Mata Redonda'),
(9, 'San José', 'San José', 'Pavas'),
(10, 'San José', 'San José', 'Hatillo'),
(11, 'San José', 'San José', 'San Sebastián'),
(12, 'San José', 'Escazú', 'Escazú'),
(13, 'San José', 'Escazú', 'San Antonio'),
(14, 'San José', 'Escazú', 'San Rafael'),
(15, 'San José', 'Desamparados', 'Desamparados'),
(16, 'San José', 'Desamparados', 'San Miguel'),
(17, 'San José', 'Desamparados', 'San Juan de Dios'),
(18, 'San José', 'Desamparados', 'San Rafael Arriba'),
(19, 'San José', 'Desamparados', 'San Antonio'),
(20, 'San José', 'Desamparados', 'Frailes'),
(21, 'San José', 'Desamparados', 'Patarrá'),
(22, 'San José', 'Desamparados', 'San Cristóbal'),
(23, 'San José', 'Desamparados', 'Rosario'),
(24, 'San José', 'Desamparados', 'Damas'),
(25, 'San José', 'Desamparados', 'San Rafael Abajo'),
(26, 'San José', 'Desamparados', 'Gravilias'),
(27, 'San José', 'Desamparados', 'Los Guido'),
(28, 'San José', 'Puriscal', 'Santiago'),
(29, 'San José', 'Puriscal', 'Mercedes Sur'),
(30, 'San José', 'Puriscal', 'Barbacoas'),
(31, 'San José', 'Puriscal', 'Grifo Alto'),
(32, 'San José', 'Puriscal', 'San Rafael'),
(33, 'San José', 'Puriscal', 'Candelarita'),
(34, 'San José', 'Puriscal', 'Desamparaditos'),
(35, 'San José', 'Puriscal', 'San Antonio'),
(36, 'San José', 'Puriscal', 'Chires'),
(37, 'San José', 'Tarrazú', 'San Marcos'),
(38, 'San José', 'Tarrazú', 'San Lorenzo'),
(39, 'San José', 'Tarrazú', 'San Carlos'),
(40, 'San José', 'Aserrí', 'Aserrí'),
(41, 'San José', 'Aserrí', 'Tarbaca'),
(42, 'San José', 'Aserrí', 'Vuelta de Jorco'),
(43, 'San José', 'Aserrí', 'San Gabriel'),
(44, 'San José', 'Aserrí', 'Legua'),
(45, 'San José', 'Aserrí', 'Monterrey'),
(46, 'San José', 'Aserrí', 'Salitrillos'),
(47, 'San José', 'Mora', 'Colón'),
(48, 'San José', 'Mora', 'Guayabo'),
(49, 'San José', 'Mora', 'Tabarcia'),
(50, 'San José', 'Mora', 'Piedras Negras'),
(51, 'San José', 'Mora', 'Picagres'),
(52, 'San José', 'Goicoechea', 'Guadalupe'),
(53, 'San José', 'Goicoechea', 'San Francisco'),
(54, 'San José', 'Goicoechea', 'Calle Blancos'),
(55, 'San José', 'Goicoechea', 'Mata de Plátano'),
(56, 'San José', 'Goicoechea', 'Ipís'),
(57, 'San José', 'Goicoechea', 'Rancho Redondo'),
(58, 'San José', 'Goicoechea', 'Purral'),
(59, 'San José', 'Santa Ana', 'Santa Ana'),
(60, 'San José', 'Santa Ana', 'Salitral'),
(61, 'San José', 'Santa Ana', 'Pozos'),
(62, 'San José', 'Santa Ana', 'Uruca'),
(63, 'San José', 'Santa Ana', 'Piedades'),
(64, 'San José', 'Santa Ana', 'Brasil'),
(65, 'San José', 'Alajuelita', 'Alajuelita'),
(66, 'San José', 'Alajuelita', 'San Josecito'),
(67, 'San José', 'Alajuelita', 'San Antonio'),
(68, 'San José', 'Alajuelita', 'Concepción'),
(69, 'San José', 'Alajuelita', 'San Felipe'),
(70, 'San José', 'Vázquez de Coronado', 'San Isidro'),
(71, 'San José', 'Vázquez de Coronado', 'San Rafael'),
(72, 'San José', 'Vázquez de Coronado', 'Dulce Nombre de Jesús'),
(73, 'San José', 'Vázquez de Coronado', 'Patalillo'),
(74, 'San José', 'Vázquez de Coronado', 'Cascajal'),
(75, 'San José', 'Acosta', 'San Ignacio'),
(76, 'San José', 'Acosta', 'Guaitil'),
(77, 'San José', 'Acosta', 'Palmichal'),
(78, 'San José', 'Acosta', 'Cangrejal'),
(79, 'San José', 'Acosta', 'Sabanillas'),
(80, 'San José', 'Tibás', 'San Juan'),
(81, 'San José', 'Tibás', 'Cinco Esquinas'),
(82, 'San José', 'Tibás', 'Anselmo Llorente'),
(83, 'San José', 'Tibás', 'León XIII'),
(84, 'San José', 'Tibás', 'Colima'),
(85, 'San José', 'Moravia', 'San Vicente'),
(86, 'San José', 'Moravia', 'San Jerónimo'),
(87, 'San José', 'Moravia', 'La Trinidad'),
(88, 'San José', 'Montes de Oca', 'San Pedro'),
(89, 'San José', 'Montes de Oca', 'Sabanilla'),
(90, 'San José', 'Montes de Oca', 'Mercedes'),
(91, 'San José', 'Montes de Oca', 'San Rafael'),
(92, 'San José', 'Turrubares', 'San Pablo'),
(93, 'San José', 'Turrubares', 'San Pedro'),
(94, 'San José', 'Turrubares', 'San Juan de Mata'),
(95, 'San José', 'Turrubares', 'San Luis'),
(96, 'San José', 'Turrubares', 'Carara'),
(97, 'San José', 'Dota', 'Santa María'),
(98, 'San José', 'Dota', 'Jardín'),
(99, 'San José', 'Dota', 'Copey'),
(100, 'San José', 'Curridabat', 'Curridabat'),
(101, 'San José', 'Curridabat', 'Granadilla'),
(102, 'San José', 'Curridabat', 'Sánchez'),
(103, 'San José', 'Curridabat', 'Tirrases'),
(104, 'San José', 'Pérez Zeledón', 'San Isidro de El General'),
(105, 'San José', 'Pérez Zeledón', 'El General'),
(106, 'San José', 'Pérez Zeledón', 'Daniel Flores'),
(107, 'San José', 'Pérez Zeledón', 'Rivas'),
(108, 'San José', 'Pérez Zeledón', 'San Pedro'),
(109, 'San José', 'Pérez Zeledón', 'Platanares'),
(110, 'San José', 'Pérez Zeledón', 'Pejibaye'),
(111, 'San José', 'Pérez Zeledón', 'Cajón'),
(112, 'San José', 'Pérez Zeledón', 'Barú'),
(113, 'San José', 'Pérez Zeledón', 'Río Nuevo'),
(114, 'San José', 'Pérez Zeledón', 'Páramo'),
(115, 'San José', 'Pérez Zeledón', 'La Amistad'),
(116, 'San José', 'León Cortés Castro', 'San Pablo'),
(117, 'San José', 'León Cortés Castro', 'San Andrés'),
(118, 'San José', 'León Cortés Castro', 'Llano Bonito'),
(119, 'San José', 'León Cortés Castro', 'San Isidro'),
(120, 'San José', 'León Cortés Castro', 'Santa Cruz'),
(121, 'San José', 'León Cortés Castro', 'San Antonio'),

-- ------------------------------------------------------------------
-- PROVINCIA DE ALAJUELA (IDs 122-237)
-- ------------------------------------------------------------------
(122, 'Alajuela', 'Alajuela', 'Alajuela'),
(123, 'Alajuela', 'Alajuela', 'San José'),
(124, 'Alajuela', 'Alajuela', 'Carrizal'),
(125, 'Alajuela', 'Alajuela', 'San Antonio'),
(126, 'Alajuela', 'Alajuela', 'Guácima'),
(127, 'Alajuela', 'Alajuela', 'San Isidro'),
(128, 'Alajuela', 'Alajuela', 'Sabanilla'),
(129, 'Alajuela', 'Alajuela', 'San Rafael'),
(130, 'Alajuela', 'Alajuela', 'Río Segundo'),
(131, 'Alajuela', 'Alajuela', 'Desamparados'),
(132, 'Alajuela', 'Alajuela', 'Turrúcares'),
(133, 'Alajuela', 'Alajuela', 'Tambor'),
(134, 'Alajuela', 'Alajuela', 'Garita'),
(135, 'Alajuela', 'Alajuela', 'Sarapiquí'),
(136, 'Alajuela', 'San Ramón', 'San Ramón'),
(137, 'Alajuela', 'San Ramón', 'Santiago'),
(138, 'Alajuela', 'San Ramón', 'San Juan'),
(139, 'Alajuela', 'San Ramón', 'Piedades Norte'),
(140, 'Alajuela', 'San Ramón', 'Piedades Sur'),
(141, 'Alajuela', 'San Ramón', 'San Rafael'),
(142, 'Alajuela', 'San Ramón', 'San Isidro'),
(143, 'Alajuela', 'San Ramón', 'Ángeles'),
(144, 'Alajuela', 'San Ramón', 'Alfaro'),
(145, 'Alajuela', 'San Ramón', 'Volio'),
(146, 'Alajuela', 'San Ramón', 'Concepción'),
(147, 'Alajuela', 'San Ramón', 'Zapotal'),
(148, 'Alajuela', 'San Ramón', 'Peñas Blancas'),
(149, 'Alajuela', 'San Ramón', 'San Lorenzo'),
(150, 'Alajuela', 'Grecia', 'Grecia'),
(151, 'Alajuela', 'Grecia', 'San Isidro'),
(152, 'Alajuela', 'Grecia', 'San José'),
(153, 'Alajuela', 'Grecia', 'San Roque'),
(154, 'Alajuela', 'Grecia', 'Tacares'),
(155, 'Alajuela', 'Grecia', 'Puente de Piedra'),
(156, 'Alajuela', 'Grecia', 'Bolívar'),
(157, 'Alajuela', 'San Mateo', 'San Mateo'),
(158, 'Alajuela', 'San Mateo', 'Desmonte'),
(159, 'Alajuela', 'San Mateo', 'Jesús María'),
(160, 'Alajuela', 'San Mateo', 'Labrador'),
(161, 'Alajuela', 'Atenas', 'Atenas'),
(162, 'Alajuela', 'Atenas', 'Jesús'),
(163, 'Alajuela', 'Atenas', 'Mercedes'),
(164, 'Alajuela', 'Atenas', 'San Isidro'),
(165, 'Alajuela', 'Atenas', 'Concepción'),
(166, 'Alajuela', 'Atenas', 'San José'),
(167, 'Alajuela', 'Atenas', 'Santa Eulalia'),
(168, 'Alajuela', 'Atenas', 'Escobal'),
(169, 'Alajuela', 'Naranjo', 'Naranjo'),
(170, 'Alajuela', 'Naranjo', 'San Miguel'),
(171, 'Alajuela', 'Naranjo', 'San José'),
(172, 'Alajuela', 'Naranjo', 'Cirrí Sur'),
(173, 'Alajuela', 'Naranjo', 'San Jerónimo'),
(174, 'Alajuela', 'Naranjo', 'San Juan'),
(175, 'Alajuela', 'Naranjo', 'Rosario'),
(176, 'Alajuela', 'Naranjo', 'Palmitos'),
(177, 'Alajuela', 'Palmares', 'Palmares'),
(178, 'Alajuela', 'Palmares', 'Zaragoza'),
(179, 'Alajuela', 'Palmares', 'Buenos Aires'),
(180, 'Alajuela', 'Palmares', 'Santiago'),
(181, 'Alajuela', 'Palmares', 'Candelaria'),
(182, 'Alajuela', 'Palmares', 'Esquipulas'),
(183, 'Alajuela', 'Palmares', 'La Granja'),
(184, 'Alajuela', 'Poás', 'San Pedro'),
(185, 'Alajuela', 'Poás', 'San Juan'),
(186, 'Alajuela', 'Poás', 'San Rafael'),
(187, 'Alajuela', 'Poás', 'Carrillos'),
(188, 'Alajuela', 'Poás', 'Sabana Redonda'),
(189, 'Alajuela', 'Orotina', 'Orotina'),
(190, 'Alajuela', 'Orotina', 'El Mastate'),
(191, 'Alajuela', 'Orotina', 'Hacienda Vieja'),
(192, 'Alajuela', 'Orotina', 'Coyolar'),
(193, 'Alajuela', 'Orotina', 'La Ceiba'),
(194, 'Alajuela', 'San Carlos', 'Quesada'),
(195, 'Alajuela', 'San Carlos', 'Florencia'),
(196, 'Alajuela', 'San Carlos', 'Buenavista'),
(197, 'Alajuela', 'San Carlos', 'Aguas Zarcas'),
(198, 'Alajuela', 'San Carlos', 'Venecia'),
(199, 'Alajuela', 'San Carlos', 'Pital'),
(200, 'Alajuela', 'San Carlos', 'La Fortuna'),
(201, 'Alajuela', 'San Carlos', 'La Tigra'),
(202, 'Alajuela', 'San Carlos', 'La Palmera'),
(203, 'Alajuela', 'San Carlos', 'Venado'),
(204, 'Alajuela', 'San Carlos', 'Cutris'),
(205, 'Alajuela', 'San Carlos', 'Monterrey'),
(206, 'Alajuela', 'San Carlos', 'Pocosol'),
(207, 'Alajuela', 'Zarcero', 'Zarcero'),
(208, 'Alajuela', 'Zarcero', 'Laguna'),
(209, 'Alajuela', 'Zarcero', 'Tapesco'),
(210, 'Alajuela', 'Zarcero', 'Guadalupe'),
(211, 'Alajuela', 'Zarcero', 'Palmira'),
(212, 'Alajuela', 'Zarcero', 'Zapote'),
(213, 'Alajuela', 'Zarcero', 'Brisas'),
(214, 'Alajuela', 'Sarchí', 'Sarchí Norte'),
(215, 'Alajuela', 'Sarchí', 'Sarchí Sur'),
(216, 'Alajuela', 'Sarchí', 'Toro Amarillo'),
(217, 'Alajuela', 'Sarchí', 'San Pedro'),
(218, 'Alajuela', 'Sarchí', 'Rodríguez'),
(219, 'Alajuela', 'Upala', 'Upala'),
(220, 'Alajuela', 'Upala', 'Aguas Claras'),
(221, 'Alajuela', 'Upala', 'San José'),
(222, 'Alajuela', 'Upala', 'Bijagua'),
(223, 'Alajuela', 'Upala', 'Delicias'),
(224, 'Alajuela', 'Upala', 'Dos Ríos'),
(225, 'Alajuela', 'Upala', 'Yolillal'),
(226, 'Alajuela', 'Upala', 'Canalete'),
(227, 'Alajuela', 'Los Chiles', 'Los Chiles'),
(228, 'Alajuela', 'Los Chiles', 'Caño Negro'),
(229, 'Alajuela', 'Los Chiles', 'El Amparo'),
(230, 'Alajuela', 'Los Chiles', 'San Jorge'),
(231, 'Alajuela', 'Guatuso', 'San Rafael'),
(232, 'Alajuela', 'Guatuso', 'Buenavista'),
(233, 'Alajuela', 'Guatuso', 'Cote'),
(234, 'Alajuela', 'Guatuso', 'Katira'),
(235, 'Alajuela', 'Río Cuarto', 'Río Cuarto'),
(236, 'Alajuela', 'Río Cuarto', 'Santa Rita'),
(237, 'Alajuela', 'Río Cuarto', 'Santa Isabel'),

-- ------------------------------------------------------------------
-- PROVINCIA DE CARTAGO (IDs 238-288)
-- ------------------------------------------------------------------
(238, 'Cartago', 'Cartago', 'Oriental'),
(239, 'Cartago', 'Cartago', 'Occidental'),
(240, 'Cartago', 'Cartago', 'Carmen'),
(241, 'Cartago', 'Cartago', 'San Nicolás'),
(242, 'Cartago', 'Cartago', 'Aguacaliente'),
(243, 'Cartago', 'Cartago', 'Guadalupe'),
(244, 'Cartago', 'Cartago', 'Corralillo'),
(245, 'Cartago', 'Cartago', 'Tierra Blanca'),
(246, 'Cartago', 'Cartago', 'Dulce Nombre'),
(247, 'Cartago', 'Cartago', 'Llano Grande'),
(248, 'Cartago', 'Cartago', 'Quebradilla'),
(249, 'Cartago', 'Paraíso', 'Paraíso'),
(250, 'Cartago', 'Paraíso', 'Santiago'),
(251, 'Cartago', 'Paraíso', 'Orosi'),
(252, 'Cartago', 'Paraíso', 'Cachí'),
(253, 'Cartago', 'Paraíso', 'Llanos de Santa Lucía'),
(254, 'Cartago', 'La Unión', 'Tres Ríos'),
(255, 'Cartago', 'La Unión', 'San Diego'),
(256, 'Cartago', 'La Unión', 'San Juan'),
(257, 'Cartago', 'La Unión', 'San Rafael'),
(258, 'Cartago', 'La Unión', 'Concepción'),
(259, 'Cartago', 'La Unión', 'Dulce Nombre'),
(260, 'Cartago', 'La Unión', 'San Ramón'),
(261, 'Cartago', 'La Unión', 'Río Azul'),
(262, 'Cartago', 'Jiménez', 'Juan Viñas'),
(263, 'Cartago', 'Jiménez', 'Tucurrique'),
(264, 'Cartago', 'Jiménez', 'Pejibaye'),
(265, 'Cartago', 'Turrialba', 'Turrialba'),
(266, 'Cartago', 'Turrialba', 'La Suiza'),
(267, 'Cartago', 'Turrialba', 'Peralta'),
(268, 'Cartago', 'Turrialba', 'Santa Cruz'),
(269, 'Cartago', 'Turrialba', 'Santa Teresita'),
(270, 'Cartago', 'Turrialba', 'Pavones'),
(271, 'Cartago', 'Turrialba', 'Tuis'),
(272, 'Cartago', 'Turrialba', 'Tayutic'),
(273, 'Cartago', 'Turrialba', 'Santa Rosa'),
(274, 'Cartago', 'Turrialba', 'Tres Equis'),
(275, 'Cartago', 'Turrialba', 'La Isabel'),
(276, 'Cartago', 'Turrialba', 'Chirripó'),
(277, 'Cartago', 'Alvarado', 'Pacayas'),
(278, 'Cartago', 'Alvarado', 'Cervantes'),
(279, 'Cartago', 'Alvarado', 'Capellades'),
(280, 'Cartago', 'Oreamuno', 'San Rafael'),
(281, 'Cartago', 'Oreamuno', 'Cot'),
(282, 'Cartago', 'Oreamuno', 'Potrero Cerrado'),
(283, 'Cartago', 'Oreamuno', 'Cipreses'),
(284, 'Cartago', 'Oreamuno', 'Santa Rosa'),
(285, 'Cartago', 'El Guarco', 'El Tejar'),
(286, 'Cartago', 'El Guarco', 'San Isidro'),
(287, 'Cartago', 'El Guarco', 'Tobosi'),
(288, 'Cartago', 'El Guarco', 'Patio de Agua'),

-- ------------------------------------------------------------------
-- PROVINCIA DE HEREDIA (IDs 289-335)
-- ------------------------------------------------------------------
(289, 'Heredia', 'Heredia', 'Heredia'),
(290, 'Heredia', 'Heredia', 'Mercedes'),
(291, 'Heredia', 'Heredia', 'San Francisco'),
(292, 'Heredia', 'Heredia', 'Ulloa'),
(293, 'Heredia', 'Heredia', 'Varablanca'),
(294, 'Heredia', 'Barva', 'Barva'),
(295, 'Heredia', 'Barva', 'San Pedro'),
(296, 'Heredia', 'Barva', 'San Pablo'),
(297, 'Heredia', 'Barva', 'San Roque'),
(298, 'Heredia', 'Barva', 'Santa Lucía'),
(299, 'Heredia', 'Barva', 'San José de la Montaña'),
(300, 'Heredia', 'Santo Domingo', 'Santo Domingo'),
(301, 'Heredia', 'Santo Domingo', 'San Vicente'),
(302, 'Heredia', 'Santo Domingo', 'San Miguel'),
(303, 'Heredia', 'Santo Domingo', 'Paracito'),
(304, 'Heredia', 'Santo Domingo', 'Santo Tomás'),
(305, 'Heredia', 'Santo Domingo', 'Santa Rosa'),
(306, 'Heredia', 'Santo Domingo', 'Tures'),
(307, 'Heredia', 'Santo Domingo', 'Pará'),
(308, 'Heredia', 'Santa Bárbara', 'Santa Bárbara'),
(309, 'Heredia', 'Santa Bárbara', 'San Pedro'),
(310, 'Heredia', 'Santa Bárbara', 'San Juan'),
(311, 'Heredia', 'Santa Bárbara', 'Jesús'),
(312, 'Heredia', 'Santa Bárbara', 'Santo Domingo'),
(313, 'Heredia', 'Santa Bárbara', 'Purabá'),
(314, 'Heredia', 'San Rafael', 'San Rafael'),
(315, 'Heredia', 'San Rafael', 'San Josecito'),
(316, 'Heredia', 'San Rafael', 'Santiago'),
(317, 'Heredia', 'San Rafael', 'Ángeles'),
(318, 'Heredia', 'San Rafael', 'Concepción'),
(319, 'Heredia', 'San Isidro', 'San Isidro'),
(320, 'Heredia', 'San Isidro', 'San José'),
(321, 'Heredia', 'San Isidro', 'Concepción'),
(322, 'Heredia', 'San Isidro', 'San Francisco'),
(323, 'Heredia', 'Belén', 'San Antonio'),
(324, 'Heredia', 'Belén', 'La Ribera'),
(325, 'Heredia', 'Belén', 'La Asunción'),
(326, 'Heredia', 'Flores', 'San Joaquín'),
(327, 'Heredia', 'Flores', 'Barrantes'),
(328, 'Heredia', 'Flores', 'Llorente'),
(329, 'Heredia', 'San Pablo', 'San Pablo'),
(330, 'Heredia', 'San Pablo', 'Rincón de Sabanilla'),
(331, 'Heredia', 'Sarapiquí', 'Puerto Viejo'),
(332, 'Heredia', 'Sarapiquí', 'La Virgen'),
(333, 'Heredia', 'Sarapiquí', 'Las Horquetas'),
(334, 'Heredia', 'Sarapiquí', 'Llanuras del Gaspar'),
(335, 'Heredia', 'Sarapiquí', 'Cureña'),

-- ------------------------------------------------------------------
-- PROVINCIA DE GUANACASTE (IDs 336-396)
-- ------------------------------------------------------------------
(336, 'Guanacaste', 'Liberia', 'Liberia'),
(337, 'Guanacaste', 'Liberia', 'Cañas Dulces'),
(338, 'Guanacaste', 'Liberia', 'Mayorga'),
(339, 'Guanacaste', 'Liberia', 'Nacascolo'),
(340, 'Guanacaste', 'Liberia', 'Curubandé'),
(341, 'Guanacaste', 'Nicoya', 'Nicoya'),
(342, 'Guanacaste', 'Nicoya', 'Mansión'),
(343, 'Guanacaste', 'Nicoya', 'San Antonio'),
(344, 'Guanacaste', 'Nicoya', 'Quebrada Honda'),
(345, 'Guanacaste', 'Nicoya', 'Sámara'),
(346, 'Guanacaste', 'Nicoya', 'Nosara'),
(347, 'Guanacaste', 'Nicoya', 'Belén de Nosarita'),
(348, 'Guanacaste', 'Santa Cruz', 'Santa Cruz'),
(349, 'Guanacaste', 'Santa Cruz', 'Bolsón'),
(350, 'Guanacaste', 'Santa Cruz', 'Veintisiete de Abril'),
(351, 'Guanacaste', 'Santa Cruz', 'Tempate'),
(352, 'Guanacaste', 'Santa Cruz', 'Cartagena'),
(353, 'Guanacaste', 'Santa Cruz', 'Cuajiniquil'),
(354, 'Guanacaste', 'Santa Cruz', 'Diriá'),
(355, 'Guanacaste', 'Santa Cruz', 'Cabo Velas'),
(356, 'Guanacaste', 'Santa Cruz', 'Tamarindo'),
(357, 'Guanacaste', 'Bagaces', 'Bagaces'),
(358, 'Guanacaste', 'Bagaces', 'La Fortuna'),
(359, 'Guanacaste', 'Bagaces', 'Mogote'),
(360, 'Guanacaste', 'Bagaces', 'Río Naranjo'),
(361, 'Guanacaste', 'Carrillo', 'Filadelfia'),
(362, 'Guanacaste', 'Carrillo', 'Palmira'),
(363, 'Guanacaste', 'Carrillo', 'Sardinal'),
(364, 'Guanacaste', 'Carrillo', 'Belén'),
(365, 'Guanacaste', 'Cañas', 'Cañas'),
(366, 'Guanacaste', 'Cañas', 'Palmira'),
(367, 'Guanacaste', 'Cañas', 'San Miguel'),
(368, 'Guanacaste', 'Cañas', 'Bebedero'),
(369, 'Guanacaste', 'Cañas', 'Porozal'),
(370, 'Guanacaste', 'Abangares', 'Las Juntas'),
(371, 'Guanacaste', 'Abangares', 'Sierra'),
(372, 'Guanacaste', 'Abangares', 'San Juan'),
(373, 'Guanacaste', 'Abangares', 'Colorado'),
(374, 'Guanacaste', 'Tilarán', 'Tilarán'),
(375, 'Guanacaste', 'Tilarán', 'Quebrada Grande'),
(376, 'Guanacaste', 'Tilarán', 'Tronadora'),
(377, 'Guanacaste', 'Tilarán', 'Santa Rosa'),
(378, 'Guanacaste', 'Tilarán', 'Líbano'),
(379, 'Guanacaste', 'Tilarán', 'Tierras Morenas'),
(380, 'Guanacaste', 'Tilarán', 'Arenal'),
(381, 'Guanacaste', 'Tilarán', 'Cabeceras'),
(382, 'Guanacaste', 'Nandayure', 'Carmona'),
(383, 'Guanacaste', 'Nandayure', 'Santa Rita'),
(384, 'Guanacaste', 'Nandayure', 'Zapotal'),
(385, 'Guanacaste', 'Nandayure', 'San Pablo'),
(386, 'Guanacaste', 'Nandayure', 'Porvenir'),
(387, 'Guanacaste', 'Nandayure', 'Bejuco'),
(388, 'Guanacaste', 'La Cruz', 'La Cruz'),
(389, 'Guanacaste', 'La Cruz', 'Santa Cecilia'),
(390, 'Guanacaste', 'La Cruz', 'La Garita'),
(391, 'Guanacaste', 'La Cruz', 'Santa Elena'),
(392, 'Guanacaste', 'Hojancha', 'Hojancha'),
(393, 'Guanacaste', 'Hojancha', 'Monte Romo'),
(394, 'Guanacaste', 'Hojancha', 'Puerto Carrillo'),
(395, 'Guanacaste', 'Hojancha', 'Huacas'),
(396, 'Guanacaste', 'Hojancha', 'Matambú'),

-- ------------------------------------------------------------------
-- PROVINCIA DE PUNTARENAS (IDs 397-459)
-- ------------------------------------------------------------------
(397, 'Puntarenas', 'Puntarenas', 'Puntarenas'),
(398, 'Puntarenas', 'Puntarenas', 'Pitahaya'),
(399, 'Puntarenas', 'Puntarenas', 'Chomes'),
(400, 'Puntarenas', 'Puntarenas', 'Lepanto'),
(401, 'Puntarenas', 'Puntarenas', 'Paquera'),
(402, 'Puntarenas', 'Puntarenas', 'Manzanillo'),
(403, 'Puntarenas', 'Puntarenas', 'Guacimal'),
(404, 'Puntarenas', 'Puntarenas', 'Barranca'),
(405, 'Puntarenas', 'Puntarenas', 'Monte Verde'),
(406, 'Puntarenas', 'Puntarenas', 'Isla del Coco'),
(407, 'Puntarenas', 'Puntarenas', 'Cóbano'),
(408, 'Puntarenas', 'Puntarenas', 'Chacarita'),
(409, 'Puntarenas', 'Puntarenas', 'Chira'),
(410, 'Puntarenas', 'Puntarenas', 'Acapulco'),
(411, 'Puntarenas', 'Puntarenas', 'El Roble'),
(412, 'Puntarenas', 'Puntarenas', 'Arancibia'),
(413, 'Puntarenas', 'Esparza', 'Espíritu Santo'),
(414, 'Puntarenas', 'Esparza', 'San Juan Grande'),
(415, 'Puntarenas', 'Esparza', 'Macacona'),
(416, 'Puntarenas', 'Esparza', 'San Rafael'),
(417, 'Puntarenas', 'Esparza', 'San Jerónimo'),
(418, 'Puntarenas', 'Esparza', 'Caldera'),
(419, 'Puntarenas', 'Buenos Aires', 'Buenos Aires'),
(420, 'Puntarenas', 'Buenos Aires', 'Volcán'),
(421, 'Puntarenas', 'Buenos Aires', 'Potrero Grande'),
(422, 'Puntarenas', 'Buenos Aires', 'Boruca'),
(423, 'Puntarenas', 'Buenos Aires', 'Pilas'),
(424, 'Puntarenas', 'Buenos Aires', 'Colinas'),
(425, 'Puntarenas', 'Buenos Aires', 'Chánguena'),
(426, 'Puntarenas', 'Buenos Aires', 'Biolley'),
(427, 'Puntarenas', 'Buenos Aires', 'Brunka'),
(428, 'Puntarenas', 'Montes de Oro', 'Miramar'),
(429, 'Puntarenas', 'Montes de Oro', 'La Unión'),
(430, 'Puntarenas', 'Montes de Oro', 'San Isidro'),
(431, 'Puntarenas', 'Osa', 'Puerto Cortés'),
(432, 'Puntarenas', 'Osa', 'Palmar'),
(433, 'Puntarenas', 'Osa', 'Sierpe'),
(434, 'Puntarenas', 'Osa', 'Bahía Ballena'),
(435, 'Puntarenas', 'Osa', 'Piedras Blancas'),
(436, 'Puntarenas', 'Osa', 'Bahía Drake'),
(437, 'Puntarenas', 'Quepos', 'Quepos'),
(438, 'Puntarenas', 'Quepos', 'Savegre'),
(439, 'Puntarenas', 'Quepos', 'Naranjito'),
(440, 'Puntarenas', 'Golfito', 'Golfito'),
(441, 'Puntarenas', 'Golfito', 'Puerto Jiménez'),
(442, 'Puntarenas', 'Golfito', 'Guaycará'),
(443, 'Puntarenas', 'Golfito', 'Pavón'),
(444, 'Puntarenas', 'Coto Brus', 'San Vito'),
(445, 'Puntarenas', 'Coto Brus', 'Sabalito'),
(446, 'Puntarenas', 'Coto Brus', 'Aguabuena'),
(447, 'Puntarenas', 'Coto Brus', 'Limoncito'),
(448, 'Puntarenas', 'Coto Brus', 'Pittier'),
(449, 'Puntarenas', 'Coto Brus', 'Gutiérrez Braun'),
(450, 'Puntarenas', 'Parrita', 'Parrita'),
(451, 'Puntarenas', 'Corredores', 'Corredor'),
(452, 'Puntarenas', 'Corredores', 'La Cuesta'),
(453, 'Puntarenas', 'Corredores', 'Canoas'),
(454, 'Puntarenas', 'Corredores', 'Laurel'),
(455, 'Puntarenas', 'Garabito', 'Jacó'),
(456, 'Puntarenas', 'Garabito', 'Tárcoles'),
(457, 'Puntarenas', 'Garabito', 'Lagunillas'),
(458, 'Puntarenas', 'Monteverde', 'Monteverde'),
(459, 'Puntarenas', 'Puerto Jiménez', 'Puerto Jiménez'),

-- ------------------------------------------------------------------
-- PROVINCIA DE LIMÓN (IDs 460-489)
-- ------------------------------------------------------------------
(460, 'Limón', 'Limón', 'Limón'),
(461, 'Limón', 'Limón', 'Valle La Estrella'),
(462, 'Limón', 'Limón', 'Río Blanco'),
(463, 'Limón', 'Limón', 'Matama'),
(464, 'Limón', 'Pococí', 'Guápiles'),
(465, 'Limón', 'Pococí', 'Jiménez'),
(466, 'Limón', 'Pococí', 'Rita'),
(467, 'Limón', 'Pococí', 'Roxana'),
(468, 'Limón', 'Pococí', 'Cariari'),
(469, 'Limón', 'Pococí', 'Colorado'),
(470, 'Limón', 'Pococí', 'La Colonia'),
(471, 'Limón', 'Siquirres', 'Siquirres'),
(472, 'Limón', 'Siquirres', 'Pacuarito'),
(473, 'Limón', 'Siquirres', 'Florida'),
(474, 'Limón', 'Siquirres', 'Germania'),
(475, 'Limón', 'Siquirres', 'El Cairo'),
(476, 'Limón', 'Siquirres', 'Alegría'),
(477, 'Limón', 'Siquirres', 'Reventazón'),
(478, 'Limón', 'Talamanca', 'Bratsi'),
(479, 'Limón', 'Talamanca', 'Sixaola'),
(480, 'Limón', 'Talamanca', 'Cahuita'),
(481, 'Limón', 'Talamanca', 'Telire'),
(482, 'Limón', 'Matina', 'Matina'),
(483, 'Limón', 'Matina', 'Batán'),
(484, 'Limón', 'Matina', 'Carrandi'),
(485, 'Limón', 'Guácimo', 'Guácimo'),
(486, 'Limón', 'Guácimo', 'Mercedes'),
(487, 'Limón', 'Guácimo', 'Pocora'),
(488, 'Limón', 'Guácimo', 'Río Jiménez'),
(489, 'Limón', 'Guácimo', 'Duacarí');

-- ####################################################################
-- #                  DATOS DE NEGOCIOS PARA DEMO (CARRUSEL)
-- ####################################################################
-- Nota: id_estatus = 1 (Activo) para negocios aprobados y visibles.
INSERT INTO `negocios` (
  `id_usuario_fk`, `nombre_legal`, `nombre_publico`, `id_categoria_fk`,
  `descripcion_corta`, `telefono_contacto`, `correo_contacto`,
  `tipo_cedula`, `cedula_hacienda`, `nombre_representante`, `no_licencia_municipal`,
  `ruta_cedula_frente`, `ruta_cedula_reverso`, `foto_portada`,
  `id_ubicacion_fk`, `direccion_exacta`, `link_google_maps`, `link_waze`, `id_estatus`
) VALUES
-- Hotel Arenal
(6, 'Hotel Arenal Paradise S.A.', 'Hotel Arenal Paradise', 1,
 'Hospedaje de lujo con vistas espectaculares al Volcán Arenal y aguas termales naturales.', '2479-0001', 'reservas@arenalparadise.com',
 'Jurídica', '3101123456', 'Pedro Mora', 'LM-001',
 NULL, NULL, 'arenal.jpg',
 200, 'La Fortuna, 2km del centro hacia el volcán', 'https://maps.app.goo.gl/arenal', 'https://waze.com/ul/arenal', 1),

-- Tour Volcán Irazú
(8, 'Tours Irazú Adventure', 'Tour Volcán Irazú', 2,
 'Excursión guiada al cráter del volcán más alto de Costa Rica con desayuno típico incluido.', '2530-0002', 'info@irazutours.com',
 'Jurídica', '3102123456', 'Carlos Duarte', 'LM-002',
 NULL, NULL, 'irazu.jpg',
 238, 'Parque Nacional Volcán Irazú, entrada principal', 'https://maps.app.goo.gl/irazu', 'https://waze.com/ul/irazu', 1),

-- Kayak Adventures
(8, 'Kayak Adventures CR', 'Aventuras en Kayak', 2,
 'Tours en kayak por manglares y costas del Pacífico con equipo profesional incluido.', '2643-0003', 'aventuras@kayakcr.com',
 'Jurídica', '3103123456', 'Carlos Duarte', 'LM-003',
 NULL, NULL, 'kayak.jpg',
 455, 'Playa Jacó, frente al muelle principal', 'https://maps.app.goo.gl/jaco', 'https://waze.com/ul/jaco', 1),

-- Hotel Monteverde
(6, 'Hotel Vista Monteverde S.A.', 'Hotel Monteverde Cloud Forest', 1,
 'Hotel boutique en el bosque nuboso con senderos privados y observación de aves.', '2645-0004', 'contacto@monteverdecf.com',
 'Jurídica', '3104123456', 'Pedro Mora', 'LM-004',
 NULL, NULL, 'monteverde.jpg',
 458, 'Monteverde, 500m del centro hacia la reserva', 'https://maps.app.goo.gl/monteverde', 'https://waze.com/ul/monteverde', 1),

-- Souvenirs Puerto Viejo
(6, 'Artesanías del Caribe S.A.', 'Souvenirs Puerto Viejo', 4,
 'Tienda de artesanías caribeñas, productos locales y recuerdos hechos a mano por artistas de la zona.', '2750-0005', 'ventas@souvenirspv.com',
 'Jurídica', '3105123456', 'Pedro Mora', 'LM-005',
 NULL, NULL, 'puerto-viejo.jpg',
 331, 'Puerto Viejo de Sarapiquí, centro del pueblo', 'https://maps.app.goo.gl/puertoviejo', 'https://waze.com/ul/puertoviejo', 1),

-- Tour Río Celeste
(8, 'Río Celeste Tours & Adventures', 'Tour Río Celeste', 2,
 'Caminata guiada por el sendero del Río Celeste y la cascada de aguas turquesas.', '2466-0006', 'info@riocelestetours.com',
 'Jurídica', '3106123456', 'Carlos Duarte', 'LM-006',
 NULL, NULL, 'rio-celeste.jpg',
 222, 'Bijagua de Upala, Parque Nacional Volcán Tenorio', 'https://maps.app.goo.gl/rioceleste', 'https://waze.com/ul/rioceleste', 1),

-- Restaurante Santa Teresa
(7, 'Restaurante Santa Tere S.R.L.', 'Sabores de Santa Teresa', 3,
 'Gastronomía costarricense con mariscos frescos y vista al océano Pacífico.', '2640-0007', 'reservas@santatere.com',
 'Jurídica', '3107123456', 'Sofia Salas', 'LM-007',
 NULL, NULL, 'santa-tere.jpg',
 407, 'Santa Teresa, Cóbano, frente a la playa', 'https://maps.app.goo.gl/santateresa', 'https://waze.com/ul/santateresa', 1),

-- Comercio Genérico San José
(7, 'Comercio TicoTrips S.A.', 'Servicios Turísticos CR', 6,
 'Servicios varios para turistas: alquiler de equipo, información turística y más.', '2222-0008', 'info@ticotrips.com',
 'Jurídica', '3108123456', 'Sofia Salas', 'LM-008',
 NULL, NULL, 'placeholder.jpg',
 1, 'San José, Centro, Avenida Central', 'https://maps.app.goo.gl/sanjose', 'https://waze.com/ul/sanjose', 1),

-- GUANACASTE - Restaurante Liberia
(7, 'Restaurante El Sabanero S.A.', 'Restaurante El Sabanero', 3,
 'Comida típica guanacasteca con gallos, sopas y carnes a la parrilla en ambiente familiar.', '2666-0009', 'contacto@elsabanero.com',
 'Jurídica', '3109123456', 'Sofia Salas', 'LM-009',
 NULL, NULL, 'santa-tere.jpg',
 336, 'Liberia centro, 100m este del parque', 'https://maps.app.goo.gl/liberia', 'https://waze.com/ul/liberia', 1),

-- LIMÓN - Tour Cahuita
(8, 'Cahuita Tours & Snorkel', 'Aventuras Cahuita', 2,
 'Tours de snorkel en el arrecife de coral y caminatas por el Parque Nacional Cahuita.', '2755-0010', 'tours@cahuitaadventures.com',
 'Jurídica', '3110123456', 'Carlos Duarte', 'LM-010',
 NULL, NULL, 'puerto-viejo.jpg',
 480, 'Cahuita, frente a la entrada del parque nacional', 'https://maps.app.goo.gl/cahuita', 'https://waze.com/ul/cahuita', 1);

-- ####################################################################
-- #                  SERVICIOS PARA CADA NEGOCIO
-- ####################################################################
-- Servicios para cada uno de los 10 negocios con precios y descripciones
-- Nota: id_estatus = 1 (Activo) para servicios disponibles

INSERT INTO `servicios` (
  `id_negocio_fk`, `id_categoria_fk`, `titulo`, `descripcion_corta`, 
  `precio_base`, `duracion_dias`, `id_estatus`
) VALUES
-- Servicios para Hotel Arenal Paradise (id_negocio=1)
(1, 1, 'Habitación Doble Standard', 'Habitación cómoda con vista al jardín, baño privado, WiFi y desayuno incluido.', 85.00, 1, 1),
(1, 1, 'Habitación Suite con Vista al Volcán', 'Suite de lujo con balcón privado, vista panorámica al Volcán Arenal, jacuzzi y desayuno gourmet.', 150.00, 1, 1),
(1, 1, 'Cabaña Familiar', 'Cabaña espaciosa para hasta 4 personas con cocina equipada y terraza con hamacas.', 120.00, 1, 1),

-- Servicios para Tour Volcán Irazú (id_negocio=2)
(2, 2, 'Tour Volcán Irazú Medio Día', 'Excursión guiada al cráter principal del volcán, incluye transporte y desayuno típico. Duración: 5 horas.', 45.00, NULL, 1),
(2, 2, 'Tour Irazú + Cartago Colonial', 'Visita al volcán y recorrido por la Basílica de los Ángeles y centro de Cartago. Duración: 8 horas.', 65.00, NULL, 1),

-- Servicios para Aventuras en Kayak (id_negocio=3)
(3, 2, 'Tour en Kayak por Manglares', 'Recorrido guiado de 2 horas por manglares con observación de fauna silvestre.', 35.00, NULL, 1),
(3, 2, 'Kayak al Atardecer', 'Experiencia romántica en kayak durante la puesta del sol con snacks incluidos. Duración: 1.5 horas.', 40.00, NULL, 1),
(3, 2, 'Tour Kayak + Snorkel', 'Aventura completa con kayak y snorkel en arrecifes cercanos, equipo incluido. Duración: 4 horas.', 55.00, NULL, 1),

-- Servicios para Hotel Monteverde Cloud Forest (id_negocio=4)
(4, 1, 'Habitación Ecológica Doble', 'Habitación rústica con materiales sostenibles, vista al bosque y desayuno orgánico.', 70.00, 1, 1),
(4, 1, 'Suite Canopy con Terraza', 'Suite premium con terraza privada para observación de aves y fauna nocturna.', 110.00, 1, 1),
(4, 2, 'Tour Nocturno Bosque Nuboso', 'Caminata nocturna guiada por senderos privados para observar fauna y flora única. Duración: 2 horas.', 30.00, NULL, 1),

-- Servicios para Souvenirs Puerto Viejo (id_negocio=5)
(5, 4, 'Artesanías de Madera Tallada', 'Productos hechos a mano por artesanos locales: figuras, joyeros, decoraciones.', 25.00, NULL, 1),
(5, 4, 'Joyería Caribeña', 'Collares, pulseras y aretes con diseños inspirados en la cultura afrocaribeña.', 15.00, NULL, 1),
(5, 4, 'Textiles y Ropa Batik', 'Camisetas, vestidos y bolsos con técnica batik tradicional en colores vibrantes.', 20.00, NULL, 1),

-- Servicios para Tour Río Celeste (id_negocio=6)
(6, 2, 'Tour Río Celeste y Cascada', 'Caminata de 6km por sendero del parque hasta la cascada de aguas turquesas. Duración: 4 horas.', 50.00, NULL, 1),
(6, 2, 'Tour Río Celeste + Aguas Termales', 'Visita al río Celeste y relajación en aguas termales naturales cercanas. Duración: 6 horas.', 70.00, NULL, 1),
(6, 2, 'Tour Fotográfico Río Celeste', 'Recorrido especializado con guía fotógrafo para capturar los mejores ángulos. Duración: 5 horas.', 85.00, NULL, 1),

-- Servicios para Sabores de Santa Teresa (id_negocio=7)
(7, 3, 'Almuerzo Frente al Mar', 'Menú del día con ceviches, pescado fresco y opciones vegetarianas con vista al océano. Duración: 1.5 horas.', 18.00, NULL, 1),
(7, 3, 'Cena Romántica Sunset', 'Cena de 3 tiempos con mariscos premium y vino durante la puesta de sol. Duración: 2 horas.', 45.00, NULL, 1),
(7, 3, 'Clase de Cocina Costarricense', 'Aprende a preparar platos típicos con chef local, incluye ingredientes y degustación. Duración: 3 horas.', 60.00, NULL, 1),

-- Servicios para Servicios Turísticos CR (id_negocio=8)
(8, 6, 'Alquiler de Equipo de Playa', 'Tablas de surf, snorkel, sombrillas y sillas por día completo.', 25.00, 1, 1),
(8, 6, 'City Tour San José', 'Recorrido guiado por museos, mercados y sitios históricos de la capital. Duración: 4 horas.', 35.00, NULL, 1),
(8, 6, 'Información y Mapas Turísticos', 'Servicio de información turística con mapas, guías y recomendaciones personalizadas.', 5.00, NULL, 1),

-- Servicios para Restaurante El Sabanero (id_negocio=9)
(9, 3, 'Almuerzo Típico Guanacasteco', 'Plato fuerte con arroz, frijoles, carne asada, ensalada y tortillas palmeadas. Duración: 1 hora.', 12.00, NULL, 1),
(9, 3, 'Sopa de Mondongo Especial', 'Tradicional sopa guanacasteca con verduras y tortillas, ideal para días frescos. Duración: 45 minutos.', 8.00, NULL, 1),
(9, 3, 'Cena BBQ Familiar', 'Parrillada para 4 personas con carnes, chorizos, gallos y acompañamientos. Duración: 1.5 horas.', 50.00, NULL, 1),

-- Servicios para Aventuras Cahuita (id_negocio=10)
(10, 2, 'Snorkel en Arrecife de Coral', 'Tour de snorkel en el arrecife con equipo incluido y guía bilingüe. Duración: 2.5 horas.', 40.00, NULL, 1),
(10, 2, 'Caminata Parque Nacional Cahuita', 'Recorrido por senderos costeros con observación de monos, perezosos y aves. Duración: 3 horas.', 25.00, NULL, 1),
(10, 2, 'Tour Combinado Snorkel + Caminata', 'Experiencia completa con snorkel en la mañana y caminata por la tarde. Duración: 6 horas.', 55.00, NULL, 1);

-- ####################################################################
-- #                 CUPONES DE PRUEBA PARA DEMOSTRACIÓN
-- ####################################################################

INSERT INTO `cupones_b2b` (`id_negocio_fk`, `codigo_cupon`, `tipo_descuento`, `valor_descuento`, `fecha_inicio`, `fecha_fin`, `usos_restantes`, `id_estatus`) VALUES
(1, 'BIENVENIDA2024', 'Porcentaje', 15, '2024-01-01', '2025-12-31', NULL, 1),
(1, 'ARENAL2024', 'Porcentaje', 20, '2024-12-01', '2025-01-31', NULL, 1),
(2, 'PLAYA15', 'Porcentaje', 15, '2024-12-01', '2025-03-31', NULL, 1),
(3, 'AVENTURA25', 'MontoFijo', 5000, '2024-12-10', '2025-02-28', NULL, 1),
(4, 'HOSPEDAJE10', 'Porcentaje', 10, '2024-12-01', '2025-06-30', NULL, 1);