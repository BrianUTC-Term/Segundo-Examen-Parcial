<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// leer filtro de la URL
$soloPeligrosos = isset($_GET['filtro']) && $_GET['filtro'] === 'peligrosos';
$archivos = getArchivos($soloPeligrosos);
$stats    = getStats();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOC Dashboard</title>
    <link rel="stylesheet" href="assets/css/estilo.css">
</head>
<body>  

    <!-- Cabecera del panel -->
    <header class="cabecera">
        <div class="cabecera-titulo">
            <h1>Explorador de Archivos</h1>
            <span class="subtitulo">/root/server/prod</span>
        </div>
        <div class="cabecera-usuario">
            <span>SOC-Analyst</span>
        </div>
    </header>

    <main class="contenedor">

        <!-- Tarjetas de estadisticas -->
        <div class="grilla-stats">
            <div class="stat-card">
                <span class="stat-numero"><?= $stats['total'] ?></span>
                <span class="stat-etiqueta">Archivos detectados</span>
            </div>
            <div class="stat-card peligro">
                <span class="stat-numero"><?= $stats['peligrosos'] ?></span>
                <span class="stat-etiqueta">Archivos peligrosos</span>
            </div>
            <div class="stat-card">
                <span class="stat-numero"><?= $stats['escaneos'] ?></span>
                <span class="stat-etiqueta">Escaneos realizados</span>
            </div>
        </div>

        <!-- Botones de accion -->
        <div class="barra-acciones">
            <button id="btn-escanear" class="btn btn-primario">Escanear</button>
            <button id="btn-reportes" class="btn btn-secundario">Ver Reportes</button>

            <!-- filtro de peligrosos -->
            <div class="filtros">
                <a href="index.php" class="btn btn-filtro <?= !$soloPeligrosos ? 'activo' : '' ?>">Todos</a>
                <a href="index.php?filtro=peligrosos" class="btn btn-filtro <?= $soloPeligrosos ? 'activo' : '' ?>">Solo peligrosos</a>
            </div>
        </div>

        <!-- indicador de auto-escaneo -->
        <div id="auto-scan-info" class="auto-scan-info">
            Auto-detección activa. Próximo escaneo en <span id="cuenta-regresiva">60</span>s
        </div>

        <!-- Tabla de archivos -->
        <div class="tabla-contenedor">
            <table id="tabla-archivos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tamaño</th>
                        <th>Fecha detectado</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-tabla">
                    <?php if(empty($archivos)): ?>
                    <tr>
                        <td colspan="6" class="sin-datos">No hay archivos detectados. Presiona Escanear.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($archivos as $archivo): ?>
                    <tr class="<?= $archivo['peligroso'] ? 'fila-peligrosa' : '' ?>" data-id="<?= $archivo['id'] ?>">
                        <td><?= htmlspecialchars($archivo['nombre']) ?></td>
                        <td><?= htmlspecialchars($archivo['tamanio']) ?></td>
                        <td><?= htmlspecialchars($archivo['fecha_detectado']) ?></td>
                        <td><?= htmlspecialchars($archivo['usuario']) ?></td>
                        <td>
                            <span class="badge <?= $archivo['peligroso'] ? 'badge-peligroso' : 'badge-normal' ?>">
                                <?= $archivo['peligroso'] ? 'Peligroso' : 'Normal' ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-marcar" onclick="togglePeligroso(<?= $archivo['id'] ?>, this)">
                                <?= $archivo['peligroso'] ? 'Desmarcar' : 'Marcar peligroso' ?>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <script src="assets/js/modal.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
