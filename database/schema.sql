-- base de datos del sistema de archivos fantasma
CREATE DATABASE IF NOT EXISTS archivo_fantasma;
USE archivo_fantasma;

-- tabla de escaneos realizados
CREATE TABLE escaneos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME DEFAULT NOW(),
    cantidad_archivos INT NOT NULL,
    usuario VARCHAR(100) NOT NULL
);

-- tabla de archivos detectados en cada escaneo
CREATE TABLE archivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    tamanio VARCHAR(50) NOT NULL,
    fecha_detectado DATETIME DEFAULT NOW(),
    escaneo_id INT NOT NULL,
    peligroso TINYINT(1) DEFAULT 0,

    FOREIGN KEY (escaneo_id) REFERENCES escaneos(id) ON DELETE CASCADE
);
