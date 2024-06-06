#!/bin/bash

# Nombre del archivo de propiedades
PROPERTIES_FILE="server.properties"

# Crear un archivo de propiedades si no existe
touch "$PROPERTIES_FILE"

# Iterar sobre las variables de entorno definidas
for var in $(printenv); do
    # Obtener el nombre de la variable y su valor
    key="${var%%=*}"
    value="${var#*=}"
    # Verificar si la línea ya está presente en el archivo
    if grep -q "^$key=" "$PROPERTIES_FILE"; then
        # Si la línea está presente, eliminarla del archivo
        sed -i "/^$key=/d" "$PROPERTIES_FILE"
    fi
    # Agregar la variable de entorno al archivo
    echo "$key=$value" >> "$PROPERTIES_FILE"
done