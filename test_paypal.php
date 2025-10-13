<?php
// Script de prueba para verificar el flujo de PayPal

echo "=== PRUEBA DEL FLUJO DE PAYPAL ===\n";

// Simular datos de sesión
session_start();

// Datos de prueba
$testData = [
    'id_usuario_registro' => 82, // Usuario creado en los logs
    'id_dispositivo' => 1,
    'datos_compra' => [
        'nombre' => 'Test',
        'apellido' => 'User',
        'email' => 'test@example.com',
        'direccion' => 'Test Address 123, Test City, 12345, Test Country'
    ],
    'es_usuario_existente' => false
];

// Establecer datos de sesión
foreach ($testData as $key => $value) {
    $_SESSION[$key] = $value;
}

echo "Datos de sesión establecidos:\n";
foreach ($testData as $key => $value) {
    echo "- $key: " . json_encode($value) . "\n";
}

// Simular datos de PayPal
$paypalData = (object) [
    'id' => 'PAYID-TEST123456789',
    'status' => 'COMPLETED',
    'payer' => (object) [
        'name' => (object) [
            'given_name' => 'Test',
            'surname' => 'User'
        ]
    ]
];

echo "\nDatos de PayPal simulados:\n";
echo "- ID: " . $paypalData->id . "\n";
echo "- Status: " . $paypalData->status . "\n";

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "Ahora puedes probar el flujo real de PayPal.\n";
echo "Los logs mostrarán información detallada del proceso.\n";
?>
