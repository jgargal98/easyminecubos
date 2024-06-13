<?php
require 'vendor/autoload.php'; // Autoload de Composer

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Exception\NoKeyLoadedException;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '34.202.66.61'; // Cambia por tu dirección IP o nombre de host SSH
$port = 22; // Puerto por defecto para SSH
$username = 'ec2-user'; // Cambia por tu nombre de usuario SSH
$public_key_file = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave pública

try {
    // Cargar la clave pública usando PublicKeyLoader
    $public_key = file_get_contents($public_key_file);
    $key = PublicKeyLoader::load($public_key);

    // Crear una instancia de SSH2
    $ssh = new SSH2($host, $port);

    // Intentar autenticarse con la clave pública
    if ($ssh->login($username, $key)) {
        echo "Login SSH exitoso.";
    } else {
        echo "Login SSH fallido.";
    }
} catch (NoKeyLoadedException $e) {
    echo 'Error: No se pudo cargar la clave pública. ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
