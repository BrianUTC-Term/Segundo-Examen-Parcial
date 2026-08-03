<?php
    /*
        Configuracion Global del Proyecto
    */

    // URL Base del API
    define('API_BASE_URL', 'https://jsonplaceholder.typicode.com');

    // Timeout para las peticiones
    define('API_TIMEOUT', 10);

    // Headers por defecto
    define('API_HEADERS', [
        'Accept: application/json',
        'Content-Type: application/json',
        'User-Agent: PHP-API-Client/1.0'
    ]);
?>
