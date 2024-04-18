<?php
session_start();

if (isset($_POST['user']) && isset($_POST['pass']))
{
  $user = $_POST['user'];
  $pass = $_POST['pass'];

  $conexion = mysqli_connect("localhost", "root", "2asir");
  mysqli_select_db($conexion, "usuarios");
  
  $buscarhash = mysqli_query($conexion, "SELECT pass FROM usuario WHERE user='$user'");

while ($registro=mysqli_fetch_row($buscarhash)){ 
    $hash = $registro[0]; 

};

  if (password_verify($pass, $hash)) {
      $_SESSION['valid_user'] = $user;
    }
  }
?>

<html>
<body>
<h1>Login</h1>

<?php
  if (isset($_SESSION['valid_user']))
  {
    print 'Acceso concedido: '.$_SESSION['valid_user'].' <br>';
    print ' <a href="propiedades.php">Acceder</a>
            <br>
            <a href="logout.php">Salir</a><br>';
  }
  else
  {
    if (isset($user))
    {
      // Se ha intentado la conexion sin exito
      print 'Credenciales incorrectas<br>';
    }
    else 
    {
      // Aun no se ha intentado conectar o se ha desconectado ya
      print 'No estas conectado.<br>';
    }

    // Proporciona un formulario para conectarse
    print '<form method="post" action="login.php">
          <table>
            <tr>
              <td>Usuario:</td>
              <td><input type="text" name="user" required></td>
            </tr>
            <tr>
              <td>Contraseña:</td>
              <td><input type="password" name="pass" required></td>
            </tr>
            <tr>
              <td colspan="2" align="center"><input type="submit" value="Conexion"></td>
            </tr>
          </table>
        </form>';
  }
?>
</body>
</html>