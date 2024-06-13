<?php
require 'vendor/autoload.php'; // Autoload de Composer

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\RSA;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '34.202.66.61'; // Cambia por tu dirección IP o nombre de host SSH
$port = 22; // Puerto por defecto para SSH
$username = 'ec2-user'; // Cambia por tu nombre de usuario SSH
$private_key = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada
$passphrase = null; // Si tu clave tiene una frase de paso, añádela aquí

try {
    // Crear una instancia de SSH2
    $ssh = new SSH2($host, $port);

    // Leer la clave privada y cargarla en RSA
    $key = new RSA();
    $key->loadKey(file_get_contents($private_key));

    // Intentar autenticarse con la clave privada
    if ($ssh->login($username, $key)) {
        echo "Login SSH exitoso.";
    } else {
        echo "Login SSH fallido.";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}