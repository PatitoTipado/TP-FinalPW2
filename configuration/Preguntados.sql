-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS preguntados;

-- Usar la base de datos preguntados
USE preguntados;

-- Crear la tabla usuarios si no existe
-- REVISAR ATRIBUTOS UNICOS
CREATE TABLE IF NOT EXISTS usuarios (
                                        id INT AUTO_INCREMENT PRIMARY KEY,
                                        nombre_de_usuario VARCHAR(50) NOT NULL UNIQUE, -- Nombre de usuario único
    nombre VARCHAR(100) NOT NULL,                  -- Nombre solo con letras
    anio_de_nacimiento INT NOT NULL,               -- Año de nacimiento
    email VARCHAR(100) NOT NULL UNIQUE,            -- Email único y formato correcto
    contrasena VARCHAR(255) NOT NULL,              -- Contraseña hasheada
    sexo ENUM('M', 'F', 'Otro') NOT NULL,          -- Sexo con valores M, F o 'Otro'
    pais VARCHAR(100) NOT NULL,                    -- País del usuario
    ciudad VARCHAR(100) NOT NULL,                  -- Ciudad del usuario
    imagen_url VARCHAR(255),                       -- URL de la imagen de perfil
    fecha_registro VARCHAR(255),                   -- Fecha de registro, posible refactorización a DATE
    estado ENUM('activo', 'inactivo', 'bloqueado') DEFAULT 'inactivo', -- Estado de la cuenta
    rol ENUM('administrador', 'editor', 'jugador') DEFAULT 'jugador', -- Rol del usuario
    nivel ENUM('facil', 'normal', 'dificil') DEFAULT 'normal', -- Nivel del usuario
    cantidad_respuestas_correctas INT DEFAULT 0,   -- Cantidad de respuestas correctas
    cantidad_preguntas_respondidas INT DEFAULT 0,   -- Cantidad de preguntas respondidas
    hash INT UNIQUE
    );

CREATE TABLE IF NOT EXISTS partidas (
                                        id INT AUTO_INCREMENT PRIMARY KEY,
                                        usuario_id INT NOT NULL, -- Clave foránea al usuario que juega la partida
                                        fecha_de_partida DATETIME NOT NULL, -- Fecha de inicio de la partida
                                        fecha_de_finalizacion DATETIME, -- Fecha de finalización de la partida
                                        puntaje_total INT DEFAULT 0, -- Puntaje total de la partida
                                        nivel ENUM('facil', 'normal', 'dificil') NOT NULL, -- Nivel de dificultad de la partida, mismo que en usuarios
                                        estado ENUM('finalizada', 'en curso', 'en revision') NOT NULL, -- Estado de la partida
                                        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Crear tabla de categorías
CREATE TABLE IF NOT EXISTS categorias (
                                          id INT AUTO_INCREMENT PRIMARY KEY,
                                          nombre_de_categoria VARCHAR(100) NOT NULL, -- Nombre de la categoría
                                          color_de_categoria VARCHAR(7) NOT NULL    -- Color de la categoría en formato hexadecimal
);

-- Crear tabla de preguntas
CREATE TABLE IF NOT EXISTS preguntas (
                                         id INT AUTO_INCREMENT PRIMARY KEY,
                                         categoria_id INT NOT NULL,                     -- Relación con la tabla categorías
                                         pregunta VARCHAR(255) NOT NULL,                -- Texto de la pregunta
                                         nivel ENUM('facil', 'normal', 'dificil') DEFAULT 'normal',  -- Nivel de dificultad de la pregunta
                                         tipo_pregunta ENUM('creada', 'sugerida') NOT NULL,  -- Diferencia entre creada o sugerida
                                         cantidad_apariciones INT DEFAULT 0,            -- Cantidad de veces que la pregunta aparece en una partida
                                         cantidad_veces_respondidas INT DEFAULT 0,      -- Cantidad de veces que se ha respondido la pregunta
                                         estado ENUM('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente',  -- Estado de la pregunta
                                         fecha_creacion DATETIME NOT NULL,              -- Fecha de creación o sugerencia
                                         usuario_id INT NOT NULL,                       -- Relación con el usuario que crea o sugiere la pregunta
                                         FOREIGN KEY (categoria_id) REFERENCES categorias(id),  -- FK con categorías
                                         FOREIGN KEY (usuario_id) REFERENCES usuarios(id)       -- FK con usuarios
);

-- Crear tabla de opciones para las preguntas
CREATE TABLE IF NOT EXISTS opciones (
                                        id INT AUTO_INCREMENT PRIMARY KEY,
                                        pregunta_id INT NOT NULL,                      -- Relación con la tabla preguntas
                                        opcion1 VARCHAR(255) NOT NULL,
                                        opcion2 VARCHAR(255) NOT NULL,
                                        opcion3 VARCHAR(255) NOT NULL,
                                        opcion_correcta VARCHAR(255) NOT NULL,         -- La opción correcta
                                        FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)  -- FK con preguntas
);

-- Crear tabla de reportes con relación a preguntas
CREATE TABLE IF NOT EXISTS reportes (
                                        id INT AUTO_INCREMENT PRIMARY KEY,
                                        pregunta_id INT NOT NULL,                      -- Relación con la tabla preguntas
                                        usuario_realiza_id INT NOT NULL,               -- Usuario que realiza el reporte
                                        usuario_atiende_id INT,                        -- Usuario que atiende el reporte
                                        fecha_reporte DATETIME NOT NULL,               -- Fecha del reporte
                                        fecha_atencion DATETIME,                       -- Fecha de atención del reporte
                                        descripcion TEXT,                              -- Descripción del motivo del reporte
                                        estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',  -- Estado del reporte
                                        FOREIGN KEY (pregunta_id) REFERENCES preguntas(id),  -- FK con preguntas
                                        FOREIGN KEY (usuario_realiza_id) REFERENCES usuarios(id),  -- FK con usuarios (quien realiza)
                                        FOREIGN KEY (usuario_atiende_id) REFERENCES usuarios(id)   -- FK con usuarios (quien atiende)
);


-- Crear tabla intermedia para la relación n a n entre preguntas y partidas
CREATE TABLE IF NOT EXISTS pregunta_partida (
                                                pregunta_id INT NOT NULL,                      -- Relación con preguntas
                                                partida_id INT NOT NULL,                       -- Relación con partidas
                                                respuesta_usuario VARCHAR(255),-- Si la respuesta del usuario fue correcta
                                                respondio_correctamente ENUM('bien','mal'), -- en un futuro podriamos cambiar a esto a que jugador respondio bien
                                                usuario_id INT ,
                                                fecha_inicio DATETIME NOT NULL,                -- Fecha en que se presentó la pregunta en la partida
                                                PRIMARY KEY (pregunta_id, partida_id),         -- Clave primaria compuesta
                                                FOREIGN KEY (pregunta_id) REFERENCES preguntas(id),  -- FK con preguntas
                                                FOREIGN KEY (partida_id) REFERENCES partidas(id),    -- FK con partidas
                                                FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
