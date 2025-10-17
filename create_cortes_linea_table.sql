-- Script SQL para crear la tabla cortes_linea
-- Ejecutar en tu base de datos MySQL

CREATE TABLE IF NOT EXISTS `cortes_linea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dispositivo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `consumo_actual` decimal(10,4) NOT NULL COMMENT 'Consumo en kWh cuando se cortó la línea',
  `limite_configurado` decimal(10,4) NOT NULL COMMENT 'Límite configurado en kWh',
  `fecha_corte` datetime NOT NULL COMMENT 'Fecha y hora del corte',
  `vista_por_usuario` tinyint(1) DEFAULT 0 COMMENT '0=No vista, 1=Vista por el usuario',
  `fecha_vista` datetime DEFAULT NULL COMMENT 'Fecha y hora cuando el usuario vio la alerta',
  `resuelto` tinyint(1) DEFAULT 0 COMMENT '0=Corte activo, 1=Resuelto',
  `fecha_resolucion` datetime DEFAULT NULL COMMENT 'Fecha y hora cuando se resolvió el corte',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_id_dispositivo` (`id_dispositivo`),
  KEY `idx_id_usuario` (`id_usuario`),
  KEY `idx_fecha_corte` (`fecha_corte`),
  KEY `idx_resuelto` (`resuelto`),
  KEY `idx_vista_por_usuario` (`vista_por_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla para registrar cortes de línea por consumo excesivo';

-- Insertar algunos datos de prueba (opcional)
INSERT INTO `cortes_linea` (`id_dispositivo`, `id_usuario`, `consumo_actual`, `limite_configurado`, `fecha_corte`, `vista_por_usuario`, `resuelto`) VALUES
(1, 1, 15.5000, 10.0000, '2024-10-16 20:30:00', 0, 0),
(2, 1, 12.7500, 10.0000, '2024-10-16 19:45:00', 1, 1),
(1, 1, 11.2000, 10.0000, '2024-10-16 18:20:00', 1, 1);

-- Verificar que la tabla se creó correctamente
SELECT 'Tabla cortes_linea creada exitosamente' as mensaje;
SELECT COUNT(*) as total_registros FROM cortes_linea;
