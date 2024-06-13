<?php
$private_key = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada

echo "Verificando la clave privada...<br>";

if (!file_exists($private_key)) {
    exit('Error: No se encuentra la clave privada en ' . $private_key . '<br>');
}

echo "Ruta de la clave privada: $private_key<br>";

$key_content = file_get_contents($private_key);
if ($key_content === false) {
    $error = error_get_last();
    echo "Error al leer la clave privada: " . $error['message'] . "<br>";
    exit();
} else {
    echo "Contenido de la clave privada:<br>";
    echo "<pre>" . htmlspecialchars($key_content) . "</pre><br>";
}