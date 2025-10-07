-- Script para agregar campo de energía acumulada a la tabla energia
-- Ejecutar este script en tu base de datos

-- 1. Agregar el campo kwh_acumulado a la tabla energia
ALTER TABLE `energia` 
ADD COLUMN `kwh_acumulado` FLOAT DEFAULT 0.0 AFTER `kwh`;

-- 2. Actualizar los registros existentes con la energía acumulada
-- Esto calcula la suma acumulativa de kWh por dispositivo
UPDATE energia e1 
SET kwh_acumulado = (
    SELECT SUM(e2.kwh) 
    FROM energia e2 
    WHERE e2.id_dispositivo = e1.id_dispositivo 
    AND e2.fecha <= e1.fecha
    AND e2.kwh IS NOT NULL
)
WHERE e1.kwh IS NOT NULL;

-- 3. Crear índice para mejorar el rendimiento
CREATE INDEX idx_energia_dispositivo_fecha ON energia(id_dispositivo, fecha);

-- 4. Verificar que se actualizó correctamente
SELECT 
    id_dispositivo,
    COUNT(*) as total_registros,
    MIN(kwh_acumulado) as min_acumulado,
    MAX(kwh_acumulado) as max_acumulado,
    MAX(fecha) as ultima_fecha
FROM energia 
WHERE kwh_acumulado > 0
GROUP BY id_dispositivo;
