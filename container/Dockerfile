FROM ubuntu:latest

ENV DEBIAN_FRONTEND=noninteractive

EXPOSE 25565

WORKDIR /minecraft

RUN apt-get update && apt-get install -y \
    openjdk-17-jdk \
    wget \
    && rm -rf /var/lib/apt/lists/*

COPY create_properties.sh /minecraft
COPY env.txt /minecraft
    
RUN wget --no-cache -O server.jar https://piston-data.mojang.com/v1/objects/4fb536bfd4a83d61cdbaf684b8d311e66e7d4c49/server.jar

RUN echo "eula=true" > eula.txt

RUN chmod +x create_properties.sh 

RUN bash create_properties.sh