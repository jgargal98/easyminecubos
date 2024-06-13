<?php
include "../inc/dbinfo.inc";
require "vendor/autoload.php";
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;

session_start();

//CREACION DEL DOCKER COMPOSE

$user = $_SESSION['usuario'];
$directory = "/var/www/propiedades/";
$file = $directory . $user . ".properties";

// Contenido de Docker Compose
$container_name = $user . "-server";

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
$RemoteFile = "/home/ec2-user/docker/" . $user . ".properties";

if (!$sftp->put($RemoteFile, $LocalFile, SFTP::SOURCE_LOCAL_FILE)) {
    throw new Exception('Error al copiar archivo al servidor remoto');
}

echo "Archivo '$LocalFile' copiado correctamente a '$RemoteFile' en el servidor remoto.<br><br><br>";

// Crear nueva instancia de ssh
$ssh = new SSH2($host);

// login via ssh
if (!$ssh->login($username, $privateKey)) {
    throw new Exception('SSH login failed');
}

// execute echo command to test SSH command execution
$command = "./hello_world.sh";
$output = $ssh->exec($command);

if ($output === false) {
    throw new Exception('Error al ejecutar comando SSH');
}

echo "Respuesta del comando SSH:\n";
echo $output . "\n";