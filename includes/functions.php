<?php
// funciones reutilizables del sistema

// genera un nombre de archivo fantasma aleatorio
function generarNombreArchivo(){
    $prefijos = ['ghost_', 'shadow_', 'phantom_', 'void_', 'null_', 'spec_'];
    $ext      = ['.exe', '.dll', '.sys', '.tmp', '.dat', '.bin'];
    $hash     = substr(md5(uniqid()), 0, 8);
    return $prefijos[array_rand($prefijos)] . $hash . $ext[array_rand($ext)];
}

// genera un tamanio de archivo aleatorio
function generarTamanio(){
    $num  = rand(1, 999);
    $unit = ['KB', 'MB', 'GB'];
    return $num . ' ' . $unit[array_rand($unit)];
}

// guarda un escaneo y sus archivos en la BD
function guardarEscaneo($archivos, $usuario){
    global $pdo;

    try {
        // insertar el escaneo
        $stmt = $pdo->prepare("INSERT INTO escaneos (cantidad_archivos, usuario) VALUES (:cantidad, :usuario)");
        $stmt->execute([
            'cantidad' => count($archivos),
            'usuario'  => $usuario
        ]);

        $escaneoId = $pdo->lastInsertId();

        // insertar cada archivo
        $stmt = $pdo->prepare("INSERT INTO archivos (nombre, tamanio, escaneo_id) VALUES (:nombre, :tamanio, :escaneo_id)");

        foreach($archivos as $archivo){
            $stmt->execute([
                'nombre'    => $archivo['nombre'],
                'tamanio'   => $archivo['tamanio'],
                'escaneo_id' => $escaneoId
            ]);
        }

        return ['success' => true, 'escaneo_id' => $escaneoId];

    } catch(PDOException $e){
        error_log("Error en guardarEscaneo: " . $e->getMessage());
        return ['success' => false];
    }
}

// obtiene todos los archivos con su estado peligroso
function getArchivos($soloPeligrosos = false){
    global $pdo;

    try {
        if($soloPeligrosos){
            $stmt = $pdo->prepare("SELECT a.*, e.usuario FROM archivos a JOIN escaneos e ON a.escaneo_id = e.id WHERE a.peligroso = 1 ORDER BY a.fecha_detectado DESC");
        } else {
            $stmt = $pdo->prepare("SELECT a.*, e.usuario FROM archivos a JOIN escaneos e ON a.escaneo_id = e.id ORDER BY a.fecha_detectado DESC");
        }

        $stmt->execute();
        return $stmt->fetchAll();

    } catch(PDOException $e){
        error_log("Error en getArchivos: " . $e->getMessage());
        return [];
    }
}

// marca o desmarca un archivo como peligroso
function togglePeligroso($id){
    global $pdo;

    try {
        // usar operador ternario para invertir el valor actual
        $stmt = $pdo->prepare("UPDATE archivos SET peligroso = IF(peligroso = 1, 0, 1) WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        return ['success' => true];

    } catch(PDOException $e){
        error_log("Error en togglePeligroso: " . $e->getMessage());
        return ['success' => false];
    }
}

// obtiene estadisticas generales
function getStats(){
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM archivos");
        $stmt->execute();
        $total = $stmt->fetch()['total'];

        $stmt = $pdo->prepare("SELECT COUNT(*) as peligrosos FROM archivos WHERE peligroso = 1");
        $stmt->execute();
        $peligrosos = $stmt->fetch()['peligrosos'];

        $stmt = $pdo->prepare("SELECT COUNT(*) as escaneos FROM escaneos");
        $stmt->execute();
        $escaneos = $stmt->fetch()['escaneos'];

        return [
            'total'      => $total,
            'peligrosos' => $peligrosos,
            'escaneos'   => $escaneos
        ];

    } catch(PDOException $e){
        error_log("Error en getStats: " . $e->getMessage());
        return ['total' => 0, 'peligrosos' => 0, 'escaneos' => 0];
    }
}
?>
