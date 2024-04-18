<?php

$pass = "pepe123";

$options = [
    'cost' => 12,
  ];

  $hash = password_hash($pass, PASSWORD_BCRYPT, $options);

  echo $hash;

  if (password_verify($pass, $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}