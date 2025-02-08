CREATE DATABASE IF NOT EXISTS portaldocente;
USE portaldocente;

-- Tabla usuario
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    dni VARCHAR(9) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(15),
    rol ENUM('docente', 'administrador') NOT NULL,
    disponibilidad BOOLEAN DEFAULT TRUE,
    isla VARCHAR(50),
    puntuacion DECIMAL(5,2),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla noticia
CREATE TABLE noticia (
    id_noticia INT AUTO_INCREMENT PRIMARY KEY,
    autor_id INT,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    imagen_url VARCHAR(255),
    FOREIGN KEY (autor_id) REFERENCES usuario(id_usuario) ON DELETE SET NULL
);

-- Tabla adjudicacion
CREATE TABLE adjudicacion (
    id_adjudicacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    isla ENUM(
        'Tenerife', 'Gran Canaria', 'Lanzarote', 'Fuerteventura',
        'La Palma', 'La Gomera', 'El Hierro', 'La Graciosa'
    ) NOT NULL,
    municipio VARCHAR(100) NOT NULL,
    fecha_adjudicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

-- Tabla solicitud
CREATE TABLE solicitud (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo_solicitud ENUM('cambio de destino', 'nueva adjudicación') NOT NULL,
    estado_solicitud ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente',
    detalles_destino_solicitado TEXT NOT NULL,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

-- Insertar usuarios de prueba
INSERT INTO usuario (id_usuario, nombre, apellido, dni, email, password, telefono, rol, disponibilidad, isla, puntuacion, fecha_creacion) VALUES 
(1, 'Admin', 'Admin', '00000000A', 'admin@email.com', 'admin', '123456789', 'administrador', TRUE, 'Tenerife', NULL, '2025-02-03 23:37:21'),
(2, 'Juan', 'Perez', '12345678B', 'juanperez@email.com', 'juanperez', '987654321', 'docente', TRUE, 'Gran Canaria', 85.50, '2025-02-03 23:37:21'),
(3, 'María', 'Rodríguez', '12345678A', 'maria.rodriguez@email.com', 'password123', '654123987', 'docente', TRUE, 'Tenerife', 87.50, '2025-02-07 19:29:43'),
(4, 'Carlos', 'Sánchez', '23456789B', 'carlos.sanchez@email.com', 'password123', '678945321', 'docente', TRUE, 'Gran Canaria', 92.30, '2025-02-07 19:29:43'),
(5, 'Elena', 'Gómez', '34567890C', 'elena.gomez@email.com', 'password123', '612345789', 'docente', TRUE, 'Lanzarote', 85.75, '2025-02-07 19:29:43'),
(6, 'David', 'Fernández', '45678901D', 'david.fernandez@email.com', 'password123', '698745236', 'docente', TRUE, 'Fuerteventura', 90.60, '2025-02-07 19:29:43');

-- Insertar noticias de prueba
INSERT INTO noticia (autor_id, titulo, contenido, imagen_url, fecha) VALUES
(3, 'La Consejería de Educación amplía la oferta de plazas docentes', 
'El Gobierno de Canarias ha anunciado una ampliación de plazas para el próximo curso escolar con el objetivo de reforzar la calidad educativa.',
'../assets/img/consejeria.jpg', NOW()),
(4, 'Cursos gratuitos sobre herramientas digitales', 
'La Consejería de Educación ofrece formación gratuita para docentes sobre nuevas tecnologías aplicadas a la educación.',
'../assets/img/cursos.jpg', NOW()),
(5, 'Nueva normativa sobre inclusión educativa', 
'El Parlamento de Canarias ha aprobado la Ley de Inclusión Educativa, que entrará en vigor en el curso 2025-2026.',
'../assets/img/inclusion.jpg', NOW()),
(6, 'Convocatoria de proyectos educativos innovadores', 
'La Consejería de Educación ha abierto el plazo para que los centros educativos presenten proyectos innovadores orientados a mejorar la calidad de la enseñanza.',
'../assets/img/innovacion.jpg', NOW());
