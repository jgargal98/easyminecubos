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
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $jugadores = json_decode($jsonData, true);

        if ($jugadores !== null && json_last_error() === JSON_ERROR_NONE && isset($jugadores['jugadores']) && is_array($jugadores['jugadores'])) {
            echo '<h3>Jugadores existentes:</h3>';
            echo '<form action="procesar_formulario.php" method="post">';
            echo '<ul>';
            foreach ($jugadores['jugadores'] as $jugador) {
                echo '<li>';
                echo '<input type="checkbox" name="eliminar[]" value="' . htmlspecialchars($jugador['nombre']) . '"> Eliminar ';
                echo $jugador['nombre'] . ' - Edad: ' . $jugador['edad'] . ' - Nivel: ' . $jugador['nivel'];
                echo '</li>';
            }
            echo '</ul>';
            
            echo '<h3>Añadir Jugador</h3>';
            echo '<label for="nombre">Nombre:</label>';
            echo '<input type="text" id="nombre" name="nombre" required><br><br>';
            
            echo '<label for="edad">Edad:</label>';
            echo '<input type="number" id="edad" name="edad" required><br><br>';
            
            echo '<label for="nivel">Nivel:</label>';
            echo '<input type="number" id="nivel" name="nivel" required><br><br>';
            
            echo '<input type="submit" value="Guardar">';
            echo '</form>';
        }
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