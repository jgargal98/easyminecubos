<?php
$private_key = '/home/ec2-user/easyminecubos-servermc.pem'; // Ruta a tu clave privada

echo "Verificando la clave privada...<br>";

if (!file_exists($private_key)) {
    exit('Error: No se encuentra la clave privada en ' . $private_key . '<br>');
}

$key_content = file_get_contents($private_key);
if ($key_content === false) {
    exit('Error al leer la clave privada.<br>');
} else {
    echo "Clave privada leída correctamente:<br><pre>" . htmlspecialchars($key_content) . "</pre><br>";
}
?>
