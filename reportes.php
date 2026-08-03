<?php
// endpoint que consulta reportes de incidentes previos (JSONPlaceholder)
// usa ApiClient (cURL)
// es llamado por fetch() desde el JS

require_once __DIR__ . '/includes/api_config.php';
require_once __DIR__ . '/includes/ApiClient.php';

header('Content-Type: application/json');

$api = new ApiClient();

try {
    $resultado = $api->get('/posts?_limit=5');

    echo json_encode([
        'success'  => $resultado['success'],
        'reportes' => $resultado['data']
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
