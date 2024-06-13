#!/bin/bash

# Verifica si se ha proporcionado el nombre del archivo como argumento
if [ $# -eq 0 ]; then
    echo "Usage: $0 <file_name>"
    exit 1
fi

# Nombre del archivo a utilizar
FILE_NAME=$1

# Construye la imagen Docker si no está construida
docker build -t minecraft-server -f docker/easy-minecubos .

# Ejecuta el contenedor
docker run -d --name minecraft_server -e FILE_NAME=$FILE_NAME -v $(pwd)/properties:/host -p 25565:25565 minecraft-server