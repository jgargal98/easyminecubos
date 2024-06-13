<?php
session_start();

// Verificar si se han enviado datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ruta al archivo JSON basado en la sesión del usuario
    if (isset($_SESSION['usuario'])) {
        $user = $_SESSION['usuario'];
        $jsonFile = "../propiedades/{$user}-ops.json";
    } else {
        die("Error: No se ha iniciado sesión.");
    }

    // Leer y decodificar el JSON existente si existe
    $jugadores = [];
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $jugadores = json_decode($jsonData, true);

        if ($jugadores === null || json_last_error() !== JSON_ERROR_NONE) {
            die("Error al leer el archivo JSON.");
        }
    }

    // Inicializar el array de jugadores si está vacío o no existe
    if (!isset($jugadores['jugadores']) || !is_array($jugadores['jugadores'])) {
        $jugadores['jugadores'] = [];
    }

    // Procesar la eliminación de jugadores si se han marcado checkboxes
    if (isset($_POST['eliminar']) && is_array($_POST['eliminar'])) {
        foreach ($_POST['eliminar'] as $indice) {
            // Verificar si el índice existe y eliminar el jugador correspondiente
            if (isset($jugadores['jugadores'][$indice])) {
                unset($jugadores['jugadores'][$indice]);
            }
        }
        // Reindexar el array después de eliminar elementos
        $jugadores['jugadores'] = array_values($jugadores['jugadores']);
    }

    // Procesar la adición de un nuevo jugador si se han proporcionado los datos
    if (isset($_POST["name"], $_POST["level"])) {
        $nuevoJugador = [
            "name" => htmlspecialchars($_POST["name"]), // Sanitizar el nombre del jugador
            "level" => intval($_POST["level"]) // Convertir nivel a entero
        ];

        // Añadir el nuevo jugador al array de jugadores
        $jugadores['jugadores'][] = $nuevoJugador;
    }

    // Convertir nuevamente el array a JSON
    $nuevoJsonData = json_encode($jugadores, JSON_PRETTY_PRINT);

    // Guardar el JSON actualizado de vuelta en el archivo
    if (file_put_contents($jsonFile, $nuevoJsonData)) {
        echo "Los datos de los jugadores han sido actualizados correctamente.";
    } else {
        echo "Error al guardar los datos de los jugadores.";
    }
}