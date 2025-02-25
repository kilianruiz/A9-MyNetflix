CREATE DATABASE myNetflixDB;
USE myNetflixDB;

-- Tabla de Películas
CREATE TABLE peliculas (
  id_pelicula INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  poster VARCHAR(255),
  descripcion TEXT,
  autor VARCHAR(255),
  fecha_lanzamiento DATE,
  reparto TEXT,
  trailer TEXT
);

-- Insertar datos en la tabla de películas
INSERT INTO peliculas (title, poster, descripcion, autor, fecha_lanzamiento, reparto, trailer) VALUES
(
  'Breaking Bad', 
  './img/bb.png', 
  'Breaking Bad sigue la historia de Walter White, un profesor de química de secundaria en Albuquerque, Nuevo México, que tras ser diagnosticado con cáncer de pulmón terminal, decide fabricar metanfetamina junto a su exalumno Jesse Pinkman para asegurar el futuro económico de su familia. A medida que avanza la serie, Walter se convierte en un implacable narcotraficante conocido como "Heisenberg", enfrentándose a peligrosos criminales y desafiando las leyes morales.', 
  'Vince Gilligan', 
  '2008-01-20', 
  'Bryan Cranston, Aaron Paul, Anna Gunn, Dean Norris, Betsy Brandt', 
  'https://www.youtube.com/watch?v=HhesaQXLuRY'
),
(
  'La Casa de Papel', 
  './img/lcdp.png', 
  'Un grupo de ocho ladrones liderados por "El Profesor" lleva a cabo un audaz atraco en la Fábrica Nacional de Moneda y Timbre de España. Mientras mantienen rehenes dentro del edificio, deben enfrentarse a la policía y resolver conflictos internos. La serie combina acción, drama y suspenso mientras explora temas como la justicia, la resistencia y el sacrificio.', 
  'Álex Pina', 
  '2017-05-02', 
  'Álvaro Morte, Úrsula Corberó, Itziar Ituño, Pedro Alonso, Miguel Herrán', 
  'https://www.youtube.com/watch?v=3y-6iaveY6c'
),
(
  'Fast & Furious 8', 
  './img/ff8.png', 
  'En esta octava entrega de la saga Fast & Furious, Dominic Toretto (Vin Diesel) traiciona a su familia al aliarse con la villana Cipher (Charlize Theron). Mientras tanto, su equipo debe unirse para detener una conspiración global que involucra vehículos autónomos y armas de destrucción masiva. La película combina escenas de alta velocidad, emociones intensas y momentos de camaradería.', 
  'F. Gary Gray', 
  '2017-04-14', 
  'Vin Diesel, Dwayne Johnson, Charlize Theron, Michelle Rodriguez, Jason Statham', 
  'https://www.youtube.com/watch?v=uisBaTkQAEs&t=1s'
),
(
  'Stranger Things', 
  './img/st.png', 
  'Ambientada en los años 80 en el pequeño pueblo de Hawkins, Indiana, Stranger Things sigue a un grupo de niños que descubren un mundo paralelo llamado "El Mundo del Revés" después de que su amigo Will Byers desaparece misteriosamente. Con la ayuda de una niña con poderes telequinéticos llamada Eleven, enfrentan criaturas sobrenaturales y secretos gubernamentales mientras intentan salvar a su amigo. La serie mezcla elementos de ciencia ficción, horror y nostalgia ochentera.', 
  'Matt Duffer, Ross Duffer', 
  '2016-07-15', 
  'Millie Bobby Brown, Finn Wolfhard, Winona Ryder, David Harbour, Gaten Matarazzo', 
  'https://www.youtube.com/embed/R1ZXOOLMJ8s?si=saLssPK39f2_SC3X'
),
(
  'Prison Break', 
  './img/pb.png', 
  'Ambientada en Birmingham, Inglaterra, tras la Primera Guerra Mundial, Peaky Blinders narra la historia de la familia Shelby y su banda criminal, liderada por Tommy Shelby (Cillian Murphy). La serie explora cómo la familia construye un imperio del crimen mientras enfrenta amenazas externas, traiciones internas y el ascenso político. Con un estilo visual único y una banda sonora icónica, la serie combina drama, acción y política.', 
  'Steven Knight', 
  '2013-09-12', 
  'Cillian Murphy, Helen McCrory, Paul Anderson, Tom Hardy, Sophie Rundle', 
  'https://www.youtube.com/watch?v=AL9zLctDJaU&t=1s'
),
(
  'The Shawshank Redemption','./img/shawshank.png',
  'La historia de Andy Dufresne, un hombre condenado por asesinato que demuestra su inocencia con la ayuda de su amigo Red mientras sobrevive en la prisión de Shawshank.',
  'Frank Darabont',
  '1994-09-23',
  'Tim Robbins, Morgan Freeman, Bob Gunton, William Sadler',
  'https://www.youtube.com/watch?v=6hB3S9bIaco'
),
(
  'The Godfather',
  './img/godfather.png',
  'La saga de la familia Corleone, liderada por Vito Corleone, y el ascenso de su hijo Michael como el nuevo jefe de la mafia.',
  'Francis Ford Coppola',
  '1972-03-24',
  'Marlon Brando, Al Pacino, James Caan, Richard S. Castellano',
  'https://www.youtube.com/watch?v=sY1S34973zA'
),
(
  'Pulp Fiction',
  './img/pulpfiction.png',
  'Una serie de historias entrelazadas sobre criminales, boxeadores, traficantes de drogas y esposas infieles en Los Ángeles.',
  'Quentin Tarantino',
  '1994-10-14',
  'John Travolta, Uma Thurman, Samuel L. Jackson, Bruce Willis',
  'https://www.youtube.com/watch?v=s7EdQ4FqbhY'
),
(
  'Inception',
  './img/inception.png',
  'Un ladrón experimentado que roba secretos corporativos a través de la tecnología de compartir sueños recibe la oportunidad de redimirse al realizar un trabajo inverso: plantar una idea en lugar de robarla.',
  'Christopher Nolan',
  '2010-07-16',
  'Leonardo DiCaprio, Joseph Gordon-Levitt, Ellen Page, Tom Hardy',
  'https://www.youtube.com/watch?v=8hP9D6kZseM'
),
(
  'Interstellar',
  './img/interstellar.png',
  'Un grupo de exploradores viaja a través de un agujero de gusano en busca de un nuevo hogar para la humanidad ante la inminente extinción en la Tierra.',
  'Christopher Nolan',
  '2014-11-07',
  'Matthew McConaughey, Anne Hathaway, Jessica Chastain, Michael Caine',
  'https://www.youtube.com/watch?v=zSWdZVtXT7E'
),
(
  'Blade Runner 2049',
  './img/bladerunner.png',
  'Un replicante llamado K descubre un secreto perdido que podría causar el colapso de la sociedad. Su búsqueda lo lleva a encontrar a Rick Deckard, desaparecido durante décadas.',
  'Denis Villeneuve',
  '2017-10-06',
  'Ryan Gosling, Harrison Ford, Ana de Armas, Jared Leto',
  'https://www.youtube.com/watch?v=gCcx85zbxr4'
),
(
  'The Dark Knight', 
  './img/darkknight.png', 
  'El Caballero Oscuro enfrenta a un nuevo adversario, El Guasón, quien sume a Gotham City en el caos y prueba los límites morales de Batman.', 
  'Christopher Nolan', 
  '2008-07-18', 
  'Christian Bale, Heath Ledger, Aaron Eckhart, Michael Caine', 
  'https://www.youtube.com/watch?v=EXeTwQWrcwY'
),
(
  'Avengers: Endgame', 
  './img/endgame.png', 
  'Los Vengadores se unen para revertir los daños causados por Thanos y restaurar el equilibrio del universo después de los eventos de Infinity War.', 
  'Anthony Russo, Joe Russo', 
  '2019-04-26', 
  'Robert Downey Jr., Chris Evans, Mark Ruffalo, Chris Hemsworth', 
  'https://www.youtube.com/watch?v=TcMBFSGVi1c'
),
(
  'Spider-Man: No Way Home', 
  './img/spiderman.png',
  'Peter Parker busca la ayuda de Doctor Strange para restaurar su identidad oculta, pero algo sale mal, trayendo enemigos de otros universos.', 
  'Jon Watts', 
  '2021-12-17', 
  'Tom Holland, Zendaya, Benedict Cumberbatch, Alfred Molina', 
  'https://www.youtube.com/watch?v=JfVOs4VSpmA'
),
(
  'Toy Story', 
  './img/toystory.png', 
  'Las aventuras de un grupo de juguetes cobran vida cuando sus dueños no están presentes, liderados por Woody y Buzz Lightyear.', 
  'John Lasseter', 
  '1995-11-22', 
  'Tom Hanks, Tim Allen, Don Rickles, Jim Varney', 
  'https://www.youtube.com/watch?v=KYz2wyBy3kc'
),
(
  'Frozen', 
  './img/frozen.png', 
  'Elsa y Anna emprenden un viaje para descubrir el origen de los poderes mágicos de Elsa y salvar su reino del invierno eterno.', 
  'Chris Buck, Jennifer Lee', 
  '2013-11-27', 
  'Idina Menzel, Kristen Bell, Jonathan Groff, Josh Gad', 
  'https://www.youtube.com/watch?v=TbQm5doF_Uc'
),
(
  'Spirited Away', 
  './img/spiritedaway.png', 
  'Chihiro, una joven niña, se encuentra atrapada en un mundo de dioses y espíritus, donde debe trabajar en un baño onsen para salvar a sus padres convertidos en cerdos.', 
  'Hayao Miyazaki', 
  '2001-07-20', 
  'Rumi Hiiragi, Miyu Irino, Mari Natsuki, Takashi Naitô', 
  'https://www.youtube.com/watch?v=ByXuk9QqQkk'
),
(
  'Goodfellas', 
  './img/goodfellas.png', 
  'La historia de Henry Hill y sus amigos, quienes subieron y cayeron en el mundo del crimen organizado en Nueva York.', 
  'Martin Scorsese', 
  '1990-09-19', 
  'Robert De Niro, Ray Liotta, Joe Pesci, Lorraine Bracco', 
  'https://www.youtube.com/watch?v=qo5jJpHtI1Y'
),
(
  'Monty Python and the Holy Grail', 
  './img/holygrail.png', 
  'Una parodia hilarante de las leyendas artúricas, donde los caballeros buscan el Santo Grial enfrentando absurdos desafíos.', 
  'Terry Gilliam, Terry Jones', 
  '1975-04-03', 
  'Graham Chapman, John Cleese, Terry Gilliam, Eric Idle', 
  'https://www.youtube.com/watch?v=LG1PlkURjxE'
),
(
  'Superbad', 
  './img/superbad.png', 
  'Dos amigos nerds intentan conseguir alcohol para una fiesta épica antes de graduarse de la escuela secundaria.', 
  'Greg Mottola', 
  '2007-08-17', 
  'Jonah Hill, Michael Cera, Emma Stone, Christopher Mintz-Plasse', 
  'https://www.youtube.com/watch?v=ecgwOWn9gSw'
),
(
  'The Conjuring', 
  './img/conjuring.png', 
  'Los investigadores paranormal Ed y Lorraine Warren ayudan a una familia atormentada por una presencia demoníaca en su granja de Rhode Island.', 
  'James Wan', 
  '2013-07-19', 
  'Vera Farmiga, Patrick Wilson, Ron Livingston, Lili Taylor', 
  'https://www.youtube.com/watch?v=k10ETZ41q5o'
),
(
  'Hereditary', 
  './img/hereditary.png', 
  'Una familia se enfrenta a eventos terroríficos después de la muerte de su patriarca, revelando oscuros secretos familiares.', 
  'Ari Aster', 
  '2018-06-08', 
  'Toni Collette, Alex Wolff, Milly Shapiro, Gabriel Byrne', 
  'https://www.youtube.com/watch?v=KU72rXrHVgg'
),
(
  'It', 
  './img/it.png', 
  'Un grupo de niños enfrenta a Pennywise, un payaso maligno que acecha a los habitantes de Derry, Maine.', 
  'Andrés Muschietti', 
  '2017-09-08', 
  'Bill Skarsgård, Jaeden Martell, Sophia Lillis, Finn Wolfhard', 
  'https://www.youtube.com/watch?v=PNZ55nmP9mw'
);

