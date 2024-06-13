<?php
require "vendor/autoload.php";

use phpseclib\Crypt\RSA;
use phpseclib\Net\SFTP;

$host = '34.202.66.61'; // Dirección IP o nombre de host SSH
$username = 'ec2-user'; // Nombre de usuario SSH
$private_key_file = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada

$sftp = new SFTP($host);

// create new RSA key
$privateKey = new RSA();

// load the private key
$privateKey->loadKey(file_get_contents($private_key_file));

// login via sftp
if (!$sftp->login($username, $privateKey)) {
    throw new Exception('sFTP login failed');
}

// now you can list what's in here
$filesAndFolders = $sftp->nlist();

// create a remote new file with defined content
$sftp->put('santino.txt', 'santino chupa coñitos');