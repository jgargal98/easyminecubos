<?php
include "../inc/dbinfo.inc";
require "vendor/autoload.php";
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;

session_start();

//CREACION DEL DOCKER COMPOSE

$user = $_SESSION['usuario'];
$directory = "/var/www/dockercomposes/";
$file = $directory . $user . "-docker-compose.yml";

// Contenido de Docker Compose
$container_name = $user . "-server";
$docker_compose_content = "
version: '3.8'
services:
minecraft_server:
    build: .
    image: easy-minecubos
    container_name: $container_name
    ports:
        - '25565:25565'
    environment:
";

// Construir el contenido basado en POST
foreach ($_POST as $key => $value) {
    $value = preg_replace("/[^a-zA-Z0-9\s]/", "", $value);
    $docker_compose_content .= "            - $key = $value\n";
}

// Escribir contenido en el archivo Docker Compose
if (file_put_contents($file, $docker_compose_content) === false) {
    throw new Exception("Error al escribir en el archivo $file");
}

//echo "Archivo $file generado correctamente.<br>";




// Crear la sesión SFTP
$host = '34.202.66.61';
$username = 'ec2-user';
$private_key_file = '/home/ec2-user/easyminecubos-servermc.pem';

$sftp = new SFTP($host);

// Leer la clave privada
$privateKey = PublicKeyLoader::load(file_get_contents($private_key_file));

// login
if (!$sftp->login($username, $privateKey)) {
    throw new Exception('sFTP login failed');
}

// Envío del archivo
// Ruta del archivo local y destino remoto
$LocalFile = $file;
$RemoteFile = "/home/ec2-user/docker/" . $user . "-docker-compose.yml";

if (!$sftp->put($RemoteFile, $LocalFile, SFTP::SOURCE_LOCAL_FILE)) {
    throw new Exception('Error al copiar archivo al servidor remoto');
}

//echo "Archivo '$LocalFile' copiado correctamente a '$RemoteFile' en el servidor remoto.<br><br><br>";


//EJECUCIÓN DEL CONTENEDOR
// Crear sesión de SSH
$ssh = new SSH2($host);
if (!$ssh->login($username, $privateKey)) {
    throw new Exception('SSH login failed');
}

// Ejecutar el comando docker-compose
//$command = "docker-compose -f $remote_file up -d";

$command = "echo 'Hola, mundo!'";
$output = $ssh->exec($command);

if (!$ssh->exec($command)) {
    throw new Exception('Error al ejecutar comando SSH');
}
if ($output === false) {
    throw new Exception('Error al ejecutar comando SSH');
}

echo "Respuesta del comando SSH:<br>";
echo $output . "<br>";