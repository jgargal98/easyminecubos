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
if (!$key->load(file_get_contents($private_key))) {
    exit('Error al cargar la clave privada.');
}

session_start();

$user = $_SESSION['usuario'];

$file = "/var/www/dockercomposes/" . $user . "-docker-compose.yml";

// Ruta del archivo local y destino remoto
$localFile = $file;
$remoteFile = "/home/ec2-user/docker/" . $user . "-docker-compose.yml";

if (!file_exists($file)) {
    touch($file);
}

$fp = fopen($file, "w");
if (!$fp) {
    exit("Error al abrir el archivo para escritura.");
}

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
    $docker_compose_content .= "            - $key=$value\n";
}

if (fwrite($fp, $docker_compose_content) === false) {
    exit("Error al escribir en el archivo.");
}

fclose($fp);
echo "Archivo $file generado correctamente.<br>";

// Crear una instancia de SSH2
$ssh = new SSH2($host, $port);

if (!$ssh->login($username, $key)) {
    exit('Fallo al iniciar sesión en SSH.');
}

echo "Conexión SSH establecida.<br>";

// Crear una instancia de SCP
$scp = new SCP($ssh);
// Transferir el archivo
if ($scp->put($remoteFile, file_get_contents($localFile))) {
    echo "Archivo transferido correctamente.<br>";
} else {
    echo "Error al transferir el archivo.<br>";
}

?>
