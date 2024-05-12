<?php
#include "../inc/dbinfo.inc";

// Configuración de la base de datos
$servername = "localhost"; // Cambia a la dirección del servidor si es necesario
$username = "root";
$password = "2asir";
$database = "easyminecubos";

// Crear conexión

#$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
 $conn = new mysqli($servername, $username, $password, $database);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializa las variables
$nombre = $pass = $confirmar_pass = "";
$error = "";

// Procesar el formulario cuando se envía
    // Validación de campos
    $nombre         = $_POST["username"];
    $pass           = $_POST["password"];
    $confirmar_pass = $_POST["confirm_password"];

    // Validar si el usuario ya existe
    $sql = "SELECT * FROM usuario WHERE user='$nombre'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "El nombre de usuario ya está en uso.";
    } elseif ($pass !== $confirmar_pass) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT); // Hash de la contraseña
        $sql_insert = "INSERT INTO usuario (user, pass) VALUES ('$nombre', '$hashed_password')";

        if ($conn->query($sql_insert) === TRUE) {
            // Registro exitoso, podrías redirigir a una página de inicio de sesión
            header("Location: login.php");
            exit();
        } else {
            $error = "Error al registrar el usuario: " . $conn->error;
  }
}

?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>EasyMinecubos - Signup</title>
    <link rel='stylesheet' href='styles.css'>
</head>
<body>
  <div class='container'>
    <img src='assets/easy-minecubos.png' alt='Título de la Página'>
      <form class='signup-form' action='signup.php' method='POST'>
        <h2>Crear una cuenta</h2>
        <input type='text' name='username' placeholder='Nombre de usuario' required>
        <input type='password' name='password' placeholder='Contraseña' required>
        <input type='password' name='confirm_password' placeholder='Confirmar contraseña' required><br>
        <span style="color: red;"><?php echo $error; ?></span><br> <!-- Muestra el mensaje de error -->
        <button type='submit' class='minecraft-button'>Registrarse</button>
      </form>
      <a href='index.html' class='back-to-home'>Volver a la página de inicio</a>
  </div>
</body>
</html>

<?php
// Cierra la conexión
$conn->close();
?>