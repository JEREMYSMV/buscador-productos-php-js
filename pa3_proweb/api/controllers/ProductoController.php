<?php
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    private Producto $modelo;

    public function __construct() {
        $this->modelo = new Producto();
    }

    // REQ 2: Validar filtros y paginación
    public function listarConFiltros(array $entrada): array {
        $filtros = [];
        $errores = [];

        // Validar título y descripción
        foreach (['title', 'description'] as $campo) {
            if (isset($entrada[$campo]) && $entrada[$campo] !== '') {
                if (!is_string($entrada[$campo])) {
                    $errores[] = "$campo debe ser texto.";
                    continue;
                }

                $valor = trim($entrada[$campo]);

                if (mb_strlen($valor) > 255) {
                    $errores[] = "$campo no debe superar 255 caracteres.";
                } elseif ($valor !== '') {
                    $filtros[$campo] = $valor;
                }
            }
        }

        // Validar categoría
        if (isset($entrada['categoria_id']) && $entrada['categoria_id'] !== '') {
            $categoria = filter_var(
                $entrada['categoria_id'],
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            );

            if ($categoria === false) {
                $errores[] = 'categoria_id debe ser un entero positivo.';
            } else {
                $filtros['categoria_id'] = $categoria;
            }
        }

        // Validar precios
        foreach (['precio_min', 'precio_max'] as $campo) {
            if (isset($entrada[$campo]) && $entrada[$campo] !== '') {
                $precio = filter_var($entrada[$campo], FILTER_VALIDATE_FLOAT);

                if ($precio === false || $precio < 0) {
                    $errores[] = "$campo debe ser un número mayor o igual a 0.";
                } else {
                    $filtros[$campo] = $precio;
                }
            }
        }

        if (
            isset($filtros['precio_min'], $filtros['precio_max']) &&
            $filtros['precio_min'] > $filtros['precio_max']
        ) {
            $errores[] = 'El precio mínimo no puede superar el máximo.';
        }

        // Validar stock
        if (isset($entrada['stock_min']) && $entrada['stock_min'] !== '') {
            $stock = filter_var(
                $entrada['stock_min'],
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 0]]
            );

            if ($stock === false) {
                $errores[] = 'stock_min debe ser un entero mayor o igual a 0.';
            } else {
                $filtros['stock_min'] = $stock;
            }
        }

        // Validar número de página
        $pagina = filter_var(
            $entrada['pagina'] ?? 1,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($pagina === false) {
            $errores[] = 'pagina debe ser un entero mayor o igual a 1.';
        }

        // Validar cantidad de productos por página
        $porPagina = filter_var(
            $entrada['por_pagina'] ?? 10,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 100]]
        );

        if ($porPagina === false) {
            $errores[] = 'por_pagina debe estar entre 1 y 100.';
        }

        if (!empty($errores)) {
            return [
                'codigo' => 400,
                'respuesta' => [
                    'status' => false,
                    'message' => 'Parámetros inválidos.',
                    'errores' => $errores
                ]
            ];
        }

        // Consultar al modelo
        try {
            $resultado = $this->modelo->filtrarProductos(
                $filtros,
                $pagina,
                $porPagina
            );

            return [
                'codigo' => 200,
                'respuesta' => $resultado
            ];
        } catch (Throwable $e) {
            return [
                'codigo' => 500,
                'respuesta' => [
                    'status' => false,
                    'message' => 'Error al consultar los productos.'
                ]
            ];
        }
    }

    // REQ 1: Consultar detalle de producto por ID
    public function obtenerDetallePorId($id): array {

        // Validar el ID recibido
        $idValidado = filter_var(
            $id,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($idValidado === false) {
            return [
                'codigo' => 400,
                'respuesta' => [
                    'status' => false,
                    'message' => 'El ID debe ser un entero positivo.'
                ]
            ];
        }

        try {
            // Consultar el modelo
            $producto = $this->modelo->obtenerProductoPorId($idValidado);

            if ($producto === false) {
                return [
                    'codigo' => 404,
                    'respuesta' => [
                        'status' => false,
                        'message' => 'Producto no encontrado.'
                    ]
                ];
            }

            return [
                'codigo' => 200,
                'respuesta' => $producto
            ];

        } catch (Throwable $e) {
            return [
                'codigo' => 500,
                'respuesta' => [
                    'status' => false,
                    'message' => 'Error al consultar el producto.'
                ]
            ];
        }
    }

}