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

if (isset($_SESSION['usuario'])) {
    $user = $_SESSION['usuario'];
    $jsonFile = "../propiedades/$user-ops.json";

    if (file_exists($jsonFile)) {
        $jugadores = json_decode(file_get_contents($jsonFile), true);
    } else {
        $jugadores = [];
    }

    if (!empty($jugadores)) {
        echo '<form action="escribir_ops.php" class="signup-form" method="post">';
        echo '<div class="existing-players">';
        echo '<h3>Operadores existentes:</h3>';
        echo '<ul>';
        
        foreach ($jugadores as $index => $jugador) {
            echo '<li>';
            echo '<input type="checkbox" name="eliminar[]" value="' . $index . '">';
            echo htmlspecialchars($jugador['name']) . ' (Nivel ' . $jugador['level'] . ')';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '<button type="submit" name="eliminarJugadores" class="minecraft-button">Eliminar Operadores</button>';
        echo '</div>';
        echo '</form>';
    }

    echo '<form action="escribir_ops.php" method="post" class="signup-form">';
    echo '<h3>Añadir Nuevo Jugador</h3>';
    echo '<label for="name">Nombre:</label><br>';
    echo '<input type="text" id="name" name="name" required><br><br>';
    
    echo '<label for="level">Nivel:</label><br>';
    echo '<select id="level" name="level" class="minecraft-select">';
    echo '<option value="1">Nivel 1</option>';
    echo '<option value="2">Nivel 2</option>';
    echo '<option value="3">Nivel 3</option>';
    echo '<option value="4">Nivel 4</option>';
    echo '</select><br><br>';
    
    echo '<button type="submit" name="añadirJugador" class="minecraft-button">Añadir operador</button>';
    echo '</form>';

} else {
    echo '<p>No estás conectado.</p>';
}
?>

        </div>
        <a href="dashboard.php" class="back-to-home">Volver</a>
    </div>
</body>
</html>