<?php
// Script PHP para generar datos de un mes completo
// Ejecutar desde el navegador: http://localhost/Tesina/generar_datos_mes.php

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'login';  // Cambia por el nombre de tu base de datos
$username = 'root';  // Cambia por tu usuario
$password = '';      // Cambia por tu contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🚀 GENERADOR DE DATOS DE ENERGÍA</h2>";
    
    // ========================================
    // 1. LIMPIAR DATOS PROBLEMÁTICOS
    // ========================================
    
    echo "<h3>🧹 Limpiando datos problemáticos...</h3>";
    
    // Eliminar registros recientes con kWh repetido
    $stmt = $pdo->prepare("DELETE FROM energia WHERE id_dispositivo = 2 AND fecha >= '2025-10-07 18:00:00'");
    $stmt->execute();
    $eliminados_recientes = $stmt->rowCount();
    echo "<p>✅ Eliminados $eliminados_recientes registros recientes problemáticos</p>";
    
    // Eliminar registros con kWh = 0
    $stmt = $pdo->prepare("DELETE FROM energia WHERE id_dispositivo = 2 AND kwh = 0");
    $stmt->execute();
    $eliminados_cero = $stmt->rowCount();
    echo "<p>✅ Eliminados $eliminados_cero registros con kWh = 0</p>";
    
    // ========================================
    // 2. GENERAR DATOS DE SEPTIEMBRE 2025
    // ========================================
    
    echo "<h3>📅 Generando datos de septiembre 2025...</h3>";
    
    $fecha_inicio = new DateTime('2025-09-01 00:00:00');
    $fecha_fin = new DateTime('2025-09-30 23:59:59');
    $kwh_actual = 35000.0;  // kWh inicial
    $registros_generados = 0;
    
    $stmt = $pdo->prepare("
        INSERT INTO energia (id_dispositivo, id_usuario, voltaje, corriente, potencia, kwh, limite_superado, fecha, mac_address) 
        VALUES (2, 1, ?, ?, ?, ?, 0, ?, '08:D1:F9:A5:2A:14')
    ");
    
    $fecha_actual = clone $fecha_inicio;
    while ($fecha_actual <= $fecha_fin) {
        // Calcular valores realistas
        $voltaje = 215 + (rand(0, 100) / 10);  // 215-225V
        $corriente = 2.5 + (rand(0, 150) / 100);  // 2.5-4.0A
        $potencia = $voltaje * $corriente;
        
        // Incrementar kWh gradualmente (cada 3 minutos)
        $incremento_kwh = ($potencia / 1000) * (3 / 60);  // 3 minutos en horas
        $kwh_actual += $incremento_kwh;
        
        // Insertar registro
        $stmt->execute([
            round($voltaje, 2),
            round($corriente, 4),
            round($potencia, 2),
            round($kwh_actual, 4),
            $fecha_actual->format('Y-m-d H:i:s')
        ]);
        
        $registros_generados++;
        
        // Avanzar 3 minutos
        $fecha_actual->add(new DateInterval('PT3M'));
        
        // Mostrar progreso cada 1000 registros
        if ($registros_generados % 1000 == 0) {
            echo "<p>📊 Generados $registros_generados registros... kWh actual: " . round($kwh_actual, 2) . "</p>";
            flush();
        }
    }
    
    echo "<p>✅ Septiembre completado: $registros_generados registros generados</p>";
    
    // ========================================
    // 3. GENERAR DATOS DE OCTUBRE 2025 (HASTA HOY)
    // ========================================
    
    echo "<h3>📅 Generando datos de octubre 2025...</h3>";
    
    $fecha_inicio = new DateTime('2025-10-01 00:00:00');
    $fecha_fin = new DateTime();  // Hasta ahora
    $registros_octubre = 0;
    
    $fecha_actual = clone $fecha_inicio;
    while ($fecha_actual <= $fecha_fin) {
        // Calcular valores realistas
        $voltaje = 215 + (rand(0, 100) / 10);  // 215-225V
        $corriente = 2.5 + (rand(0, 150) / 100);  // 2.5-4.0A
        $potencia = $voltaje * $corriente;
        
        // Incrementar kWh gradualmente
        $incremento_kwh = ($potencia / 1000) * (3 / 60);  // 3 minutos en horas
        $kwh_actual += $incremento_kwh;
        
        // Insertar registro
        $stmt->execute([
            round($voltaje, 2),
            round($corriente, 4),
            round($potencia, 2),
            round($kwh_actual, 4),
            $fecha_actual->format('Y-m-d H:i:s')
        ]);
        
        $registros_octubre++;
        
        // Avanzar 3 minutos
        $fecha_actual->add(new DateInterval('PT3M'));
        
        // Mostrar progreso cada 500 registros
        if ($registros_octubre % 500 == 0) {
            echo "<p>📊 Generados $registros_octubre registros de octubre... kWh actual: " . round($kwh_actual, 2) . "</p>";
            flush();
        }
    }
    
    echo "<p>✅ Octubre completado: $registros_octubre registros generados</p>";
    
    // ========================================
    // 4. VERIFICAR RESULTADOS
    // ========================================
    
    echo "<h3>📊 Verificando resultados...</h3>";
    
    // Estadísticas finales
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total_registros,
            MIN(fecha) as primera_fecha,
            MAX(fecha) as ultima_fecha,
            MIN(kwh) as min_kwh,
            MAX(kwh) as max_kwh,
            ROUND(AVG(kwh), 2) as avg_kwh
        FROM energia 
        WHERE id_dispositivo = 2
    ");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Métrica</th><th>Valor</th></tr>";
    echo "<tr><td>Total registros</td><td>{$stats['total_registros']}</td></tr>";
    echo "<tr><td>Primera fecha</td><td>{$stats['primera_fecha']}</td></tr>";
    echo "<tr><td>Última fecha</td><td>{$stats['ultima_fecha']}</td></tr>";
    echo "<tr><td>kWh mínimo</td><td>{$stats['min_kwh']}</td></tr>";
    echo "<tr><td>kWh máximo</td><td>{$stats['max_kwh']}</td></tr>";
    echo "<tr><td>kWh promedio</td><td>{$stats['avg_kwh']}</td></tr>";
    echo "</table>";
    
    // Últimos 10 registros
    echo "<h4>📋 Últimos 10 registros:</h4>";
    $stmt = $pdo->query("
        SELECT fecha, voltaje, corriente, potencia, kwh 
        FROM energia 
        WHERE id_dispositivo = 2 
        ORDER BY fecha DESC 
        LIMIT 10
    ");
    $ultimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Fecha</th><th>Voltaje</th><th>Corriente</th><th>Potencia</th><th>kWh</th></tr>";
    foreach ($ultimos as $registro) {
        echo "<tr>";
        echo "<td>{$registro['fecha']}</td>";
        echo "<td>{$registro['voltaje']}</td>";
        echo "<td>{$registro['corriente']}</td>";
        echo "<td>{$registro['potencia']}</td>";
        echo "<td>{$registro['kwh']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>✅ ¡Datos generados exitosamente!</h3>";
    echo "<p>Ahora puedes:</p>";
    echo "<ul>";
    echo "<li>🔍 Verificar la vista del dispositivo</li>";
    echo "<li>📊 Generar el informe PDF</li>";
    echo "<li>⚡ Probar el sistema de corte automático</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
