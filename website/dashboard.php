<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Dashboard - Easy Minecubos</title>
    <link rel='stylesheet' href='properties.css'>
</head>
<body>
    <div class='container'>
        <img src='assets/easy-minecubos.png' alt='Título de la Página'>
    </div>
    <div class='container'>
        
<?php
session_start();

if (isset($_SESSION['usuario'])){

    if ("variable" == false) {
        print "Tu servidor está encendido, debes detenerlo para poder configurarlo.<br><br>";
    }else {
        print " <div class='options'>
                    <a href='propiedades.php' class='minecraft-button'>Propiedades</a>
                    <a href='bans.php' class='minecraft-button'>Administrar bans</a>
                    <a href='ops.php' class='minecraft-button'>Administrar ops</a>
                    <a href='whitelist.php' class='minecraft-button'>Administrar whitelist</a>
                </div>";
    }
}
else
{
  print '<p>No estas conectado.</p>';
}

?>
        </div>
        <div class='container'>
        <a href='logout.php' class='back-to-home'>Salir</a>
    </div>
</body>
</html>