<?php
// conexion a la base de datos
// compatible con XAMPP en Windows y Mac

function getConfig(){
    $os = strtoupper(substr(PHP_OS, 0, 3));

    if($os === 'WIN'){
        return [
            'host'   => 'localhost',
            'port'   => '3306',
            'dbname' => 'archivo_fantasma',
            'user'   => 'root',
            'pass'   => '',
            'socket' => null
        ];
    } else {
        // buscar socket de mysql en mac
        $sockets = [
            '/tmp/mysql.sock',
            '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock',
            '/opt/homebrew/var/mysql/mysql.sock'
        ];

        $socket = null;
        foreach($sockets as $s){
            if(file_exists($s)){
                $socket = $s;
                break;
            }
        }

        return [
            'host'   => 'localhost',
            'port'   => '3306',
            'dbname' => 'archivo_fantasma',
            'user'   => 'root',
            'pass'   => '',
            'socket' => $socket
        ];
    }
}

$config = getConfig();

try {
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];

    if($config['socket'] && file_exists($config['socket'])){
        $dsn = "mysql:unix_socket={$config['socket']};dbname={$config['dbname']};charset=utf8mb4";
    } else {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
    }

    $pdo = new PDO($dsn, $config['user'], $config['pass'], $opciones);

} catch(PDOException $e){
    die(json_encode(['error' => 'Error de conexion: ' . $e->getMessage()]));
}
?>
