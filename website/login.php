<?php
include "../inc/dbinfo.inc";

session_start();

// Crear conexión
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Inicializar las variables
$nombre = $pass = "";
$error = "";

// Validación de campos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $nombre = $_POST["username"];
        $pass = $_POST["password"];

        // Consulta preparada para evitar inyecciones SQL
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE user=?");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            // Manejar errores de SQL de manera personalizada
            $error = "Hubo un error al realizar la consulta.";
        } elseif ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row["pass"])) {
                // Iniciar sesión y redirigir al usuario a una página de inicio
                $_SESSION["usuario"] = $nombre;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Credenciales incorrectas.";
            }
        } else {
            $error = "El usuario no existe.";
        }

        $stmt->close();
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}

// Cierra la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>EasyMinecubos - Login</title>
    <link rel='stylesheet' href='styles.css'>
</head>
<body>
    <div class='container'>
        <img src='assets/easy-minecubos.png' alt='Título de la Página'>
        <form class='signup-form' action='login.php' method='POST'>
            <h2>Iniciar Sesión</h2>
            <input type='text' name='username' placeholder='Usuario' required>
            <input type='password' name='password' placeholder='Contraseña' required><br>
            <span style="color: red;"><?php echo $error; ?></span><br> <!-- Muestra el mensaje de error -->
            <button type='submit' class='minecraft-button'>Iniciar Sesión</button>
        </form>
        <a href='index.html' class='back-to-home'>Volver a la página de inicio</a>
    </div>
</body>
</html>
