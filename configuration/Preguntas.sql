-- Seleccionar la base de datos
USE preguntados;

-- Insertar categoría para las preguntas
INSERT INTO categorias (nombre_de_categoria, color_de_categoria)
VALUES
    ('Historia de Argentina', '#FF5733');

-- Obtener el ID de la categoría recién insertada
SET @categoria_id = (SELECT id FROM categorias WHERE nombre_de_categoria = 'Historia de Argentina');

-- Insertar las preguntas
INSERT INTO preguntas (categoria_id, pregunta, nivel, tipo_pregunta, cantidad_apariciones, cantidad_veces_respondidas, estado, fecha_creacion, usuario_id)
VALUES
    (@categoria_id, '¿Cual es el baile tradicional de Argentina que se caracteriza por el abrazo entre los bailarines?', 'facil', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Cual es la moneda Oficial de Argentina?', 'facil', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Quien fue la figura historica argentina conocida como "El libertador"?', 'facil', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿En qué ciudad argentina se encuentra el famoso teatro Colón, conocido por ser uno de los mejores teatros de ópera del mundo?', 'facil', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Cuál es el plato nacional de Argentina que consiste en carne asada a la parrilla?', 'facil', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Qué montaña, ubicada en la frontera entre Argentina y Chile, es la montaña más alta de América del Sur?', 'facil', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Cuál es la capital de Argentina?', 'facil', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Qué escritor argentino es uno de los más famosos del mundo, conocido por su obra "Ficciones"?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Cuál es el equipo de fútbol más exitoso de Argentina, con numerosos títulos nacionales e internacionales?', 'facil', 'creada', 0, 0, 'aprobada', NOW(), 1);

-- Obtener los IDs de las preguntas insertadas
SET @pregunta1_id = (SELECT id FROM preguntas WHERE pregunta = '¿Cual es el baile tradicional de Argentina que se caracteriza por el abrazo entre los bailarines?');
SET @pregunta2_id = (SELECT id FROM preguntas WHERE pregunta = '¿Cual es la moneda Oficial de Argentina?');
SET @pregunta3_id = (SELECT id FROM preguntas WHERE pregunta = '¿Quien fue la figura historica argentina conocida como "El libertador"?');
SET @pregunta4_id = (SELECT id FROM preguntas WHERE pregunta = '¿En qué ciudad argentina se encuentra el famoso teatro Colón, conocido por ser uno de los mejores teatros de ópera del mundo?');
SET @pregunta5_id = (SELECT id FROM preguntas WHERE pregunta = '¿Cuál es el plato nacional de Argentina que consiste en carne asada a la parrilla?');
SET @pregunta6_id = (SELECT id FROM preguntas WHERE pregunta = '¿Qué montaña, ubicada en la frontera entre Argentina y Chile, es la montaña más alta de América del Sur?');
SET @pregunta7_id = (SELECT id FROM preguntas WHERE pregunta = '¿Cuál es la capital de Argentina?');
SET @pregunta8_id = (SELECT id FROM preguntas WHERE pregunta = '¿Qué escritor argentino es uno de los más famosos del mundo, conocido por su obra "Ficciones"?');
SET @pregunta9_id = (SELECT id FROM preguntas WHERE pregunta = '¿Cuál es el equipo de fútbol más exitoso de Argentina, con numerosos títulos nacionales e internacionales?');

-- Insertar las opciones correspondientes a cada pregunta
INSERT INTO opciones (pregunta_id, opcion1, opcion2, opcion3, opcion_correcta)
VALUES
    (@pregunta1_id, 'Samba', 'Cumbia', 'Zamba', 'Tango'),
    (@pregunta2_id, 'Dólar', 'Euro', 'Real', 'Peso argentino'),
    (@pregunta3_id, 'Simón Bolívar', 'Manuel Belgrano', 'Eva Perón', 'José de San Martín'),
    (@pregunta4_id, 'Mendoza', 'Córdoba', 'Rosario', 'Buenos Aires'),
    (@pregunta5_id, 'Paella', 'Pizza', 'Empanadas', 'Asado'),
    (@pregunta6_id, 'Monte Everest', 'Cerro Torre', 'Mont Blanc', 'Aconcagua'),
    (@pregunta7_id, 'Montevideo', 'Santiago', 'La Paz', 'Buenos Aires'),
    (@pregunta8_id, 'Julio Cortázar', 'Adolfo Bioy Casares', 'Ricardo Piglia', 'Jorge Luis Borges'),
    (@pregunta9_id, 'Independiente', 'River Plate', 'San Lorenzo', 'Boca Juniors');





-- Usar la base de datos
USE preguntados;

-- Obtener el ID de la categoría de Historia de Argentina
SET @categoria_id = (SELECT id FROM categorias WHERE nombre_de_categoria = 'Historia de Argentina');

-- Insertar preguntas de nivel normal
INSERT INTO preguntas (categoria_id, pregunta, nivel, tipo_pregunta, cantidad_apariciones, cantidad_veces_respondidas, estado, fecha_creacion, usuario_id)
VALUES
    (@categoria_id, '¿Cuál fue el año en que Argentina declaró su independencia?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Quién fue el primer presidente de Argentina?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Qué conflicto bélico tuvo lugar entre Argentina y el Reino Unido en 1982?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Qué ciudad fue la primera capital de Argentina?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Cuál es el significado de la palabra "gaucho"?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Qué figura histórica es conocida como "La madre de la plaza"?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿En qué año se realizó la reforma constitucional que estableció la autonomía de la Ciudad de Buenos Aires?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Qué movimiento social y político surgió en Argentina en la década de 1940 bajo el liderazgo de Juan Domingo Perón?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1),
    (@categoria_id, '¿Cuál es el nombre del famoso recorrido turístico que incluye la Casa Rosada y el Obelisco?', 'normal', 'creada', 0, 0, 'aprobada', NOW(), 1);

-- Obtener los IDs de las preguntas insertadas
SET @pregunta1_id = (SELECT id FROM preguntas WHERE pregunta = '¿Cuál fue el año en que Argentina declaró su independencia?');
SET @pregunta2_id = (SELECT id FROM preguntas WHERE pregunta = '¿Quién fue el primer presidente de Argentina?');
SET @pregunta3_id = (SELECT id FROM preguntas WHERE pregunta = '¿Qué conflicto bélico tuvo lugar entre Argentina y el Reino Unido en 1982?');
SET @pregunta4_id = (SELECT id FROM preguntas WHERE pregunta = '¿Qué ciudad fue la primera capital de Argentina?');
SET @pregunta5_id = (SELECT id FROM preguntas WHERE pregunta = '¿Cuál es el significado de la palabra "gaucho"?');
SET @pregunta6_id = (SELECT id FROM preguntas WHERE pregunta = '¿Qué figura histórica es conocida como "La madre de la plaza"?');
SET @pregunta7_id = (SELECT id FROM preguntas WHERE pregunta = '¿En qué año se realizó la reforma constitucional que estableció la autonomía de la Ciudad de Buenos Aires?');
SET @pregunta8_id = (SELECT id FROM preguntas WHERE pregunta = '¿Qué movimiento social y político surgió en Argentina en la década de 1940 bajo el liderazgo de Juan Domingo Perón?');
SET @pregunta9_id = (SELECT id FROM preguntas WHERE pregunta = '¿Cuál es el nombre del famoso recorrido turístico que incluye la Casa Rosada y el Obelisco?');

-- Insertar las opciones correspondientes a cada pregunta con 4 opciones, diferenciando la opción correcta
INSERT INTO opciones (pregunta_id, opcion1, opcion2, opcion3, opcion_correcta)
VALUES
    (@pregunta1_id, '1810', '1821', '1806', '1816'),
    (@pregunta2_id, 'Domingo Faustino Sarmiento', 'Manuel de Rosas', 'Bartolomé Mitre', 'Bernardino Rivadavia'),
    (@pregunta3_id, 'Guerra de la Triple Alianza', 'Guerra Civil', 'Guerra del Pacífico', 'Guerra de Malvinas'),
    (@pregunta4_id, 'Buenos Aires', 'Salta', 'Santiago del Estero', 'Córdoba'),
    (@pregunta5_id, 'Poblador de las pampas', 'Vigilante de los gauchos', 'Habitante de las montañas', 'Vaquero de las pampas'),
    (@pregunta6_id, 'Madre Teresa', 'Eva Perón', 'María Eva Duarte', 'Madre de Plaza de Mayo'),
    (@pregunta7_id, '1985', '2001', '1978', '1994'),
    (@pregunta8_id, 'Radicalismo', 'Socialismo', 'Liberalismo', 'Peronismo'),
    (@pregunta9_id, 'La Avenida de Mayo', 'La Calle Florida', 'La Plaza San Martín', 'La Avenida 9 de Julio');