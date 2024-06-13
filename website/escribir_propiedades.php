<?php
echo "Iniciando script...<br>";

// Incluir archivo de configuración de base de datos
if (file_exists('../inc/dbinfo.inc')) {
    include "../inc/dbinfo.inc";
    echo "Archivo dbinfo.inc incluido.<br>";
} else {
    exit("Error: No se encuentra el archivo dbinfo.inc.<br>");
}

// Autoload de Composer
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
    echo "Autoload de Composer incluido.<br>";
} else {
    exit("Error: No se encuentra el archivo autoload de Composer.<br>");
}

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\RSA;
use phpseclib3\Net\SCP;

// Datos de conexión
$host = '34.202.66.61';
$port = 22; // Puerto por defecto para SSH
$username = 'ec2-user';
$private_key = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada

echo "Preparando la clave privada...<br>";

// Leer la clave privada
$key = new RSA();
$key_content = file_get_contents($private_key);
if ($key_content === false) {
    exit('Error al leer la clave privada.<br>');
} else {
    echo "Clave privada leída correctamente.<br>";
}

if (!$key->load($key_content)) {
    exit('Error al cargar la clave privada.<br>');
}
echo "Clave privada cargada correctamente.<br>";

session_start();

if (!isset($_SESSION['usuario'])) {
    exit('No se ha establecido una sesión de usuario.<br>');
}

$user = $_SESSION['usuario'];
echo "Usuario de la sesión: $user<br>";

$file = "/var/www/dockercomposes/" . $user . "-docker-compose.yml";
echo "Ruta del archivo local: $file<br>";

// Ruta del archivo local y destino remoto
$localFile = $file;
$remoteFile = "/home/ec2-user/docker/" . $user . "-docker-compose.yml";
echo "Ruta del archivo remoto: $remoteFile<br>";

if (!file_exists($file)) {
    if (touch($file)) {
        echo "Archivo creado: $file<br>";
    } else {
        exit("Error al crear el archivo: $file<br>");
    }
} else {
    echo "Archivo ya existe: $file<br>";
}

$fp = fopen($file, "w");
if (!$fp) {
    exit("Error al abrir el archivo para escritura.<br>");
}
echo "Archivo abierto para escritura: $file<br>";

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
    fclose($fp);
    exit("Error al escribir en el archivo.<br>");
}
fclose($fp);
echo "Archivo escrito correctamente: $file<br>";

// Crear una instancia de SSH2
$ssh = new SSH2($host, $port);
if (!$ssh) {
    exit("No se pudo crear la instancia de SSH.<br>");
}

echo "Instancia de SSH creada.<br>";

if (!$ssh->login($username, $key)) {
    exit('Fallo al iniciar sesión en SSH.<br>');
}
echo "Conexión SSH establecida.<br>";

// Crear una instancia de SCP
$scp = new SCP($ssh);
if (!$scp) {
    exit("No se pudo crear la instancia de SCP.<br>");
}
echo "Instancia de SCP creada.<br>";

// Transferir el archivo
if ($scp->put($remoteFile, file_get_contents($localFile))) {
    echo "Archivo transferido correctamente.<br>";
} else {
    echo "Error al transferir el archivo.<br>";
}

?>
