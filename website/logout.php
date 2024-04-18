<?php
  session_start();
if(isset($_SESSION['valid_user']))
{
  $old_user = $_SESSION['valid_user'];
  unset($_SESSION['valid_user']);
  session_destroy();
}
?>
<html>
<body>

<?php 
  if (!empty($old_user))
  {
    print '<h1>Se ha desconectado</h1><br>';
  }
  else
  {
    // Si no habia usuario conectado pero se accede de alguna manera en esta pagina
    print 'Error: No hay usuario registrado.<br>';
  }
?> 
<a href="index.html">Volver a la pagina principal</a>
</body>
</html>