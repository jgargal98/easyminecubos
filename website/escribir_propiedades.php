<?php
require 'vendor/autoload.php'; // Autoload de Composer

use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Exception\NoKeyLoadedException;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Example raw key data (replace with your actual key data)
$raw = file_get_contents('/path/to/your/key.pem');

try {
    // Load the key using PublicKeyLoader
    $key = PublicKeyLoader::load($raw);

    // Example usage: Display the loaded key
    echo "Key loaded successfully:\n";
    echo $key->toString() . "\n"; // Output the key as string (for debugging)

    // Further operations with the $key object can be performed here
    // For example:
    // - Use the $key for cryptographic operations
    // - Authenticate using SSH or perform other tasks requiring key handling
} catch (NoKeyLoadedException $e) {
    echo 'Error: Unable to load key. ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}