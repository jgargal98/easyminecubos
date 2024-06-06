<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Propiedades - Easy Minecubos</title>
    <link rel='stylesheet' href='properties.css'>
</head>
<body>
    <div class='container'>
        <img src='assets/easy-minecubos.png' alt='Título de la Página'>
    </div>
    <div class='container'>
        <div class='formulario'>

<?php
session_start();

if (isset($_SESSION['usuario'])){

    $properties = fopen("default.properties", "r");

    print "<form action='escribir_propiedades.php' class='signup-form' method='post'>";

    while(!feof($properties)) {

        //separamos el archivo en lineas para modificarlo
        $linea = explode("< >", fgets($properties));

        //Presentamos las opciones que a mi me interesan en un formulario
        $opcion = explode("=", $linea[0]);

        if (substr($opcion[0],0,1) != "#") {
            switch (trim($opcion[0])) {
                case "difficulty":
                    print $opcion[0] . ": 
                    <select name='" . $opcion[0] . "' class='minecraft-select'>
                        <option value='peaceful'>peaceful</option>
                        <option value='easy'>easy</option>
                        <option value='normal'>normal</option>
                        <option value='hard'>hard</option>
                        </select><br><br>";
                break;
                
                case "gamemode":
                    print $opcion[0] . ": 
                    <select name='" . $opcion[0] . "' class='minecraft-select'>
                        <option value='survival'>survival</option>
                        <option value='creative'>creative</option>
                        <option value='adventure'>adventure</option>
                        <option value='spectator'>spectator</option>
                        </select><br><br>";
                break;

                case "spawn-protection":
                case "max-players":
                case "motd":
                case "simulation-distance":
                case "view-distance":
                    print $opcion[0] . ": 
                    <input type='text' name='" . $opcion[0] . "' value='" . $opcion[1] . "'><br><br>";
                break;
                
                default:
                    //Opciones booleanas true-false
                    if (trim($opcion[1]) === "true" || trim($opcion[1]) === "false") {
                        //entonces será un select de true o false, con opcion preseleccionada
                        print $opcion[0] . ": 
                        <select name='" . $opcion[0] . "' class='minecraft-select'><br>";
                        
                        switch (trim($opcion[1])) {
                            case "true":
                                print
                                    "<option value='true' selected='selected'>true</option>
                                    <option value='false'>false</option>
                                    </select><br><br>";
                            break;
                            case "false":
                                print 
                                    "<option value='true'>true</option>
                                    <option value='false' selected='selected'>false</option>
                                    </select><br><br>";
                            break;
                        }
                    }
                break;
                }
        }
    }
    print "<button type='submit' class='minecraft-button'>Enviar</button>
            </form>";


    fclose($properties);

}
else
{
  print '<p>No estas conectado.</p>';
}

?>

        </div>
        <a href='logout.php' class='back-to-home'>Salir</a>
    </div>
</body>
</html>