-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS preguntados;

-- Usar la base de datos preguntados
USE preguntados;

-- Crear la tabla usuarios si no existe
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
    fecha_registro VARCHAR (255),
    estado VARCHAR (100) DEFAULT 'inactivo',
    hash VARCHAR (255));