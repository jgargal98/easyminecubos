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

        // Cargar el archivo JSON de jugadores si existe
        $jsonFile = 'jugadores.json';
        $jugadores = [];

        if (file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            $jugadores = json_decode($jsonContent, true);
        }

        if (!empty($jugadores)) {
            echo '<form action="procesar_formulario.php" method="post">';
            echo '<div class="existing-players">';
            echo '<h3>Jugadores existentes:</h3>';
            echo '<ul>';
            
            foreach ($jugadores as $index => $jugador) {
                echo '<li>';
                echo '<input type="checkbox" name="eliminar[]" value="' . $index . '">';
                echo htmlspecialchars($jugador['name']) . ' (Nivel ' . $jugador['level'] . ')';
                echo '</li>';
            }
            
            echo '</ul>';
            echo '<button type="submit" name="eliminarJugadores" class="minecraft-button">Eliminar Jugadores</button>';
            echo '</div>';
            echo '</form>';
        }
?>

        <!-- Formulario para añadir nuevo jugador -->
        <form action="procesar_formulario.php" method="post" class="signup-form">
            <h3>Añadir Nuevo Jugador</h3>
            <label for="name">Nombre:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="level">Nivel:</label><br>
            <select id="level" name="level" class="minecraft-select">
                <option value="1">Nivel 1</option>
                <option value="2">Nivel 2</option>
                <option value="3">Nivel 3</option>
                <option value="4">Nivel 4</option>
            </select><br><br>
            
            <button type="submit" name="añadirJugador" class="minecraft-button">Añadir Jugador</button>
        </form>
        
<?php
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