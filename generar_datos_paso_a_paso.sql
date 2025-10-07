-- Script PASO A PASO para generar datos de un mes
-- Ejecutar cada sección por separado en phpMyAdmin

-- ========================================
-- PASO 1: LIMPIAR DATOS PROBLEMÁTICOS
-- ========================================

-- Eliminar registros recientes con kWh repetido
DELETE FROM energia 
WHERE id_dispositivo = 2 
AND fecha >= '2025-10-07 18:00:00';

-- Eliminar registros con kWh = 0
DELETE FROM energia 
WHERE id_dispositivo = 2 
AND kwh = 0;

-- Verificar limpieza
SELECT COUNT(*) as registros_restantes FROM energia WHERE id_dispositivo = 2;

-- ========================================
-- PASO 2: GENERAR DATOS DE SEPTIEMBRE (DÍA POR DÍA)
-- ========================================

-- Día 1 de septiembre
INSERT INTO energia (id_dispositivo, id_usuario, voltaje, corriente, potencia, kwh, limite_superado, fecha, mac_address)
SELECT 
    2, 1,
    ROUND(215 + (RAND() * 10), 2),
    ROUND(2.5 + (RAND() * 1.5), 4),
    ROUND((215 + (RAND() * 10)) * (2.5 + (RAND() * 1.5)), 2),
    ROUND(35000 + (@row_number := @row_number + 1) * 0.001, 4),
    0,
    DATE_ADD('2025-09-01 00:00:00', INTERVAL (@row_number - 1) * 3 MINUTE),
    '08:D1:F9:A5:2A:14'
FROM 
    (SELECT @row_number := 0) r,
    (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
    (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
    (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3
WHERE (@row_number - 1) * 3 < 1440;  -- 1440 minutos = 24 horas

-- Continuar con más días...
-- (Repetir el patrón para cada día del mes)

-- ========================================
-- PASO 3: GENERAR DATOS DE OCTUBRE (HASTA HOY)
-- ========================================

-- Día 1 de octubre
INSERT INTO energia (id_dispositivo, id_usuario, voltaje, corriente, potencia, kwh, limite_superado, fecha, mac_address)
SELECT 
    2, 1,
    ROUND(215 + (RAND() * 10), 2),
    ROUND(2.5 + (RAND() * 1.5), 4),
    ROUND((215 + (RAND() * 10)) * (2.5 + (RAND() * 1.5)), 2),
    ROUND(36000 + (@row_number := @row_number + 1) * 0.001, 4),
    0,
    DATE_ADD('2025-10-01 00:00:00', INTERVAL (@row_number - 1) * 3 MINUTE),
    '08:D1:F9:A5:2A:14'
FROM 
    (SELECT @row_number := 0) r,
    (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
    (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
    (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3
WHERE (@row_number - 1) * 3 < 1440;

-- ========================================
-- PASO 4: VERIFICAR RESULTADOS
-- ========================================

-- Estadísticas finales
SELECT 
    COUNT(*) as total_registros,
    MIN(fecha) as primera_fecha,
    MAX(fecha) as ultima_fecha,
    MIN(kwh) as min_kwh,
    MAX(kwh) as max_kwh,
    ROUND(AVG(kwh), 2) as avg_kwh
FROM energia 
WHERE id_dispositivo = 2;

-- Últimos 10 registros
SELECT 
    fecha,
    voltaje,
    corriente,
    potencia,
    kwh
FROM energia 
WHERE id_dispositivo = 2 
ORDER BY fecha DESC 
LIMIT 10;
