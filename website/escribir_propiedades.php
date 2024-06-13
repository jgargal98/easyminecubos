<?php
include "../inc/dbinfo.inc";
require "vendor/autoload.php";
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SFTP;

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

echo "Archivo $file generado correctamente.<br>";




// Crear la sesión SFTP

$host = '34.202.66.61'; // Dirección IP o nombre de host SSH
$username = 'ec2-user'; // Nombre de usuario SSH
$private_key_file = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada

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

echo "Archivo '$LocalFile' copiado correctamente a '$RemoteFile' en el servidor remoto.\n";