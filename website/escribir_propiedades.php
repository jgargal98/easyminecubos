<?php 
session_start();

$user = $_SESSION['usuario'];

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

$file = $user . "-docker-compose.yml";
file_put_contents($file, $docker_compose_content);

echo "Archivo $file generado correctamente.";

$comando_scp = "scp $file usuario@otra_maquina:/ruta/destino";
exec($comando_scp);

$dockercompose = "docker-compose -f $file up";

$comando_ssh = "ssh $usuario@$otra_maquina \"$dockercompose\"";
exec($comando_ssh);
?>