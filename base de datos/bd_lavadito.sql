-- Crear base de datos
CREATE DATABASE lavadito;
USE lavadito;

-- Tabla: clientes
CREATE TABLE clientes (
  cliente_id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(20),
  apellidos VARCHAR(25),
  telefono VARCHAR(15),
  email VARCHAR(50),
  direccion VARCHAR(100)
);

-- Tabla: pedidos
CREATE TABLE pedidos (
  pedido_id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT,
  fecha_pedido DATE,
  fecha_entrega DATE,
  estado VARCHAR(20),
  FOREIGN KEY (cliente_id) REFERENCES clientes(cliente_id)
);

-- Tabla: servicios
CREATE TABLE servicios (
  servicio_id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT,
  tipo_servicio VARCHAR(50),
  peso FLOAT,
  precio FLOAT,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(pedido_id)
);

-- Tabla: furgonetas
CREATE TABLE furgonetas (
  furgoneta_id INT AUTO_INCREMENT PRIMARY KEY,
  placa VARCHAR(15),
  modelo VARCHAR(30),
  capacidad FLOAT,
  estado VARCHAR(20)
);

-- Tabla: conductores
CREATE TABLE conductores (
  conductor_id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(20),
  apellido VARCHAR(20),
  telefono VARCHAR(15),
  licencia VARCHAR(20),
  estado VARCHAR(20)
);

-- Tabla: rutas
CREATE TABLE rutas (
  ruta_id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT,
  furgoneta_id INT,
  conductor_id INT,
  fecha_hora_salida DATETIME,
  fecha_hora_entrega DATETIME,
  estado VARCHAR(20),
  FOREIGN KEY (pedido_id) REFERENCES pedidos(pedido_id),
  FOREIGN KEY (furgoneta_id) REFERENCES furgonetas(furgoneta_id),
  FOREIGN KEY (conductor_id) REFERENCES conductores(conductor_id)
);

-- Tabla: pagos
CREATE TABLE pagos (
  pago_id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT,
  monto FLOAT,
  fecha_pago DATE,
  metodo VARCHAR(20),
  estado VARCHAR(20),
  FOREIGN KEY (pedido_id) REFERENCES pedidos(pedido_id)
);
-- Tabla: usuarios
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) UNIQUE NOT NULL,
  clave VARCHAR(255) NOT NULL
);
-- Tabla: usuarios
CREATE TABLE usuarios (
  usuario_id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  usuario VARCHAR(50) UNIQUE NOT NULL,
  clave VARCHAR(255) NOT NULL,
  FOREIGN KEY (cliente_id) REFERENCES clientes(cliente_id)
);
