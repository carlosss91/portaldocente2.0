<?php
session_start();
require_once '../models/Adjudicacion.php';

// Verifica que el usuario haya iniciado sesión y tenga el rol adecuado
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php"); // Redirige al login si el usuario no está autenticado
    exit();
}

// Verifica si el ID del usuario está definido en la sesión
if (!isset($_SESSION["id_usuario"])) {
    die("Error: ID de usuario no definido en la sesión.");
}

// Crea una instancia del modelo de adjudicaciones
$adjudicacionModel = new Adjudicacion();

// Obtiene las adjudicaciones del usuario autenticado
$adjudicaciones = $adjudicacionModel->obtenerAdjudicaciones($_SESSION["id_usuario"]);

// Asegurar que $adjudicaciones sea un array antes de recorrerlo
if (!is_array($adjudicaciones)) {
    $adjudicaciones = []; // Si la consulta falla, se asigna un array vacío para evitar errores en el bucle
}

// Listado de islas y municipios actualizados
$municipios_por_isla = [
    "Tenerife" => ["Adeje", "Arafo", "Arico", "Arona", "Buenavista del Norte", "Candelaria", "El Rosario", "El Sauzal", "El Tanque", "Fasnia", "Garachico", "Granadilla de Abona", "Guía de Isora", "Güímar", "Icod de los Vinos", "La Guancha", "La Matanza de Acentejo", "La Orotava", "La Victoria de Acentejo", "Los Realejos", "Puerto de la Cruz", "San Cristóbal de La Laguna", "San Juan de la Rambla", "San Miguel de Abona", "Santa Cruz de Tenerife", "Santa Úrsula", "Santiago del Teide", "Tacoronte", "Tegueste", "Vilaflor de Chasna"],
    "Gran Canaria" => ["Agaete", "Agüimes", "Artenara", "Arucas", "Firgas", "Gáldar", "Ingenio", "La Aldea de San Nicolás", "Las Palmas de Gran Canaria", "Mogán", "Moya", "San Bartolomé de Tirajana", "Santa Brígida", "Santa Lucía de Tirajana", "Santa María de Guía", "Tejeda", "Telde", "Teror", "Valleseco", "Valsequillo de Gran Canaria", "Vega de San Mateo"],
    "Lanzarote" => ["Arrecife", "Haría", "San Bartolomé", "Teguise", "Tías", "Tinajo", "Yaiza"],
    "Fuerteventura" => ["Antigua", "Betancuria", "La Oliva", "Pájara", "Puerto del Rosario", "Tuineje"],
    "La Palma" => ["Barlovento", "Breña Alta", "Breña Baja", "Fuencaliente", "Garafía", "Los Llanos de Aridane", "El Paso", "Puntagorda", "Puntallana", "San Andrés y Sauces", "Santa Cruz de La Palma", "Tazacorte", "Tijarafe", "Villa de Mazo"],
    "La Gomera" => ["Agulo", "Alajeró", "Hermigua", "San Sebastián de La Gomera", "Valle Gran Rey", "Vallehermoso"],
    "El Hierro" => ["Frontera", "El Pinar de El Hierro", "Valverde"],
    "La Graciosa" => ["Caleta de Sebo"]
];

// Determina la página activa para resaltar la opción correspondiente en la barra lateral
$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adjudicaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/adjudicaciones.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <!-- Header y barra lateral -->
    <?php include 'partials/header.php'; ?>
    <?php include 'partials/sidebar.php'; ?>

    <!-- Contenido -->
    <main class="content">
        <h2 class="page-title">Adjudicaciones</h2>
        <button id="mostrarFormulario" class="btn btn-primary" onclick="toggleFormularioAdjudicaciones()">Nueva Adjudicación</button>
        
        <div id="formularioAdjudicacion" style="display:none;">
    <form action="../controllers/AdjudicacionController.php" method="POST">
        <div class="d-flex align-items-center mb-2 gap-3">
            <!-- Selector de isla -->
            <label for="isla">Isla:</label>
            <select name="isla" class="form-select" id="isla" onchange="actualizarMunicipios(this)">
                <option value="">Seleccione una isla</option>
                <?php foreach (array_keys($municipios_por_isla) as $isla): ?>
                    <option value="<?= $isla ?>"><?= $isla ?></option>
                <?php endforeach; ?>
            </select>
            
            <!-- Selector de municipio (se llenará dinámicamente) -->
            <label for="municipio">Municipio:</label>
            <select name="municipio" class="form-select" id="municipio">
                <option value="">Seleccione un municipio</option>
            </select>
            
            <!-- Botón de guardar, alineado a la derecha -->
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>

<!-- TABLA -->
<table class="table">
    <thead>
        <tr>
            <!-- Columna de número de orden con botón de ordenación -->
            <th>
                Nº
                <button type="button" class="btn btn-link p-0 sort-btn" data-sort="orden" title="Ordenar">&#8597;</button>
            </th>
            <!-- Columna Isla con botón de ordenación -->
            <th>
                Isla
                <button type="button" class="btn btn-link p-0 sort-btn" data-sort="isla" title="Ordenar">&#8597;</button>
            </th>
            <!-- Columna Municipio con botón de ordenación -->
            <th>
                Municipio
                <button type="button" class="btn btn-link p-0 sort-btn" data-sort="municipio" title="Ordenar">&#8597;</button>
            </th>
            <!-- Columna Fecha con botón de ordenación -->
            <th>
                Fecha
                <button type="button" class="btn btn-link p-0 sort-btn" data-sort="fecha" title="Ordenar">&#8597;</button>
            </th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="adjudicaciones-tbody">
        <?php 
        // Invertir el array para mostrar la más reciente primero
        $adjudicaciones = array_reverse($adjudicaciones);
        // Mostramos la más reciente primero (orden descendente)
        $orden = count($adjudicaciones);
        foreach ($adjudicaciones as $adj): ?>
        <tr data-id="<?= $adj['id_adjudicacion'] ?>">
            <!-- Número de orden -->
            <td><?= $orden-- ?></td>
            <!-- Isla -->
            <td><?= htmlspecialchars($adj["isla"] ?? 'No disponible') ?></td>
            <!-- Municipio -->
            <td><?= htmlspecialchars($adj["municipio"] ?? 'No disponible') ?></td>
            <!-- Fecha -->
            <td><?= htmlspecialchars($adj["fecha_adjudicacion"] ?? 'No disponible') ?></td>
            <!-- Botón de eliminar -->
            <td>
                <button class="btn btn-light border" onclick="eliminarAdjudicacion(this)" title="Eliminar">
                    <img src="../assets/icons/eliminar.png" alt="Eliminar" width="20">
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>  

    <!-- Pie de página -->
    <?php include 'partials/footer.php'; ?>
</body>
</html>
