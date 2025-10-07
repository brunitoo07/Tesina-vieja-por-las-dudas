<?php
// Script para verificar los datos de energía en tu base de datos
// Ejecutar desde el navegador: http://localhost/Tesina/verificar_datos_energia.php

// Configuración de la base de datos (ajusta según tu configuración)
$host = 'localhost';
$dbname = 'login';  // Cambia por el nombre de tu base de datos
$username = 'root';  // Cambia por tu usuario
$password = '';      // Cambia por tu contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 VERIFICACIÓN DE DATOS DE ENERGÍA</h2>";
    
    // 1. Verificar dispositivos
    echo "<h3>📱 Dispositivos registrados:</h3>";
    $stmt = $pdo->query("SELECT id_dispositivo, nombre, mac_address FROM dispositivos");
    $dispositivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($dispositivos)) {
        echo "<p style='color: red;'>❌ No hay dispositivos registrados</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>MAC Address</th></tr>";
        foreach ($dispositivos as $dispositivo) {
            echo "<tr>";
            echo "<td>{$dispositivo['id_dispositivo']}</td>";
            echo "<td>{$dispositivo['nombre']}</td>";
            echo "<td>{$dispositivo['mac_address']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 2. Verificar datos de energía por dispositivo
    foreach ($dispositivos as $dispositivo) {
        $id_dispositivo = $dispositivo['id_dispositivo'];
        echo "<h3>⚡ Datos de energía para: {$dispositivo['nombre']} (ID: $id_dispositivo)</h3>";
        
        // Estadísticas generales
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(*) as total_registros,
                MIN(fecha) as primera_fecha,
                MAX(fecha) as ultima_fecha,
                MIN(kwh) as min_kwh,
                MAX(kwh) as max_kwh,
                AVG(kwh) as avg_kwh,
                SUM(kwh) as sum_kwh
            FROM energia 
            WHERE id_dispositivo = ?
        ");
        $stmt->execute([$id_dispositivo]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Métrica</th><th>Valor</th></tr>";
        echo "<tr><td>Total registros</td><td>{$stats['total_registros']}</td></tr>";
        echo "<tr><td>Primera fecha</td><td>{$stats['primera_fecha']}</td></tr>";
        echo "<tr><td>Última fecha</td><td>{$stats['ultima_fecha']}</td></tr>";
        echo "<tr><td>kWh mínimo</td><td>{$stats['min_kwh']}</td></tr>";
        echo "<tr><td>kWh máximo</td><td>{$stats['max_kwh']}</td></tr>";
        echo "<tr><td>kWh promedio</td><td>" . round($stats['avg_kwh'], 6) . "</td></tr>";
        echo "<tr><td>kWh suma total</td><td>{$stats['sum_kwh']}</td></tr>";
        echo "</table>";
        
        // Últimas 10 lecturas
        echo "<h4>📊 Últimas 10 lecturas:</h4>";
        $stmt = $pdo->prepare("
            SELECT fecha, voltaje, corriente, potencia, kwh 
            FROM energia 
            WHERE id_dispositivo = ? 
            ORDER BY fecha DESC 
            LIMIT 10
        ");
        $stmt->execute([$id_dispositivo]);
        $lecturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($lecturas)) {
            echo "<p style='color: red;'>❌ No hay lecturas para este dispositivo</p>";
        } else {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Fecha</th><th>Voltaje</th><th>Corriente</th><th>Potencia</th><th>kWh</th></tr>";
            foreach ($lecturas as $lectura) {
                echo "<tr>";
                echo "<td>{$lectura['fecha']}</td>";
                echo "<td>{$lectura['voltaje']}</td>";
                echo "<td>{$lectura['corriente']}</td>";
                echo "<td>{$lectura['potencia']}</td>";
                echo "<td>{$lectura['kwh']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        echo "<hr>";
    }
    
    // 3. Verificar límites de consumo
    echo "<h3>📏 Límites de consumo configurados:</h3>";
    $stmt = $pdo->query("
        SELECT lc.id_dispositivo, d.nombre, lc.limite_consumo, lc.fecha_creacion 
        FROM limites_consumo lc 
        JOIN dispositivos d ON lc.id_dispositivo = d.id_dispositivo
    ");
    $limites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($limites)) {
        echo "<p style='color: red;'>❌ No hay límites de consumo configurados</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Dispositivo</th><th>Límite (kWh)</th><th>Fecha creación</th></tr>";
        foreach ($limites as $limite) {
            echo "<tr>";
            echo "<td>{$limite['nombre']}</td>";
            echo "<td>{$limite['limite_consumo']}</td>";
            echo "<td>{$limite['fecha_creacion']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    echo "<p>Verifica la configuración de la base de datos en este archivo.</p>";
}
?>
