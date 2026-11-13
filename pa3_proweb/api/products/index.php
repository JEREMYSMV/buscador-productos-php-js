<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../controllers/ProductoController.php';

try {
    $controlador = new ProductoController();

    // Consultar detalle de producto por ID
    if (isset($_GET['id'])) {

        $resultado = $controlador->obtenerDetallePorId($_GET['id']);

        http_response_code($resultado['codigo']);

        echo json_encode(
            $resultado['respuesta'],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    // Listar todos los productos
    $resultado = $controlador->listarConFiltros([
        'pagina' => 1,
        'por_pagina' => 100
    ]);

    http_response_code($resultado['codigo']);

    echo json_encode(
        $resultado['respuesta'],
        JSON_UNESCAPED_UNICODE
    );

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'status' => false,
        'message' => 'Error interno del servidor.'
    ]);
}