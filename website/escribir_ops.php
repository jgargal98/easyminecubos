<?php
session_start();

// Verificar si se han enviado datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ruta al archivo JSON
    $user = $_SESSION['usuario'];
    $jsonFile = "../propiedades/$user-ops.json";

    // Leer y decodificar el JSON existente si existe
    $jugadores = array();
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $jugadores = json_decode($jsonData, true);

        if ($jugadores === null || json_last_error() !== JSON_ERROR_NONE) {
            die("Error al leer el archivo JSON.");
        }
    }

    // Inicializar el array de jugadores si está vacío o no existe
    if (!isset($jugadores[0]) || !is_array($jugadores[0])) {
        $jugadores = array(
            array(
                "uuid" => "28a9e89d-da64-427d-a920-5324a8bad0d6",
                "name" => "camperosintomate",
                "level" => 4,
                "bypassesPlayerLimit" => false
            )
        );
    }

    // Procesar la eliminación de jugadores
    if (isset($_POST['eliminar']) && is_array($_POST['eliminar'])) {
        foreach ($_POST['eliminar'] as $nombreEliminar) {
            // Buscar y eliminar al jugador por nombre
            foreach ($jugadores as $indice => $jugador) {
                if ($jugador['name'] === $nombreEliminar) {
                    unset($jugadores[$indice]);
                    break;
                }
            }
        }
        // Reindexar el array después de eliminar elementos
        $jugadores = array_values($jugadores);
    }

    // Procesar la adición de un nuevo jugador
    if (isset($_POST["name"], $_POST["level"])) {
        $nuevoJugador = array(
            "name" => $_POST["name"],
            "level" => intval($_POST["level"]) // Convertir a entero
        );

        // Añadir el nuevo jugador al array de jugadores
        $jugadores[] = $nuevoJugador;
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