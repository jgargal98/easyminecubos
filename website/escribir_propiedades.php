<?php
require 'vendor/autoload.php'; // Autoload de Composer

use phpseclib3\Net\SFTP;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '34.202.66.61'; // Dirección IP o nombre de host SSH
$port = 22; // Puerto por defecto para SSH
$username = 'ec2-user'; // Nombre de usuario SSH
$private_key_file = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada
$passphrase = null; // Frase de contraseña (si es necesaria)

session_start();

$user = $_SESSION['usuario'];

$file = "/var/www/dockercomposes/" . $user . "-docker-compose.yml";
$directory = "/var/www/dockercomposes/";

// Ruta del archivo local y destino remoto
$localFile = $file;
$remoteFile = "/home/ec2-user/docker/" . $user . "-docker-compose.yml";

try {
    // Verificar si el directorio existe o crearlo
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0777, true)) {
            throw new Exception('Error al crear directorio...');
        }
    }

    // Crear una instancia de SFTP
    $sftp = new SFTP($host, $port);

    // Leer la clave privada
    $key = file_get_contents($private_key_file);

    // Cargar la clave privada si tiene passphrase
    // $sftp->setPrivateKey($key, $passphrase);

    // Intentar autenticarse con la clave privada
    if (!$sftp->login($username, $key)) {
        throw new Exception('Login SFTP fallido');
    }

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

    // Transferir el archivo Docker Compose al servidor remoto
    if ($sftp->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
        echo "Archivo transferido correctamente.\n";
    } else {
        throw new Exception('Error al transferir el archivo mediante SFTP');
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}