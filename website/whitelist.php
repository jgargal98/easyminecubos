<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Blanca - Easy Minecubos</title>
    <link rel="stylesheet" href="properties.css">
</head>
<body>
    <div class="container">
        <img src="assets/easy-minecubos.png" alt="Título de la Página">
    </div>
    <div class="container">
        <div class="formulario">
            <?php
            session_start();

            if (isset($_SESSION['usuario'])) {
                $user = $_SESSION['usuario'];
                $jsonFile = "../propiedades/$user-whitelist.json";

                // Cargar el archivo JSON de la lista blanca si existe
                $jugadoresWhitelist = [];

                if (file_exists($jsonFile)) {
                    $jsonContent = file_get_contents($jsonFile);
                    $jugadoresWhitelist = json_decode($jsonContent, true);
                }

                if (!empty($jugadoresWhitelist)) {
                    echo '<form action="escribir_whitelist.php" method="post" class="signup-form">';
                    echo '<div class="existing-players">';
                    echo '<h3>Jugadores en Lista Blanca:</h3>';
                    echo '<ul>';

                    foreach ($jugadoresWhitelist as $jugador) {
                        echo '<li>';
                        echo '<input type="checkbox" name="eliminar[]" value="' . htmlspecialchars($jugador['name']) . '">';
                        echo htmlspecialchars($jugador['name']);
                        echo '</li>';
                    }

                    echo '</ul>';
                    echo '<button type="submit" name="eliminarJugadores" class="minecraft-button">Eliminar Jugadores</button>';
                    echo '</div>';
                    echo '</form>';
                }
            } else {
                print '<p>No estás conectado.</p>';
            }
            ?>
            <!-- Formulario para añadir nuevo jugador a la lista blanca -->
            <form action="escribir_whitelist.php" method="post" class="signup-form">
                <h3>Añadir Jugador a Lista Blanca</h3>
                <label for="name">Nombre:</label><br>
                <input type="text" id="name" name="name" required><br><br>
                <button type="submit" name="añadirJugador" class="minecraft-button">Añadir Jugador</button>
            </form>
        </div>
        <a href="dashboard.php" class="back-to-home">volver</a>
    </div>
</body>
</html>
