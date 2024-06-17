<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Dashboard - Easy Minecubos</title>
    <link rel='stylesheet' href='properties.css'>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .center-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="center-wrapper">
        <div class='container'>
            <img src='assets/easy-minecubos.png' alt='Título de la Página'>
        </div>
        <div class='container'>
            <?php
            session_start();
            if (isset($_SESSION['usuario'])){
                if ("variable" == false) {
                    print "Tu servidor está encendido, debes detenerlo para poder configurarlo.<br><br>";
                } else {
                    print "<div class='options'>
                            <a href='propiedades.php' class='minecraft-button'>Propiedades</a>
                            <a href='bans.php' class='minecraft-button'>vetados</a>
                            <a href='ops.php' class='minecraft-button'>Operadores</a>
                            <a href='whitelist.php' class='minecraft-button'>whitelist</a>
                           </div>";
                }
            } else {
                print '<p>No estas conectado.</p>';
            }
            ?>
        </div>
        <div class='container'>
            <a href='logout.php' class='back-to-home'>Salir</a>
        </div>
    </div>
</body>
</html>
