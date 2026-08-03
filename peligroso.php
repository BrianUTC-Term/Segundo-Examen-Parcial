<?php
// endpoint para marcar/desmarcar un archivo como peligroso
// recibe el id por POST y actualiza la BD

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if($id <= 0){
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

$resultado = togglePeligroso($id);
echo json_encode($resultado);
?>
