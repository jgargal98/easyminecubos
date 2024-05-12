#!/bin/bash

# Nombre del archivo de propiedades
PROPERTIES_FILE="server.properties"

# Crear un archivo de propiedades si no existe
touch $PROPERTIES_FILE

# Iterar sobre las variables de entorno definidas en el archivo docker-compose
for var in $(env | grep -E '^[A-Za-z0-9_]+=' | grep -Eo '^[A-Za-z0-9_]+'); do
    # Obtener el valor de la variable de entorno
    value=$(printenv $var)
    # Reemplazar el valor de la variable en el archivo de propiedades si ya existe
    if grep -q "^$var=" $PROPERTIES_FILE; then
        sed -i "s/^$var=.*/$var = $value/g" $PROPERTIES_FILE
    else
        # Si la variable no existe en el archivo, la añade
        echo "$var = $value" >> $PROPERTIES_FILE
    fi
done