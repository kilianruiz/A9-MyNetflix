CREATE DATABASE myNetflixDB;
USE myNetflixDB;

-- Tabla de Películas
CREATE TABLE peliculas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  poster VARCHAR(255),
  descripcion TEXT,
  autor VARCHAR(255),
  fecha_lanzamiento DATE,
  reparto TEXT,
  trailer TEXT,
  likes INT
);

-- Insertar datos en la tabla de películas
INSERT INTO peliculas (title, poster, descripcion, autor, fecha_lanzamiento, reparto, trailer, likes) VALUES
(
  'Breaking Bad', 
  './img/bb.webp', 
  'Breaking Bad sigue la historia de Walter White, un profesor de química de secundaria en Albuquerque, Nuevo México, que tras ser diagnosticado con cáncer de pulmón terminal, decide fabricar metanfetamina junto a su exalumno Jesse Pinkman para asegurar el futuro económico de su familia. A medida que avanza la serie, Walter se convierte en un implacable narcotraficante conocido como "Heisenberg", enfrentándose a peligrosos criminales y desafiando las leyes morales.', 
  'Vince Gilligan', 
  '2008-01-20', 
  'Bryan Cranston, Aaron Paul, Anna Gunn, Dean Norris, Betsy Brandt', 
  'https://www.youtube.com/watch?v=HhesaQXLuRY',
  250
),
(
  'La Casa de Papel', 
  './img/lcdp.webp', 
  'Un grupo de ocho ladrones liderados por "El Profesor" lleva a cabo un audaz atraco en la Fábrica Nacional de Moneda y Timbre de España. Mientras mantienen rehenes dentro del edificio, deben enfrentarse a la policía y resolver conflictos internos. La serie combina acción, drama y suspenso mientras explora temas como la justicia, la resistencia y el sacrificio.', 
  'Álex Pina', 
  '2017-05-02', 
  'Álvaro Morte, Úrsula Corberó, Itziar Ituño, Pedro Alonso, Miguel Herrán', 
  'https://www.youtube.com/watch?v=3y-6iaveY6c', 
  150
),
(
  'Fast & Furious 8', 
  './img/ff8.webp', 
  'En esta octava entrega de la saga Fast & Furious, Dominic Toretto (Vin Diesel) traiciona a su familia al aliarse con la villana Cipher (Charlize Theron). Mientras tanto, su equipo debe unirse para detener una conspiración global que involucra vehículos autónomos y armas de destrucción masiva. La película combina escenas de alta velocidad, emociones intensas y momentos de camaradería.', 
  'F. Gary Gray', 
  '2017-04-14', 
  'Vin Diesel, Dwayne Johnson, Charlize Theron, Michelle Rodriguez, Jason Statham', 
  'https://www.youtube.com/watch?v=uisBaTkQAEs&t=1s', 
  300
),
(
  'Stranger Things', 
  './img/st.webp', 
  'Ambientada en los años 80 en el pequeño pueblo de Hawkins, Indiana, Stranger Things sigue a un grupo de niños que descubren un mundo paralelo llamado "El Mundo del Revés" después de que su amigo Will Byers desaparece misteriosamente. Con la ayuda de una niña con poderes telequinéticos llamada Eleven, enfrentan criaturas sobrenaturales y secretos gubernamentales mientras intentan salvar a su amigo. La serie mezcla elementos de ciencia ficción, horror y nostalgia ochentera.', 
  'Matt Duffer, Ross Duffer', 
  '2016-07-15', 
  'Millie Bobby Brown, Finn Wolfhard, Winona Ryder, David Harbour, Gaten Matarazzo', 
  'https://www.youtube.com/embed/R1ZXOOLMJ8s?si=saLssPK39f2_SC3X', 
  400
),
(
  'Prison Break', 
  './img/pb.png', 
  'Ambientada en Birmingham, Inglaterra, tras la Primera Guerra Mundial, Peaky Blinders narra la historia de la familia Shelby y su banda criminal, liderada por Tommy Shelby (Cillian Murphy). La serie explora cómo la familia construye un imperio del crimen mientras enfrenta amenazas externas, traiciones internas y el ascenso político. Con un estilo visual único y una banda sonora icónica, la serie combina drama, acción y política.', 
  'Steven Knight', 
  '2013-09-12', 
  'Cillian Murphy, Helen McCrory, Paul Anderson, Tom Hardy, Sophie Rundle', 
  'https://www.youtube.com/watch?v=AL9zLctDJaU&t=1s', 
  180
);

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
  password VARCHAR(255) NOT NULL, 
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos en la tabla de usuarios
INSERT INTO usuarios (nombre, email, password) VALUES
('Kilian', 'kilian@gmail.com', '$2b$12$RhUYA1WMgaUpw3zQegXRYeAg3PpOaOhsLLCSmiUnvdAhNw5UHu/AK'),
('Hugo', 'hugo@gmail.com', '$2b$12$9RzCMHtfRNhWx.5rtRdPOeISu74e7nRYu8nckS2.ujtHFosKe8xDK'),
('Alberto', 'alberto@gmail.com', '$2b$12$LMQ1gj128E2uTL0fWP697./P/pJIJVxw3TLBjpncQSQBB8emRMJji');

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
(1, 1),  -- Kilian -> Admin
(2, 2),  -- Hugo -> Suscriptor
(3, 2);  -- Alberto -> Suscriptor

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
(2, 1),  -- Kilian le da like a Breaking Bad
(3, 2),  -- Hugo le da like a La Casa de Papel
(1, 4);  -- Alberto le da like a Stranger Things
