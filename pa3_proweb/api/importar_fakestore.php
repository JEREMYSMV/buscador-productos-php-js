<?php
// Ejecutar una vez desde el navegador o Postman: /api/importar_fakestore.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config/conexion.php';

try {
    $db = (new Conexion())->conectar();
    $json = @file_get_contents('https://fakestoreapi.com/products');
    if ($json === false) {
        throw new Exception('No se pudo conectar con Fake Store API. Verifica la conexión a internet del servidor.');
    }
    $items = json_decode($json, true);
    if (!is_array($items)) throw new Exception('La respuesta de Fake Store API no es válida.');

    $db->beginTransaction();
    $catStmt = $db->prepare('INSERT INTO categorias (title) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)');
    $catIdStmt = $db->prepare('SELECT id FROM categorias WHERE title = ?');
    $prodStmt = $db->prepare('INSERT IGNORE INTO productos (id,title,description,price,categoria_id,stock) VALUES (?,?,?,?,?,0)');    $delImg = $db->prepare('DELETE FROM imagenes WHERE producto_id = ?');
    $imgStmt = $db->prepare('INSERT INTO imagenes (producto_id,url) VALUES (?,?)');

    foreach ($items as $item) {
        $category = (string)($item['category'] ?? 'Sin categoría');
        $catStmt->execute([$category]);
        $catIdStmt->execute([$category]);
        $categoryId = (int)$catIdStmt->fetchColumn();
        $prodStmt->execute([(int)$item['id'], (string)$item['title'], (string)$item['description'], (float)$item['price'], $categoryId]);
        if ($prodStmt->rowCount() > 0 && !empty($item['image'])) {
    $imgStmt->execute([
        (int)$item['id'],
        (string)$item['image']
    ]);
}
    }
    $db->commit();
    echo json_encode(['status'=>true,'message'=>'Productos importados correctamente. El stock quedó en 0 porque Fake Store API no proporciona ese dato.','total'=>count($items)], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    http_response_code(500);
    echo json_encode(['status'=>false,'message'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
