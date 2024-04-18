<?php
session_start();


if (isset($_POST['user']) && isset($_POST['pass'])){

  $user = $_POST['user'];
  $pass = $_POST['pass'];


  $options = [
    'cost' => 12,
  ];

  $pass = password_hash($pass, PASSWORD_BCRYPT, $options);
  
  //Insertar el usuario

  $conexion = mysqli_connect("localhost", "root", "2asir");
    mysqli_select_db($conexion, "usuarios");

    $query = mysqli_query($conexion, "INSERT INTO `usuario` (`user`, `pass`) VALUES ('$user', '$pass')");
  
    $numerror   = mysqli_errno($conexion); 
    $textoerror = mysqli_error($conexion); 
    
    if ($numerror!=0){
      print "Este nombre de usuario ya existe <br>";
    } else {
      print "Usuario creado <br>
              <a href='login.php'>Iniciar sesion</a>";
    }
}
else {
    print '<form method="post" action="signup.php">
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
              <td colspan="2" align="center"><input type="submit" value="Crear cuenta"></td>
            </tr>
          </table>
        </form>';
}