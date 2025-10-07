-- Script para limpiar datos problemáticos y generar datos de un mes completo
-- Ejecutar en phpMyAdmin o cliente MySQL

-- ========================================
-- 1. LIMPIAR DATOS PROBLEMÁTICOS
-- ========================================

-- Eliminar registros con kWh repetido (últimos registros problemáticos)
DELETE FROM energia 
WHERE id_dispositivo = 2 
AND fecha >= '2025-10-07 18:00:00'
AND kwh = 44062.8;

-- Eliminar registros con kWh = 0 (sin energía)
DELETE FROM energia 
WHERE id_dispositivo = 2 
AND kwh = 0;

-- ========================================
-- 2. GENERAR DATOS DE UN MES COMPLETO
-- ========================================

-- Variables para el generador
SET @fecha_inicio = '2025-09-01 00:00:00';
SET @fecha_fin = '2025-09-30 23:59:59';
SET @kwh_inicial = 35000.0;  -- kWh inicial del mes
SET @kwh_actual = @kwh_inicial;
SET @fecha_actual = @fecha_inicio;
SET @id_dispositivo = 2;
SET @id_usuario = 1;
SET @mac_address = '08:D1:F9:A5:2A:14';

-- Generar datos cada 3 minutos durante todo el mes
WHILE @fecha_actual <= @fecha_fin DO
    
    -- Calcular valores realistas
    SET @voltaje = 215 + (RAND() * 10);  -- 215-225V
    SET @corriente = 2.5 + (RAND() * 1.5);  -- 2.5-4.0A
    SET @potencia = @voltaje * @corriente;
    
    -- Incrementar kWh gradualmente (simulando consumo real)
    SET @incremento_kwh = (@potencia / 1000) * (3 / 60);  -- 3 minutos en horas
    SET @kwh_actual = @kwh_actual + @incremento_kwh;
    
    -- Insertar registro
    INSERT INTO energia (
        id_dispositivo, 
        id_usuario, 
        voltaje, 
        corriente, 
        potencia, 
        kwh, 
        limite_superado, 
        fecha, 
        mac_address
    ) VALUES (
        @id_dispositivo,
        @id_usuario,
        ROUND(@voltaje, 2),
        ROUND(@corriente, 4),
        ROUND(@potencia, 2),
        ROUND(@kwh_actual, 4),
        0,
        @fecha_actual,
        @mac_address
    );
    
    -- Avanzar 3 minutos
    SET @fecha_actual = DATE_ADD(@fecha_actual, INTERVAL 3 MINUTE);
    
END WHILE;

-- ========================================
-- 3. GENERAR DATOS DE OCTUBRE (ACTUAL)
-- ========================================

-- Resetear variables para octubre
SET @fecha_inicio = '2025-10-01 00:00:00';
SET @fecha_fin = NOW();  -- Hasta ahora
SET @kwh_inicial = @kwh_actual;  -- Continuar desde donde terminó septiembre
SET @kwh_actual = @kwh_inicial;
SET @fecha_actual = @fecha_inicio;

-- Generar datos de octubre
WHILE @fecha_actual <= @fecha_fin DO
    
    -- Calcular valores realistas
    SET @voltaje = 215 + (RAND() * 10);  -- 215-225V
    SET @corriente = 2.5 + (RAND() * 1.5);  -- 2.5-4.0A
    SET @potencia = @voltaje * @corriente;
    
    -- Incrementar kWh gradualmente
    SET @incremento_kwh = (@potencia / 1000) * (3 / 60);  -- 3 minutos en horas
    SET @kwh_actual = @kwh_actual + @incremento_kwh;
    
    -- Insertar registro
    INSERT INTO energia (
        id_dispositivo, 
        id_usuario, 
        voltaje, 
        corriente, 
        potencia, 
        kwh, 
        limite_superado, 
        fecha, 
        mac_address
    ) VALUES (
        @id_dispositivo,
        @id_usuario,
        ROUND(@voltaje, 2),
        ROUND(@corriente, 4),
        ROUND(@potencia, 2),
        ROUND(@kwh_actual, 4),
        0,
        @fecha_actual,
        @mac_address
    );
    
    -- Avanzar 3 minutos
    SET @fecha_actual = DATE_ADD(@fecha_actual, INTERVAL 3 MINUTE);
    
END WHILE;

-- ========================================
-- 4. VERIFICAR RESULTADOS
-- ========================================

-- Mostrar estadísticas finales
SELECT 
    'ESTADÍSTICAS FINALES' as info,
    COUNT(*) as total_registros,
    MIN(fecha) as primera_fecha,
    MAX(fecha) as ultima_fecha,
    MIN(kwh) as min_kwh,
    MAX(kwh) as max_kwh,
    ROUND(AVG(kwh), 2) as avg_kwh,
    ROUND(SUM(kwh), 2) as sum_kwh
FROM energia 
WHERE id_dispositivo = 2;

-- Mostrar últimos 10 registros
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

-- Mostrar distribución por día
SELECT 
    'DISTRIBUCIÓN POR DÍA' as info,
    DATE(fecha) as dia,
    COUNT(*) as registros,
    MIN(kwh) as kwh_min,
    MAX(kwh) as kwh_max,
    ROUND(MAX(kwh) - MIN(kwh), 4) as incremento_diario
FROM energia 
WHERE id_dispositivo = 2 
AND fecha >= '2025-09-01'
GROUP BY DATE(fecha)
ORDER BY dia DESC
LIMIT 10;
