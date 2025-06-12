<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../app/Config/Database.php';

$db = new Database();
$conn = $db->getConnection();

$response = ['success' => false, 'message' => '', 'activation_code' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['mac_address'])) {
        $response['message'] = 'Falta la dirección MAC';
        echo json_encode($response);
        exit;
    }

    $mac_address = $data['mac_address'];
    
    // Verificar si el dispositivo ya existe
    $stmt = $conn->prepare("SELECT d.id_dispositivo, ca.codigo 
                           FROM dispositivos d 
                           LEFT JOIN codigos_activacion ca ON d.id_dispositivo = ca.id_dispositivo 
                           WHERE d.mac_address = ?");
    $stmt->bind_param("s", $mac_address);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $device = $result->fetch_assoc();
        if ($device['codigo']) {
            $response['success'] = true;
            $response['activation_code'] = $device['codigo'];
            $response['message'] = 'Código de activación recuperado';
        } else {
            // Generar nuevo código
            $activation_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
            
            $stmt = $conn->prepare("INSERT INTO codigos_activacion (codigo, id_dispositivo, estado, fecha_generacion) VALUES (?, ?, 'disponible', CURRENT_TIMESTAMP)");
            $stmt->bind_param("si", $activation_code, $device['id_dispositivo']);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['activation_code'] = $activation_code;
                $response['message'] = 'Código de activación generado correctamente';
            } else {
                $response['message'] = 'Error al generar el código de activación';
            }
        }
    } else {
        // Registrar nuevo dispositivo y generar código
        $conn->begin_transaction();
        
        try {
            // Insertar dispositivo
            $stmt = $conn->prepare("INSERT INTO dispositivos (mac_address, estado) VALUES (?, 'activo')");
            $stmt->bind_param("s", $mac_address);
            $stmt->execute();
            $id_dispositivo = $conn->insert_id;
            
            // Generar código
            $activation_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
            
            $stmt = $conn->prepare("INSERT INTO codigos_activacion (codigo, id_dispositivo, estado, fecha_generacion) VALUES (?, ?, 'disponible', CURRENT_TIMESTAMP)");
            $stmt->bind_param("si", $activation_code, $id_dispositivo);
            
            if ($stmt->execute()) {
                $conn->commit();
                $response['success'] = true;
                $response['activation_code'] = $activation_code;
                $response['message'] = 'Dispositivo registrado y código generado correctamente';
            } else {
                throw new Exception('Error al generar el código');
            }
        } catch (Exception $e) {
            $conn->rollback();
            $response['message'] = 'Error al registrar el dispositivo: ' . $e->getMessage();
        }
    }
}

echo json_encode($response); 