-- Tabla de Categorías
CREATE TABLE categorias (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre_categoria VARCHAR(100) UNIQUE NOT NULL
);

-- Insertar datos en la tabla de categorías
INSERT INTO categorias (nombre_categoria) VALUES
('Drama'),
('Acción'),
('Ciencia Ficción'),
('Crimen'),
('Terror');

-- Tabla de Relación entre Películas y Categorías
CREATE TABLE pelicula_categoria (
  id_pelicula INT,
  id_categoria INT,
  PRIMARY KEY (id_pelicula, id_categoria),
  FOREIGN KEY (id_pelicula) REFERENCES peliculas(id_pelicula),
  FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

-- Relacionar películas con categorías
INSERT INTO pelicula_categoria (id_pelicula, id_categoria) VALUES
(1, 1),  -- Breaking Bad -> Drama
(1, 4),  -- Breaking Bad -> Crimen
(2, 4),  -- La Casa de Papel -> Crimen
(3, 2),  -- Fast & Furious 8 -> Acción
(4, 3),  -- Stranger Things -> Ciencia Ficción
(4, 5),  -- Stranger Things -> Terror
(5, 4);  -- Peaky Blinders -> Crimen

-- Tabla de Roles
CREATE TABLE roles (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre_rol VARCHAR(50) UNIQUE NOT NULL
);

-- Insertar datos en la tabla de roles
INSERT INTO roles (nombre_rol) VALUES
('Admin'),
('Suscriptor');

-- Tabla de Usuarios
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  FOREIGN KEY (id_rol) REFERENCES roles(id_rol),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos en la tabla de usuarios
INSERT INTO usuarios (nombre, email, password, id_rol) VALUES
('Kilian', 'kilian@gmail.com', '$2b$12$RhUYA1WMgaUpw3zQegXRYeAg3PpOaOhsLLCSmiUnvdAhNw5UHu/AK',1),
('Hugo', 'hugo@gmail.com', '$2b$12$9RzCMHtfRNhWx.5rtRdPOeISu74e7nRYu8nckS2.ujtHFosKe8xDK',1),
('Alberto', 'alberto@gmail.com', '$2b$12$LMQ1gj128E2uTL0fWP697./P/pJIJVxw3TLBjpncQSQBB8emRMJji',2);

-- Tabla de Likes
CREATE TABLE likes (
  id_like_usuario INT NOT NULL AUTO_INCREMENT,
  usuario_id INT,
  pelicula_id INT,
  PRIMARY KEY (id_like_usuario),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (pelicula_id) REFERENCES peliculas(id_pelicula)
);

-- Insertar likes de los usuarios
INSERT INTO likes (usuario_id, pelicula_id) VALUES
(2, 1), -- Kilian le da like a Breaking Bad
(3, 2), -- Hugo le da like a La Casa de Papel
(3, 1), -- Hugo le da like a Breaking Bad
(3, 4), -- Hugo le da like a Stranger Things
(1, 4); -- Alberto le da like a Stranger Things

-- Actualizar la tabla de registro_pendiente
CREATE TABLE registro_pendiente (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'aceptado', 'rechazado') DEFAULT 'pendiente'
);