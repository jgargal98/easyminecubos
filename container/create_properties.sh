#!/bin/bash

# Nombre del archivo de propiedades
PROPERTIES_FILE="server.properties"

# Crear un archivo de propiedades si no existe
touch "$PROPERTIES_FILE"

# Leer el contenido actual del archivo
current_content=$(<"$PROPERTIES_FILE")

# Iterar sobre las variables de entorno definidas
for var in $(env); do
    # Obtener el nombre de la variable y su valor
    key="${var%%=*}"
    value="${var#*=}"
    # Verificar si la línea ya está presente en el archivo
    if grep -q "^$key=" <<< "$current_content"; then
        # Si la línea está presente, eliminarla del contenido
        current_content=$(grep -v "^$key=" <<< "$current_content")
    fi
    # Agregar la variable de entorno al contenido actualizado
    current_content+="$key=$value"$'\n'
done

# Escribir el contenido actualizado en el archivo
echo "$current_content" > "$PROPERTIES_FILE"
