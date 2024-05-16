FROM ubuntu:latest

ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /minecraft

COPY create_properties.sh .

RUN apt-get update && apt-get install -y \
    openjdk-17-jdk \
    wget \
    && rm -rf /var/lib/apt/lists/*
    
RUN wget -O server.jar https://piston-data.mojang.com/v1/objects/4fb536bfd4a83d61cdbaf684b8d311e66e7d4c49/server.jar

RUN echo "eula=true" > eula.txt

RUN pwd > t.txt

RUN chmod +x create_properties.sh 

RUN sh create_properties.sh

EXPOSE 25565

ENTRYPOINT [ "java" ] 

CMD ["-Xmx1024M", "-Xms1024M", "-jar", "server.jar", "nogui" ]