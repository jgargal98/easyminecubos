<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Dashboard - Easy Minecubos</title>
    <link rel='stylesheet' href='styles.css'>
</head>
<body>
    <div class='container'>
        <img src='assets/easy-minecubos.png' alt='Título de la Página'><br><br><br>
        
        <?php
        session_start();

        if (isset($_SESSION['usuario'])){
            if ("variable" == false) {
                echo "Tu servidor está encendido, debes detenerlo para poder configurarlo.<br><br>";
            } else {
                echo "
                        <a href='propiedades.php' class='minecraft-button'>Propiedades</a>
                        <a href='bans.php' class='minecraft-button'>vetados</a>
                        <a href='ops.php' class='minecraft-button'>Operadores</a>
                        <a href='whitelist.php' class='minecraft-button'>whitelist</a>
                      ";
            }
        } else {
            echo '<p>No estás conectado.</p>';
        }
        ?>
        
        <a href='logout.php' class='back-to-home'>Salir</a>
    </div>
</body>
</html>