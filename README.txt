## 📖 **Descripción del Proyecto**

Trivia Gaucha permite a los usuarios:
- Responder preguntas de trivia organizadas en diferentes niveles de dificultad.
- Interactuar con un sistema anti-trampas para garantizar la honestidad en el juego.
- Sugerir y reportar preguntas para mejorar la calidad del contenido.

Roles especiales:
- **Usuario Editor**: Gestiona las preguntas (altas, bajas, modificaciones) y atiende reportes y sugerencias de otros usuarios.
- **Usuario Administrador**: Accede a estadísticas detalladas, filtra información y genera reportes en PDF.

El juego está desarrollado siguiendo el patrón **MVC** con **PHP** y **Mustache**, utilizando librerías de terceros para funciones avanzadas como:
- **PHPMailer** para el envío de correos electrónicos.
- **DOMPDF** para la generación de PDFs.
- Escritura y lectura de archivos JSON.

---

## ✨ **Características Principales**

- 🛡️ **Sistema anti-trampas** para mantener la integridad del juego.
- 👥 Roles diferenciados: **Editor** y **Administrador** con permisos específicos.
- 📊 Generación de estadísticas en formato PDF.
- 📩 Capacidad de enviar correos electrónicos para notificaciones y comunicación con los usuarios.
- 📂 Gestión de preguntas (alta, baja, modificación y visualización).

---

## 🛠️ **Tecnologías Utilizadas**

- **Lenguaje**: PHP (vanilla, sin Composer)
- **Motor de Plantillas**: Mustache
- **Servidor**: XAMPP
- **Librerías de Terceros**:
  - [PHPMailer](https://github.com/PHPMailer/PHPMailer)
  - [DOMPDF](https://github.com/dompdf/dompdf)

---

## ⚙️ **Instrucciones de Instalación**

##1. **Clonar el Proyecto en el raiz de htdocs**  
## 2. Configurar la Base de Datos 🗄️

1. Abre tu **gestor de base de datos** (por ejemplo, **phpMyAdmin**). 🖥️
2. Crea una **nueva base de datos**. 🆕
3. **Importa el archivo** `PreguntadosBaseV1.sql` que encontrarás en el proyecto. 📂
4. Edita el archivo `config.ini` en el proyecto y actualiza los siguientes valores según tu configuración:
   - **host**: Dirección del servidor de base de datos (generalmente `localhost`). 🌍
   - **usuario**: Nombre de usuario de la base de datos. 👤
   - **contraseña**: Contraseña del usuario. 🔑
   - **nombre_db**: Nombre de la base de datos que creaste. 🏷️

## 3. Configurar el Correo Electrónico 📧

1. Abre el archivo `emailConfig.ini` que se encuentra en el proyecto. 📝
2. Completa los siguientes valores con las credenciales de tu cuenta de correo:
   - **correo**: Tu dirección de correo electrónico. 📧
   - **contraseña**: Una contraseña de aplicación (si utilizas Gmail u otro servicio con autenticación de dos factores). 🔒
3. Asegúrate de que tu proveedor de correo permite el uso de **aplicaciones externas**. 🌐

## 4. Levantar el Proyecto 🚀

1. Abre tu **navegador web**. 🌐
2. Accede a la siguiente URL:
   ```plaintext
   http://localhost/<nombre_proyecto>
## 2. Configurar la Base de Datos 🗄️

1. Abre tu **gestor de base de datos** (por ejemplo, **phpMyAdmin**). 🖥️
2. Crea una **nueva base de datos**. 🆕
3. **Importa el archivo** `preguntados_base_v1.sql` que encontrarás en el proyecto. 📂
4. Edita el archivo `config.ini` en el proyecto y actualiza los siguientes valores según tu configuración:
   - **host**: Dirección del servidor de base de datos (generalmente `localhost`). 🌍
   - **usuario**: Nombre de usuario de la base de datos. 👤
   - **contraseña**: Contraseña del usuario. 🔑
   - **nombre_db**: Nombre de la base de datos que creaste. 🏷️

## 3. Configurar el Correo Electrónico 📧

1. Abre el archivo `emailConfig.ini` que se encuentra en el proyecto. 📝
2. Completa los siguientes valores con las credenciales de tu cuenta de correo:
   - **correo**: Tu dirección de correo electrónico. 📧
   - **contraseña**: Una contraseña de aplicación (si utilizas Gmail u otro servicio con autenticación de dos factores). 🔒
3. Asegúrate de que tu proveedor de correo permite el uso de **aplicaciones externas**. 🌐

## 4. Levantar el Proyecto 🚀

1. Abre tu **navegador web**. 🌐
2. Accede a la siguiente URL:  http://localhost/<nombre_proyecto>



sadasdasdas
dsa
da
sd
asd
as
das
d
sad
as
d
asd
asd
as
da
sd
asd
asdad
as
