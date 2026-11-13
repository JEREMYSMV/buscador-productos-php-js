<?php
// Servicio GET auxiliar: lista de categorías para el formulario de filtros
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../models/Producto.php';

try {
    echo json_encode((new Producto())->obtenerCategorias(), JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'Error al consultar las categorías']);
}
