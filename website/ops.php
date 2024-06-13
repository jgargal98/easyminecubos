<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Propiedades - Easy Minecubos</title>
    <link rel='stylesheet' href='properties.css'>
</head>
<body>
    <div class='container'>
        <img src='assets/easy-minecubos.png' alt='Título de la Página'>
    </div>
    <div class='container'>
        <div class='formulario'>

<?php
session_start();

if (isset($_SESSION['usuario'])){
    $user = $_SESSION['usuario'];
    $jsonFile = "../propiedades/$user-ops.json";

    // Verificar si existe el archivo JSON y si tiene datos
        $jugadores = array();
        if (file_exists($jsonFile)) {
            $jsonData = file_get_contents($jsonFile);
            $jugadores = json_decode($jsonData, true);
    
            if ($jugadores === null || json_last_error() !== JSON_ERROR_NONE) {
                die("Error al leer el archivo JSON.");
            }
        }
    
        // Inicializar el array de jugadores si está vacío o no existe
        if (!isset($jugadores['jugadores']) || !is_array($jugadores['jugadores'])) {
            $jugadores['jugadores'] = array();
        }
    
        // Mostrar el formulario para añadir un nuevo jugador
        echo '<form action="procesar_formulario.php" method="post">';
        echo '<h3>Añadir Jugador</h3>';
        echo '<label for="name">Nombre:</label>';
        echo '<input type="text" id="name" name="name" required><br><br>';
        
        echo '<label for="level">Nivel:</label>';
        echo '<select id="level" name="level" required>';
        echo '<option value="1">1</option>';
        echo '<option value="2">2</option>';
        echo '<option value="3">3</option>';
        echo '<option value="4">4</option>';
        echo '</select><br><br>';
        
        echo '<input type="submit" value="Guardar">';
        echo '</form>';
        
        // Mostrar los jugadores existentes si hay alguno
        if (!empty($jugadores['jugadores'])) {
            echo '<h3>Jugadores existentes:</h3>';
            echo '<form action="procesar_formulario.php" method="post">';
            echo '<ul>';
            foreach ($jugadores['jugadores'] as $jugador) {
                echo '<li>';
                echo '<input type="checkbox" name="eliminar[]" value="' . htmlspecialchars($jugador['name']) . '"> Eliminar ';
                echo $jugador['name'] . ' - Nivel: ' . $jugador['level'];
                echo '</li>';
            }
            echo '</ul>';
            echo '<input type="submit" value="Eliminar Jugadores Seleccionados">';
            echo '</form>';
        }
}
else
{
  print '<p>No estas conectado.</p>';
}

?>
        </div>
        <a href='logout.php' class='back-to-home'>Salir</a>
    </div>
</body>
</html>