<?php
session_start();

if (isset($_SESSION['usuario'])){

    $properties = fopen("default.properties", "r");

    print "<form action='escribir_propiedades.php' class='signup-form' method='post'>";

    while(!feof($properties)) {

        //separamos el archivo en líneas para modificarlo
        $linea = explode("< >", fgets($properties));

        //Presentamos las opciones que a mí me interesan en un formulario
        $opcion = explode("=", $linea[0]);

        if (substr($opcion[0],0,1) != "#") {
            switch (trim($opcion[0])) {
                case "difficulty":
                    print "<div class='form-row'>
                            <label for='" . $opcion[0] . "'>" . $opcion[0] . ":</label>
                            <select name='" . $opcion[0] . "' class='minecraft-select'>
                                <option value='peaceful'>peaceful</option>
                                <option value='easy'>easy</option>
                                <option value='normal'>normal</option>
                                <option value='hard'>hard</option>
                            </select>
                        </div>";
                break;
                
                case "gamemode":
                    print "<div class='form-row'>
                            <label for='" . $opcion[0] . "'>" . $opcion[0] . ":</label>
                            <select name='" . $opcion[0] . "' class='minecraft-select'>
                                <option value='survival'>survival</option>
                                <option value='creative'>creative</option>
                                <option value='adventure'>adventure</option>
                                <option value='spectator'>spectator</option>
                            </select>
                        </div>";
                break;

                case "spawn-protection":
                case "max-players":
                case "motd":
                case "simulation-distance":
                case "view-distance":
                    print "<div class='form-row'>
                            <label for='" . $opcion[0] . "'>" . $opcion[0] . ":</label>
                            <input type='text' name='" . $opcion[0] . "' value='" . $opcion[1] . "'>
                        </div>";
                break;
                
                default:
                    //Opciones booleanas true-false
                    if (trim($opcion[1]) === "true" || trim($opcion[1]) === "false") {
                        //entonces será un select de true o false, con opción preseleccionada
                        print "<div class='form-row'>
                                <label for='" . $opcion[0] . "'>" . $opcion[0] . ":</label>
                                <select name='" . $opcion[0] . "' class='minecraft-select'>";
                        
                        switch (trim($opcion[1])) {
                            case "true":
                                print
                                    "<option value='true' selected='selected'>true</option>
                                    <option value='false'>false</option>
                                    </select>
                                </div>";
                            break;
                            case "false":
                                print 
                                    "<option value='true'>true</option>
                                    <option value='false' selected='selected'>false</option>
                                    </select>
                                </div>";
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
  print '<p>No estás conectado.</p>';
}

?>
