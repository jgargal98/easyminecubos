<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Bans - Easy Minecubos</title>
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
    $jsonFile = "../propiedades/$user-banned-players.json";

    // Cargar el archivo JSON de jugadores baneados si existe
    $jugadoresBaneados = [];

    if (file_exists($jsonFile)) {
        $jsonContent = file_get_contents($jsonFile);
        $jugadoresBaneados = json_decode($jsonContent, true);
    }

    if (!empty($jugadoresBaneados)) {
        echo '<form action="escribir_baneados.php" method="post" class="signup-form">';
        echo '<div class="existing-players">';
        echo '<h3>Jugadores Vetados:</h3>';
        echo '<ul>';
        
        foreach ($jugadoresBaneados as $jugador) {
            echo '<li>';
            echo '<input type="checkbox" name="eliminar[]" value="' . htmlspecialchars($jugador['name']) . '">';
            echo htmlspecialchars($jugador['name']) . '<br> - Expira: ' . htmlspecialchars($jugador['expires']) . '<br> - Razón: ' . htmlspecialchars($jugador['reason']);
            echo '</li>';
        }
        
        echo '</ul>';
        echo '<button type="submit" name="eliminarJugadores" class="minecraft-button">Perdonar</button>';
        echo '</div>';
        echo '</form>';
    }
?>

        <!-- Formulario para añadir nuevo jugador baneado -->
        <form action="escribir_bans.php" method="post" class="signup-form">
            <h3>Vetar jugador</h3>
            <label for="name">Nombre:</label><br>
            <input type="text" id="name" name="name" required><br><br>            
            <label for="reason">Razón:</label><br>
            <textarea id="reason" name="reason" rows="4" maxlength="200" required></textarea><br><br>
            
            <button type="submit" name="añadirJugador" class="minecraft-button">Vetar jugador</button>
        </form>

<?php
} else {
    print '<p>No estás conectado.</p>';
}
?>
        </div>
        <a href="dashboard.php" class="back-to-home">volver</a>
    </div>
</body>
</html>
