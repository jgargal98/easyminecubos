conexión con la instancia EC2 (servidor web:)
ssh -i easyminecubos-website.pem ec2-user@ec2-54-91-126-186.compute-1.amazonaws.com
sudo dnf install -y httpd php php-mysqli
sudo systemctl start httpd

Conectamos a: http://ec2-54-91-126-186.compute-1.amazonaws.com/ (mi dns publico)

añadimos el usuario de la instancia al grupo apache
sudo usermod -a -G apache ec2-user

Instalamos mysql client para poder modificar la bbdd
sudo dnf install mariadb105

conectarse a la bbdd desde el cliente en la instancia EC2: mysql -h easyminecubos-db.c9grvogynohy.us-east-1.rds.amazonaws.com -P 3306 -u johnbarbacoa -p

Hacer que la máquina actualice la página web cada vez que se arranca:
Primero se hace el script, luego se configura en el user-data de la instancia de aws