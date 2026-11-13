<?php
// REQ 2: Endpoint para listado con filtros y paginación

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../controllers/ProductoController.php';

// Función para devolver respuestas HTTP en JSON
function responder(int $codigo, array $datos): void {
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

// Permitir solicitudes OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// El servicio solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    responder(405, [
        'status' => false,
        'message' => 'Método no permitido. Use POST.'
    ]);
}

// Obtener los datos enviados por el cliente
$entrada = $_POST;

if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
    $entrada = json_decode(file_get_contents('php://input'), true);

    if (!is_array($entrada)) {
        responder(400, [
            'status' => false,
            'message' => 'El cuerpo JSON no es válido.'
        ]);
    }
}

// Enviar la solicitud al controlador
try {
    $controlador = new ProductoController();
    $resultado = $controlador->listarConFiltros($entrada);

    responder(
        $resultado['codigo'],
        $resultado['respuesta']
    );

} catch (Throwable $e) {
    responder(500, [
        'status' => false,
        'message' => 'Error interno del servidor.'
    ]);
}