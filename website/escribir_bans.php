<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Dashboard - Easy Minecubos</title>
    <link rel='stylesheet' href='properties.css'>
</head>
<body>
    <div class='container'>
        <img src='assets/easy-minecubos.png' alt='Título de la Página'>
    </div>
    <div class='container'>

<?php
session_start();

// Verificar si se han enviado datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ruta al archivo JSON de jugadores baneados
    $user = $_SESSION['usuario'];
    $jsonFile = "../propiedades/$user-banned-players.json";

    // Leer y decodificar el JSON existente si existe
    $jugadoresBaneados = array();
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $jugadoresBaneados = json_decode($jsonData, true);

        if ($jugadoresBaneados === null || json_last_error() !== JSON_ERROR_NONE) {
            die("Error al leer el archivo JSON de jugadores baneados.");
        }
    }

    // Inicializar el array de jugadores baneados si está vacío o no existe
    if (!is_array($jugadoresBaneados)) {
        $jugadoresBaneados = array();
    }

    // Procesar la eliminación de jugadores baneados
    if (isset($_POST['eliminar']) && is_array($_POST['eliminar'])) {
        foreach ($_POST['eliminar'] as $nombreEliminar) {
            // Buscar y eliminar al jugador baneado por nombre
            foreach ($jugadoresBaneados as $indice => $jugadorBaneado) {
                if ($jugadorBaneado['name'] === $nombreEliminar) {
                    unset($jugadoresBaneados[$indice]);
                    break;
                }
            }
        }
        // Reindexar el array después de eliminar elementos
        $jugadoresBaneados = array_values($jugadoresBaneados);
    }

    // Procesar la adición de un nuevo jugador baneado
    if (isset($_POST["name"], $_POST["reason"])) {
        $nuevoJugadorBaneado = array(
            "name" => $_POST["name"],
            "created" => date('Y-m-d H:i:s O'),
            "source" => "Server",
            "expires" => "forever",
            "reason" => $_POST["reason"]
        );

        // Añadir el nuevo jugador baneado al array de jugadores baneados
        $jugadoresBaneados[] = $nuevoJugadorBaneado;
    }

    // Convertir nuevamente el array a JSON
    $nuevoJsonData = json_encode($jugadoresBaneados, JSON_PRETTY_PRINT);

    // Guardar el JSON actualizado de vuelta en el archivo
    if (file_put_contents($jsonFile, $nuevoJsonData)) {
        echo "Los datos de los jugadores baneados han sido actualizados correctamente.";
    } else {
        echo "Error al guardar los datos de los jugadores baneados.";
    }
}

?>
</div>
<div class='container'>
<a href="dashboard.php" class="back-to-home">Volver</a>
</div>
</body>
</html>