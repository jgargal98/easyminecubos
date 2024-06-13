<?php
require "vendor/autoload.php";

use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SFTP;

$host = '34.202.66.61'; // Dirección IP o nombre de host SSH
$username = 'ec2-user'; // Nombre de usuario SSH
$private_key_file = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada

// create new SFTP instance
$sftp = new SFTP($host);

// create new RSA key
$privateKey = PublicKeyLoader::load(file_get_contents($private_key_file));

// login via sftp
if (!$sftp->login($username, $privateKey)) {
    throw new Exception('sFTP login failed');
}

// create a remote new file with defined content
if (!$sftp->put('santino.txt', 'santino chupa')) {
    throw new Exception('Error al crear archivo remoto');
}

echo "Archivo 'santino.txt' creado correctamente en el servidor SFTP.\n";
?>