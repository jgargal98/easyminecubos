<?php
print "<h1>Configura tu server</h1>";

$properties = fopen("default.properties", "r");


print "<form action='escribir_propiedades.php' method='post'>";
while(!feof($properties)) {

    //separamos el archivo en lineas
    $linea = explode("< >", fgets($properties));

    //Presentamos las opciones que a mi me interesan en un formulario
    $opcion = explode("=", $linea[0]);


    if (substr($opcion[0],0,1) != "#") {

    

    switch (trim($opcion[0])) {
        case "difficulty":
            print $opcion[0] . ": ";
            print 
            "<select name='" . $opcion[0] . "'>
                <option value='peaceful'>peaceful</option>
                <option value='easy'>easy</option>
                <option value='normal'>normal</option>
                <option value='hard'>hard</option>
                </select><br><br>";
        break;
        
        case "gamemode":
            print $opcion[0] . ": ";
            print 
            "<select name='" . $opcion[0] . "'>
                <option value='survival'>survival</option>
                <option value='creative'>creative</option>
                <option value='adventure'>adventure</option>
                <option value='spectator'>spectator</option>
                </select><br><br>";
        break;

        case "spawn-protection":
        case "max-players":
        case "motd":
        case "online-mode":
        case "simulation-distance":
        case "view-distance":
            print $opcion[0] . ": ";
            print "<input type='text' name='" . $opcion[0] . "' value='" . $opcion[1] . "'><br><br>";
        break;
        
        default:
            //Opciones booleanas true-false
            if (trim($opcion[1]) === "true" || trim($opcion[1]) === "false") {
                //entonces será un select de true o false, con opcion preseleccionada
                print $opcion[0] . ": ";
                print "<select name='" . $opcion[0] . "'>";
                
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
print "</form>";
fclose($properties);