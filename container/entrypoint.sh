#!/bin/bash

# Verificar si la variable de entorno FILE_NAME está definida
if [ -z "$FILE_NAME" ]; then
    echo "Error: FILE_NAME no está definido"
    exit 1
fi

# Verificar si el archivo existe
if [ ! -f "/host/${FILE_NAME}.properties" ]; then
    echo "Error: El archivo /host/${FILE_NAME}.properties no existe"
    exit 1
fi

# Copiar el archivo con el nuevo nombre dentro del contenedor
cp "/host/${FILE_NAME}.properties" "/minecraft/server.properties"

# Iniciar el servidor de Minecraft
exec java -Xmx2G -Xms1G -jar /minecraft/server.jar nogui &