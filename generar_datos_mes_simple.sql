-- Script SIMPLE para generar datos de un mes completo
-- Ejecutar en phpMyAdmin o cliente MySQL

-- ========================================
-- 1. LIMPIAR DATOS PROBLEMÁTICOS
-- ========================================

-- Eliminar registros recientes con kWh repetido
DELETE FROM energia 
WHERE id_dispositivo = 2 
AND fecha >= '2025-10-07 18:00:00';

-- Eliminar registros con kWh = 0
DELETE FROM energia 
WHERE id_dispositivo = 2 
AND kwh = 0;

-- ========================================
-- 2. GENERAR DATOS DE SEPTIEMBRE 2025
-- ========================================

-- Insertar datos cada 3 minutos durante septiembre
INSERT INTO energia (id_dispositivo, id_usuario, voltaje, corriente, potencia, kwh, limite_superado, fecha, mac_address)
SELECT 
    2 as id_dispositivo,
    1 as id_usuario,
    ROUND(215 + (RAND() * 10), 2) as voltaje,
    ROUND(2.5 + (RAND() * 1.5), 4) as corriente,
    ROUND((215 + (RAND() * 10)) * (2.5 + (RAND() * 1.5)), 2) as potencia,
    ROUND(35000 + (ROW_NUMBER() OVER (ORDER BY t.fecha) * 0.001), 4) as kwh,
    0 as limite_superado,
    t.fecha,
    '08:D1:F9:A5:2A:14' as mac_address
FROM (
    SELECT DATE_ADD('2025-09-01 00:00:00', INTERVAL (t1.n + t2.n * 10 + t3.n * 100 + t4.n * 1000) * 3 MINUTE) as fecha
    FROM 
        (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
        (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
        (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
        (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
    WHERE DATE_ADD('2025-09-01 00:00:00', INTERVAL (t1.n + t2.n * 10 + t3.n * 100 + t4.n * 1000) * 3 MINUTE) <= '2025-09-30 23:59:59'
) t;

-- ========================================
-- 3. GENERAR DATOS DE OCTUBRE 2025 (HASTA HOY)
-- ========================================

-- Insertar datos de octubre continuando desde septiembre
INSERT INTO energia (id_dispositivo, id_usuario, voltaje, corriente, potencia, kwh, limite_superado, fecha, mac_address)
SELECT 
    2 as id_dispositivo,
    1 as id_usuario,
    ROUND(215 + (RAND() * 10), 2) as voltaje,
    ROUND(2.5 + (RAND() * 1.5), 4) as corriente,
    ROUND((215 + (RAND() * 10)) * (2.5 + (RAND() * 1.5)), 2) as potencia,
    ROUND(36000 + (ROW_NUMBER() OVER (ORDER BY t.fecha) * 0.001), 4) as kwh,
    0 as limite_superado,
    t.fecha,
    '08:D1:F9:A5:2A:14' as mac_address
FROM (
    SELECT DATE_ADD('2025-10-01 00:00:00', INTERVAL (t1.n + t2.n * 10 + t3.n * 100) * 3 MINUTE) as fecha
    FROM 
        (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
        (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
        (SELECT 0 as n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3
    WHERE DATE_ADD('2025-10-01 00:00:00', INTERVAL (t1.n + t2.n * 10 + t3.n * 100) * 3 MINUTE) <= NOW()
) t;

-- ========================================
-- 4. VERIFICAR RESULTADOS
-- ========================================

-- Estadísticas generales
SELECT 
    'ESTADÍSTICAS FINALES' as info,
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
    'ÚLTIMOS 10 REGISTROS' as info,
    fecha,
    voltaje,
    corriente,
    potencia,
    kwh
FROM energia 
WHERE id_dispositivo = 2 
ORDER BY fecha DESC 
LIMIT 10;
