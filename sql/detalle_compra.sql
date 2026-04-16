-- Crear base de datos
CREATE DATABASE IF NOT EXISTS db_paginaescolar;
USE db_paginaescolar;

-- Crear tabla detalle_compra
CREATE TABLE detalle_compra (
  id INT(11) NOT NULL AUTO_INCREMENT,
  id_compra INT(11) DEFAULT NULL,
  producto VARCHAR(100) DEFAULT NULL,
  cantidad INT(11) DEFAULT NULL,
  precio DECIMAL(10,2) DEFAULT NULL,
  subtotal DECIMAL(10,2) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;