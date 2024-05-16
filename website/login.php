<?php
include "../inc/dbinfo.inc";

session_start();

// Configuración de la base de datos
$servername = "localhost"; // Cambia a la dirección del servidor si es necesario
$username = "root";
$password = "2asir";
$database = "easyminecubos";

// Crear conexión
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
#$conn = new mysqli($servername, $username, $password, $database);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos.");
}

// Inicializar las variables
$nombre = $pass = "";
$error = "";
// Validación de campos
    $nombre = $_POST["username"];
    $pass = $_POST["password"];

    // Verificar las credenciales en la base de datos
    $sql = "SELECT * FROM usuario WHERE user='$nombre'";
    $result = $conn->query($sql);

    if ($result === false) {
        // Manejar errores de SQL de manera personalizada
        $error = "Hubo un error al realizar la consulta.";
    } elseif ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row["pass"])) {
            // Iniciar sesión y redirigir al usuario a una página de inicio
            $_SESSION["usuario"] = $nombre;
            header("Location: propiedades.php");
            exit();
        } else {
            $error = "Credenciales incorrectas.";
        }
    } else {
        $error = "El usuario no existe.";
    }
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

<?php
// Cierra la conexión
$conn->close();
?>
