<?php
include "../inc/dbinfo.inc";

if (!extension_loaded('ssh2')) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        dl('php_ssh2.dll'); // Para sistemas Windows
    } else {
        dl('ssh2.so'); // Para sistemas Unix/Linux
    }
}

$host = '34.202.66.61';
$port = 22; // Puerto por defecto para SSH
$username = 'ec2-user';
$private_key = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada
$passphrase = null; // Si tu clave tiene una frase de paso, añádela aquí


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    // Establecer conexión
    $connection = ssh2_connect($host, $port);
    if (!$connection) {
        die('No se pudo conectar al servidor.');
    }

    // Autenticación con clave pública y privada
    if (!ssh2_auth_pubkey_file($connection, $username, $public_key, $private_key, $passphrase)) {
        die('No se pudo autenticar con la clave pública/privada.');
    }

    // Inicializar sesión SCP y transferir el archivo
    $scp = ssh2_scp_send($connection, $local_file, $remote_file, 0644);
    if (!$scp) {
        die('No se pudo transferir el archivo.');
    }

    echo 'Archivo transferido con éxito.';

}else {
    // Si hay un error, captura el mensaje de error
    $error = error_get_last();
    echo "Error al generar el archivo $file: " . $error['message'];
}