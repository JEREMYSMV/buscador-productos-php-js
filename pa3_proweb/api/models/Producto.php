<?php
require_once __DIR__ . '/../config/conexion.php';

class Producto {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    // REQ 1: Listado de productos
    public function obtenerProductos() {
        $sql = "SELECT
                    p.id,
                    p.title AS nombre,
                    p.description AS descripcion,
                    p.price AS precio,
                    c.title AS categoria,
                    p.categoria_id,
                    p.stock,
                    GROUP_CONCAT(
                        DISTINCT i.url SEPARATOR '|||'
                    ) AS imagenes
                FROM productos p
                LEFT JOIN categorias c
                    ON p.categoria_id = c.id
                LEFT JOIN imagenes i
                    ON p.id = i.producto_id
                GROUP BY
                    p.id, p.title, p.description,
                    p.price, c.title, p.categoria_id, p.stock
                ORDER BY p.id";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        $productos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        foreach ($productos as &$producto) {
            $producto['imagenes'] = !empty($producto['imagenes'])
                ? explode('|||', $producto['imagenes'])
                : [];
        }
        unset($producto);

        return $productos;
    }

    // REQ 2: Detalle de un producto por ID
    public function obtenerProductoPorId($id) {
        $sql = "SELECT
                    p.id,
                    p.title AS nombre,
                    p.description AS descripcion,
                    p.price AS precio,
                    c.title AS categoria,
                    p.categoria_id,
                    p.stock,
                    GROUP_CONCAT(
                        DISTINCT i.url SEPARATOR '|||'
                    ) AS imagenes
                FROM productos p
                LEFT JOIN categorias c
                    ON p.categoria_id = c.id
                LEFT JOIN imagenes i
                    ON p.id = i.producto_id
                WHERE p.id = :id
                GROUP BY
                    p.id, p.title, p.description,
                    p.price, c.title, p.categoria_id, p.stock";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':id' => $id]);

        $producto = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($producto !== false) {
            $producto['imagenes'] = !empty($producto['imagenes'])
                ? explode('|||', $producto['imagenes'])
                : [];
        }

        return $producto;
    }

    // REQ 3: Listado filtrado (solo se aplican los filtros enviados)
public function filtrarProductos(array $f, int $pagina = 1, int $porPagina = 10) {

    // Calcular el desplazamiento para la paginación
    $offset = ($pagina - 1) * $porPagina;

    // Construir las condiciones de búsqueda
    $where = " WHERE 1 = 1";
    $params = [];

    if (isset($f['title'])) {
        $where .= " AND p.title LIKE :title";
        $params[':title'] = '%' . $f['title'] . '%';
    }

    if (isset($f['description'])) {
        $where .= " AND p.description LIKE :description";
        $params[':description'] = '%' . $f['description'] . '%';
    }

    if (isset($f['categoria_id'])) {
        $where .= " AND p.categoria_id = :categoria_id";
        $params[':categoria_id'] = $f['categoria_id'];
    }

    if (isset($f['precio_min'])) {
        $where .= " AND p.price >= :precio_min";
        $params[':precio_min'] = $f['precio_min'];
    }

    if (isset($f['precio_max'])) {
        $where .= " AND p.price <= :precio_max";
        $params[':precio_max'] = $f['precio_max'];
    }

    if (isset($f['stock_min'])) {
        $where .= " AND p.stock >= :stock_min";
        $params[':stock_min'] = $f['stock_min'];
    }

    // Contar los productos que cumplen los filtros
    $sqlTotal = "SELECT COUNT(*) FROM productos p" . $where;

    $consultaTotal = $this->conexion->prepare($sqlTotal);

    foreach ($params as $clave => $valor) {
        $consultaTotal->bindValue($clave, $valor);
    }

    $consultaTotal->execute();
    $totalRegistros = (int) $consultaTotal->fetchColumn();

    // Calcular el número total de páginas
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    // Obtener solamente los productos de la página solicitada
    $sql = "SELECT
                p.id,
                p.title AS nombre,
                p.description AS descripcion,
                p.price AS precio,
                c.title AS categoria,
                p.categoria_id,
                p.stock,
                GROUP_CONCAT(
                    DISTINCT i.url SEPARATOR '|||'
                ) AS imagenes
            FROM productos p
            LEFT JOIN categorias c
                ON p.categoria_id = c.id
            LEFT JOIN imagenes i
                ON p.id = i.producto_id"
            . $where .
            " GROUP BY
                p.id, p.title, p.description,
                p.price, c.title, p.categoria_id, p.stock
              ORDER BY p.id
              LIMIT :limite OFFSET :offset";

    $consulta = $this->conexion->prepare($sql);

    foreach ($params as $clave => $valor) {
        $consulta->bindValue($clave, $valor);
    }

    $consulta->bindValue(':limite', $porPagina, PDO::PARAM_INT);
    $consulta->bindValue(':offset', $offset, PDO::PARAM_INT);

    $consulta->execute();

    $productos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    // Convertir las imágenes en un arreglo
    foreach ($productos as &$producto) {
        $producto['imagenes'] = !empty($producto['imagenes'])
            ? explode('|||', $producto['imagenes'])
            : [];
    }
    unset($producto);

    // Respuesta con productos y metadatos de paginación
    return [
        'status' => true,
        'productos' => $productos,
        'paginacion' => [
            'pagina_actual' => $pagina,
            'por_pagina' => $porPagina,
            'total_registros' => $totalRegistros,
            'total_paginas' => $totalPaginas
        ]
    ];
}

    // Categorías (para llenar el select del formulario de filtros)
    public function obtenerCategorias() {
        $consulta = $this->conexion->prepare("SELECT id, title FROM categorias ORDER BY title");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
