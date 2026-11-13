CREATE DATABASE IF NOT EXISTS bd_productos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_productos;

CREATE TABLE IF NOT EXISTS categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS productos (
  id INT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  categoria_id INT NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_producto_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE IF NOT EXISTS imagenes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT NOT NULL,
  url TEXT NOT NULL,
  CONSTRAINT fk_imagen_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);
