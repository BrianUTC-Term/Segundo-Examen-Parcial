<?php
// endpoint que genera archivos falsos y los guarda en la BD
// es llamado por fetch() desde el JS

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

// generar entre 3 y 8 archivos falsos al azar
$cantidad  = rand(3, 8);
$archivos  = [];
$usuario   = 'SOC-Analyst';

for($i = 0; $i < $cantidad; $i++){
    $archivos[] = [
        'nombre'  => generarNombreArchivo(),
        'tamanio' => generarTamanio()
    ];
}

// guardar en la BD
$resultado = guardarEscaneo($archivos, $usuario);

if($resultado['success']){
    // devolver los archivos al JS con sus ids de BD
    // re-consultar para obtener los ids reales
    $stmt = $pdo->prepare("SELECT * FROM archivos WHERE escaneo_id = :id ORDER BY id DESC");
    $stmt->execute(['id' => $resultado['escaneo_id']]);
    $archivosGuardados = $stmt->fetchAll();

    echo json_encode([
        'success'  => true,
        'archivos' => $archivosGuardados
    ]);
} else {
    echo json_encode(['success' => false]);
}
?>
