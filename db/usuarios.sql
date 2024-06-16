SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS `easyminecubos`;

USE `easyminecubos`;

-- tabla `usuario`
CREATE TABLE IF NOT EXISTS `usuario` (
  `user` varchar(20) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- tabla `servidor`
CREATE TABLE IF NOT EXISTS `servidor` (
  `id_servidor` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_propietario` varchar(20) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `ruta_backup` varchar(255) DEFAULT NULL,
  `fecha_ultimo_backup` datetime DEFAULT NULL,
  PRIMARY KEY (`id_servidor`),
  CONSTRAINT `fk_servidor_usuario` FOREIGN KEY (`usuario_propietario`) REFERENCES `usuario` (`user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;