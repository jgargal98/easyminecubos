<?php
require 'vendor/autoload.php'; // Autoload de Composer

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;

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

    // Cargar la clave privada usando PublicKeyLoader
    $private_key = file_get_contents($private_key_file);
    $rsa = PublicKeyLoader::load($private_key);

    // Crear una instancia de SSH2
    $ssh = new SSH2($host, $port);

    // Intentar autenticarse con la clave cargada
    if (!$ssh->login($username, $rsa)) {
        throw new Exception('Login SSH fallido');
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

    // Crear una instancia de SCP y transferir el archivo Docker Compose al servidor remoto
    $scp = new \phpseclib3\Net\SCP($ssh);
    if ($scp->put($remoteFile, $localFile, \phpseclib3\Net\SCP::SOURCE_LOCAL_FILE)) {
        echo "Archivo transferido correctamente.\n";
    } else {
        throw new Exception('Error al transferir el archivo mediante SCP');
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
