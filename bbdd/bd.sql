-- Crear la base de datos
CREATE DATABASE myNetflixDB;
USE myNetflixDB;

-- Tabla de Películas
CREATE TABLE peliculas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  poster VARCHAR(255),
  description TEXT,
  likes INT
);

-- Insertar datos en la tabla de películas
INSERT INTO peliculas (title, poster, description, likes) VALUES
('Breaking Bad', './img/bb.webp', 'Serie de drama sobre un profesor de química que se convierte en fabricante de metanfetaminas.', 250),
('La Casa de Papel', './img/lcdp.webp', 'Un grupo de delincuentes lleva a cabo un asalto a la Real Casa de la Moneda de España.', 150),
('Fast & Furious 8', './img/ff8.webp', 'La octava entrega de la saga Fast & Furious, centrada en traiciones y carreras de autos.', 300),
('Stranger Things', './img/st.webp', 'Serie de ciencia ficción y terror que sigue a un grupo de niños enfrentándose a criaturas de otro mundo.', 400),
('Peaky Blinders', './img/pb.png', 'La historia de la familia Shelby y su banda criminal en el post-guerra de Inglaterra.', 180);

-- Tabla de Categorías
CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL
);

-- Insertar datos en la tabla de categorías
INSERT INTO categorias (name) VALUES
('Drama'),
('Acción'),
('Ciencia Ficción'),
('Crimen'),
('Terror');

-- Tabla de Relación entre Películas y Categorías
CREATE TABLE pelicula_categoria (
  pelicula_id INT,
  categoria_id INT,
  PRIMARY KEY (pelicula_id, categoria_id),
  FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

-- Relacionar películas con categorías
INSERT INTO pelicula_categoria (pelicula_id, categoria_id) VALUES
(1, 1),  -- Breaking Bad -> Drama
(1, 4),  -- Breaking Bad -> Crimen
(2, 4),  -- La Casa de Papel -> Crimen
(3, 2),  -- Fast & Furious 8 -> Acción
(4, 3),  -- Stranger Things -> Ciencia Ficción
(4, 5),  -- Stranger Things -> Terror
(5, 4);  -- Peaky Blinders -> Crimen

-- Tabla de Usuarios
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  email VARCHAR(100) UNIQUE NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos en la tabla de usuarios
INSERT INTO usuarios (nombre, email) VALUES
('Carlos García', 'carlos@correo.com'),
('Ana López', 'ana@correo.com'),
('Pedro Martínez', 'pedro@correo.com');

-- Tabla de Roles
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(50) UNIQUE NOT NULL
);

-- Insertar datos en la tabla de roles
INSERT INTO roles (role_name) VALUES
('Admin'),
('Suscriptor');

-- Tabla de Relación entre Usuarios y Roles
CREATE TABLE usuario_rol (
  usuario_id INT,
  rol_id INT,
  PRIMARY KEY (usuario_id, rol_id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Asignar roles a los usuarios
INSERT INTO usuario_rol (usuario_id, rol_id) VALUES
(1, 1),  -- Carlos -> Admin
(2, 2),  -- Ana -> Suscriptor
(3, 2);  -- Pedro -> Suscriptor

-- Tabla de Likes
CREATE TABLE likes (
  usuario_id INT,
  pelicula_id INT,
  PRIMARY KEY (usuario_id, pelicula_id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE
);

-- Insertar likes de los usuarios
INSERT INTO likes (usuario_id, pelicula_id) VALUES
(2, 1),  -- Ana le da like a Breaking Bad
(3, 2),  -- Pedro le da like a La Casa de Papel
(1, 4);  -- Carlos le da like a Stranger Things
