-- Crear base de datos
CREATE DATABASE IF NOT EXISTS db_paginaescolar;
USE db_paginaescolar;

-- Crear tabla compras
CREATE TABLE compras (
  id INT(11) NOT NULL AUTO_INCREMENT,
  total DECIMAL(10,2) DEFAULT NULL,
  metodo_pago VARCHAR(50) DEFAULT NULL,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;