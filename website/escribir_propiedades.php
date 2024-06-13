<?php
include "../inc/dbinfo.inc";

require 'vendor/autoload.php'; // Autoload de Composer

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\RSA;
use phpseclib3\Net\SCP;

$host = '34.202.66.61';
$port = 22; // Puerto por defecto para SSH
$username = 'ec2-user';
$private_key = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada
$passphrase = null; // Si tu clave tiene una frase de paso, añádela aquí

// Leer la clave privada
$key = new RSA();
$key->load(file_get_contents($private_key));

session_start();

$user = $_SESSION['usuario'];

$file ="/var/www/dockercomposes/" . $user . "-docker-compose.yml";
$directory = "/var/www/dockercomposes/";

// Ruta del archivo local y destino remoto
$localFile = $file;
$remoteFile = "/home/ec2-user/docker/" . $user . "-docker-compose.yml";

if (!is_dir($directory)) {
    if (!mkdir($directory, 0777, true)) {
        die('Error al crear directorio...');
    }
}

// Verificar si el archivo ya existe
if (!file_exists($file)) {
    // Intentar crear el archivo
    if (!touch($file)) {
        die('Error al crear archivo...');
    }
}

// Verificar si el archivo se creó correctamente
if (!file_exists($file)) {
    die('El archivo no se creó correctamente...');
}

fopen("$file", "w");

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

foreach ($_POST as $key => $value) {
    $value = preg_replace("/[^a-zA-Z0-9\s]/", "", $value);
    $docker_compose_content .= "            - $key = $value\n";
}

if (file_put_contents($file, $docker_compose_content) !== false) {
    echo "Archivo $file generado correctamente.<br>";

    try {
        // Intenta realizar la conexión SSH
        $ssh = new SSH2($host, $port);
    
        if (!$ssh->login($username, $key)) {
            throw new Exception('Login SSH fallido');
        }
    
        // Intenta transferir el archivo usando SCP
        $scp = new SCP($ssh);
        if ($scp->put($remoteFile, file_get_contents($localFile))) {
            echo "Archivo transferido correctamente.\n";
        } else {
            throw new Exception('Error al transferir el archivo mediante SCP');
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}