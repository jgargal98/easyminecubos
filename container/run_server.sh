#!/bin/bash

# Verifica si se ha proporcionado el nombre del archivo a copiar como argumento
if [ $# -eq 0 ]; then
    echo "Usage: $0 <file_name>"
    exit 1
fi

# Nombre del archivo a utilizar
FILE_NAME=$1

# Nombre del contenedor basado en el argumento proporcionado
CONTAINER_NAME="minecraft_server_${FILE_NAME}"

# Construye la imagen Docker si no está construida
docker build -t minecraft-server -f docker/easy-minecubos .

# Verifica si ya existe un contenedor con el mismo nombre y lo elimina si existe
if [ $(docker ps -a -q -f name=${CONTAINER_NAME}) ]; then
    docker rm -f ${CONTAINER_NAME}
fi

# Ejecuta el contenedor
docker run -d --name ${CONTAINER_NAME} -e FILE_NAME=$FILE_NAME -v $(pwd)/properties:/host -p 25565:25565 minecraft-server

# Obtener la IP de la máquina
IP=$(hostname -I | awk '{print $1}')

# Especificar el puerto
PORT=25565

# Devolver la IP y el puerto en el formato ip:puerto
echo "${IP}:${PORT}"