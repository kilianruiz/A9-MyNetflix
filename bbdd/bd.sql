CREATE DATABASE myNetflixDB;
use myNetflixDB;

CREATE TABLE peliculas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  poster VARCHAR(255),
  description TEXT,
  likes INT
);

INSERT INTO peliculas (title, poster, description, likes) VALUES
('Breaking Bad', './img/bb.webp', 'Serie de drama sobre un profesor de química que se convierte en fabricante de metanfetaminas.', 250),
('La Casa de Papel', './img/lcdp.webp', 'Un grupo de delincuentes lleva a cabo un asalto a la Real Casa de la Moneda de España.', 150),
('Fast & Furious 8', './img/ff8.webp', 'La octava entrega de la saga Fast & Furious, centrada en traiciones y carreras de autos.', 300),
('Stranger Things', './img/st.webp', 'Serie de ciencia ficción y terror que sigue a un grupo de niños enfrentándose a criaturas de otro mundo.', 400),
('Peaky Blinders', './img/pb.png', 'La historia de la familia Shelby y su banda criminal en el post-guerra de Inglaterra.', 180);

-- tabla de categorias
-- tabla de likes
-- tabla de usuarios
-- tabla de roles