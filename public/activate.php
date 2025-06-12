<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../app/Config/Database.php';

$db = new Database();
$conn = $db->getConnection();

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['mac_address']) || !isset($data['activation_code'])) {
        $response['message'] = 'Faltan datos requeridos';
        echo json_encode($response);
        exit;
    }

    $mac_address = $data['mac_address'];
    $activation_code = $data['activation_code'];

    // Verificar si el código existe y está disponible
    $stmt = $conn->prepare("SELECT * FROM codigos_activacion WHERE codigo = ? AND estado = 'disponible'");
    $stmt->bind_param("s", $activation_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $codigo = $result->fetch_assoc();
        
        // Verificar si el dispositivo ya está registrado
        $stmt = $conn->prepare("SELECT id_dispositivo FROM dispositivos WHERE mac_address = ?");
        $stmt->bind_param("s", $mac_address);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $dispositivo = $result->fetch_assoc();
            $id_dispositivo = $dispositivo['id_dispositivo'];
        } else {
            // Registrar nuevo dispositivo
            $stmt = $conn->prepare("INSERT INTO dispositivos (mac_address, estado) VALUES (?, 'activo')");
            $stmt->bind_param("s", $mac_address);
            $stmt->execute();
            $id_dispositivo = $conn->insert_id;
        }

        // Actualizar el código de activación
        $stmt = $conn->prepare("UPDATE codigos_activacion SET id_dispositivo = ?, estado = 'usado', fecha_uso = CURRENT_TIMESTAMP WHERE id_codigo = ?");
        $stmt->bind_param("ii", $id_dispositivo, $codigo['id_codigo']);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Dispositivo activado correctamente';
        } else {
            $response['message'] = 'Error al activar el dispositivo';
        }
    } else {
        $response['message'] = 'Código de activación inválido o ya utilizado';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verificar estado de activación
    if (!isset($_GET['mac_address'])) {
        $response['message'] = 'Falta la dirección MAC';
        echo json_encode($response);
        exit;
    }

    $mac_address = $_GET['mac_address'];
    
    $stmt = $conn->prepare("SELECT d.id_dispositivo, d.estado, ca.codigo 
                           FROM dispositivos d 
                           LEFT JOIN codigos_activacion ca ON d.id_dispositivo = ca.id_dispositivo 
                           WHERE d.mac_address = ?");
    $stmt->bind_param("s", $mac_address);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $device = $result->fetch_assoc();
        $response['success'] = true;
        $response['is_activated'] = ($device['estado'] === 'activo' && $device['codigo'] !== null);
        $response['message'] = $response['is_activated'] ? 'Dispositivo activado' : 'Dispositivo no activado';
    } else {
        $response['message'] = 'Dispositivo no encontrado';
    }
}

echo json_encode($response); 