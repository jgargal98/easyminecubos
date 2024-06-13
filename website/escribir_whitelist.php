<?php
session_start();

// Verificar si se han enviado datos desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ruta al archivo JSON de la lista blanca
    $user = $_SESSION['usuario'];
    $jsonFile = "../propiedades/{$user}-whitelist.json";

    // Leer y decodificar el JSON existente si existe
    $usuariosWhitelist = [];
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $usuariosWhitelist = json_decode($jsonData, true);

        if ($usuariosWhitelist === null || json_last_error() !== JSON_ERROR_NONE) {
            die("Error al leer el archivo JSON de la lista blanca.");
        }
    }

    // Inicializar el array si está vacío o no existe
    if (!is_array($usuariosWhitelist)) {
        $usuariosWhitelist = [];
    }

    // Procesar la adición de un nuevo usuario a la lista blanca
    if (isset($_POST["name"])) {
        $nuevoUsuario = [
            "name" => $_POST["name"]
        ];

        // Añadir el nuevo usuario a la lista blanca
        $usuariosWhitelist[] = $nuevoUsuario;

        // Convertir nuevamente el array a JSON
        $nuevoJsonData = json_encode($usuariosWhitelist, JSON_PRETTY_PRINT);

        // Guardar el JSON actualizado de vuelta en el archivo
        if (file_put_contents($jsonFile, $nuevoJsonData)) {
            echo "El usuario ha sido añadido a la lista blanca correctamente.";
        } else {
            echo "Error al guardar los datos del usuario en la lista blanca.";
        }
    }

    // Procesar la eliminación de jugadores de la lista blanca
    if (isset($_POST['eliminar']) && is_array($_POST['eliminar'])) {
        $usuariosAEliminar = $_POST['eliminar'];

        // Filtrar los usuarios a eliminar del array de la lista blanca
        $usuariosWhitelist = array_filter($usuariosWhitelist, function ($usuario) use ($usuariosAEliminar) {
            return !in_array($usuario['name'], $usuariosAEliminar);
        });

        // Convertir nuevamente el array a JSON
        $nuevoJsonData = json_encode($usuariosWhitelist, JSON_PRETTY_PRINT);

        // Guardar el JSON actualizado de vuelta en el archivo
        if (file_put_contents($jsonFile, $nuevoJsonData)) {
            echo "Los usuarios han sido eliminados de la lista blanca correctamente.";
        } else {
            echo "Error al guardar los datos de la lista blanca después de eliminar usuarios.";
        }
    }
}