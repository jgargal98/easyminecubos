#!/bin/bash

# Descargar Minecraft (suponiendo que necesitas descargarlo)
# Comando de descarga de Minecraft aquí...

# Iniciar el servidor
java -Xmx2G -Xms1G -jar server.jar nogui &

# Capturar el PID del proceso del servidor
SERVER_PID=$!

# Esperar un tiempo razonable para que el servidor inicie completamente
sleep 30

# Detener el servidor de Minecraft
kill "$SERVER_PID"

# Ruta del archivo server.properties
FILE="server.properties"

# Verificar si el archivo existe
if [ ! -f "$FILE" ]; then
    echo "El archivo $FILE no existe."
    exit 1
fi

# Leer el archivo server.properties línea por línea
while IFS='=' read -r key value; do
    # Ignorar líneas que comienzan con '#' (comentarios)
    if echo "$key" | grep -q "^#"; then
        continue
    fi
    
    # Buscar si existe una variable de entorno con el mismo nombre que la clave
    env_value=$(eval echo "\$$key")
    if [ -n "$env_value" ]; then
        # Si la variable de entorno existe, sobrescribir el valor en el archivo server.properties
        sed -i "s/^$key=.*/$key=$env_value/" "$FILE"
        echo "Se ha sobrescrito el valor de $key en $FILE con el valor de la variable de entorno."
    fi
done < "$FILE"

# Iniciar el servidor nuevamente
java -Xmx2G -Xms1G -jar server.jar nogui &