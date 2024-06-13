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

// Ruta del archivo local y destino remoto
$local_file = $file;
$remote_file = "/home/ec2-user/docker/" . $user . "-docker-compose.yml";

if (!file_exists("$file")) {
    touch("$file");
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

    // Crear una instancia de SSH2
    $ssh = new SSH2($host, $port);

    if (!$ssh->login($username, $key)) {
        exit('Login Failed');
    }

    // Crear una instancia de SCP
    $scp = new SCP($ssh);

    // Ruta del archivo local y destino en el servidor remoto
    $localFile = 'path/to/local/file.txt';
    $remoteFile = 'path/to/remote/file.txt';

    // Transferir el archivo
    if ($scp->put($remoteFile, file_get_contents($localFile))) {
        echo "Archivo transferido correctamente.\n";
    } else {
        echo "Error al transferir el archivo.\n";
    }

}else {
    // Si hay un error, captura el mensaje de error
    $error = error_get_last();
    echo "Error al generar el archivo $file: " . $error['message'];
}