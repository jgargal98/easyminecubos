<?php
include "../inc/dbinfo.inc";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$user = $_SESSION['usuario'];


$file ="/var/www/html/dockercomposes/" . $user . "-docker-compose.yml";

fopen("$file", "w");

$docker_compose_content = "
version: '3.8'
services:
    minecraft_server:
        build:
            context: .
            dockerfile: Dockerfile
        ports:
            - '25565:25565'
        environment:
";

foreach ($_POST as $key => $value) {
    $docker_compose_content .= "            - $key = $value\n";
}

if (file_put_contents($file, $docker_compose_content) !== false) {
    echo "Archivo $file generado correctamente.";
} else {
    // Si hay un error, captura el mensaje de error
    $error = error_get_last();
    echo "Error al generar el archivo $file: " . $error['message'];
}
/*

$pass = "easyminecubos-servermc.pem";
$destiny = "ec2-user@34.202.66.61:/docker/$user-compose";

$comando_scp = "scp -i $pass $file $destiny";
exec($comando_scp);

$dockercompose = "docker-compose -f $file up";

$comando_ssh = "ssh -i $pass $destiny \"$dockercompose\"";
exec($comando_ssh);
?>
*/