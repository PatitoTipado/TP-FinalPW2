<?php

class UserModel
{
    private $database;
    private $emailSender;
    private $phpMailSender;

    public function __construct($database, $emailSender, $phpMailSender)
    {
        $this->emailSender = $emailSender;
        $this->database = $database;
        $this->phpMailSender = $phpMailSender;
    }

    public function registrarUsuario($nombre_de_usuario, $nombre, $anio_de_nacimiento, $email, $contrasena, $foto, $sexo, $latitud, $longitud)
    {

        if ($this->validarNombreUsuario($nombre_de_usuario)) {
            return "el nombre de usuario elegido ya esta registrado.";
        }

        if ($this->validarContrasena($contrasena)) {
            return "la contraseña no cumple con la longuitud requerida.";
        }

        if ($this->validarQueSoloTengaCaracteres($nombre)) {
            return "el nombre debe tener solo letras y sin espacios.";
        }

        if (empty($latitud) || empty($longitud)) {
            return "seleccione una ubicacion por favor";
        }

        $fecha_registro = $this->obtenerFechaRegistro();

        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        $hash = $this->obtenerHash();

        $pais =strtolower($this->obtenerPais($latitud,$longitud));
        $ciudad=strtolower($this->obtenerProvincia($latitud,$longitud));

        $sql = "INSERT INTO usuarios 
        (nombre_de_usuario, nombre, anio_de_nacimiento, email, contrasena, sexo, latitud,longitud ,fecha_registro,imagen_url,hash,rol,pais,ciudad) 
        VALUES 
        ('$nombre_de_usuario', '$nombre', '$anio_de_nacimiento', '$email', '$hashed_password', '$sexo', '$latitud', '$longitud', '$fecha_registro','$foto','$hash','jugador','$pais', '$ciudad')";

        if ($this->database->execute($sql)) {
            $this->emailSender->sendEmail($nombre_de_usuario, 'validacion correo', "tu codigo hash es '$hash'");
            $this->phpMailSender->sendEmail($email, $hash, $nombre_de_usuario);
            return "exitoso";
        } else {
            return "ocurrio un error en la base de datos.";
        }
    }

    public function sugerirPregunta($pregunta, $nivel, $opcion1, $opcion2, $opcion3, $opcionCorrecta, $id_categoria, $id_usuario)
    {
        $insertPregunta = "
    INSERT INTO preguntas 
    (categoria_id, pregunta, nivel, tipo_pregunta, cantidad_apariciones, cantidad_veces_respondidas, estado, fecha_creacion, usuario_id) 
    VALUES 
    ($id_categoria, '$pregunta', '$nivel', 'sugerida', 0, 0, 'pendiente', NOW(), $id_usuario)";

        $result = $this->database->execute($insertPregunta);
        if ($result) {

            $pregunta_id = $this->database->getLastInsertId();
            $insertOpciones = "
        INSERT INTO opciones 
        (pregunta_id, opcion1, opcion2, opcion3, opcion_correcta) 
        VALUES 
        ($pregunta_id, '$opcion1', '$opcion2', '$opcion3', '$opcionCorrecta')";
            if ($this->database->execute($insertOpciones)) {
                return true; //
            } else {
                return false;
            }
        } else {
            return false;
        }


    }

    private function obtenerPais($lat, $lon)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?lat=$lat&lon=$lon&format=json&addressdetails=1&language=es";

        // Inicializar cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Establecer User-Agent para evitar el error 403
        curl_setopt($ch, CURLOPT_USERAGENT, 'MiApp/1.0 (miemail@dominio.com)');

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hubo algún error con la solicitud
        if(curl_errno($ch)) {
            die( 'Error en la solicitud: ' . curl_error($ch));
        }

        // Cerrar cURL
        curl_close($ch);

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        // Verificar si se obtuvo el país
        return $data['address']['country'] ?? 'No se pudo obtener el país';
    }

    private function obtenerProvincia($lat, $lon)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?lat=$lat&lon=$lon&format=json&addressdetails=1&language=es";

        // Inicializar cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Establecer User-Agent para evitar el error 403 --> no toque el agent por que no lo necesite
        curl_setopt($ch, CURLOPT_USERAGENT, 'MiApp/1.0 (miemail@dominio.com)');

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hubo algún error con la solicitud
        if(curl_errno($ch)) {
            die( 'Error en la solicitud: ' . curl_error($ch));
        }

        // Cerrar cURL
        curl_close($ch);

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        // Verificar si se obtuvo la provincia
        return $data['address']['state'] ?? 'No se pudo obtener la provincia';
    }


    public function validarHash($hash)
    {
        $sql = "SELECT * FROM usuarios WHERE hash = '$hash'";
        $result = $this->database->execute($sql);

        if ($result->num_rows == 0) {
            return false;
        }

        $usuario = $result->fetch_assoc();

        if ($usuario['estado'] == 'inactivo') {

            $idUsuario = $usuario['id'];
            $updateQuery = "UPDATE usuarios SET estado = 'activo' WHERE id = $idUsuario";
            $this->database->execute($updateQuery);

            return true;
        } else {
            return false;
        }
    }

    public function validarLogin($usuario, $password)
    {
        $result= $this->validarUsuarioYPassword($usuario,$password);

        if ($result!=null) {

            $data['result'] = true;
            $data['id_usuario'] = $result['id'];
            $data['rol'] = $result['rol'];
            $data['user'] = $result['nombre_de_usuario'];
            $data['puntaje_maximo'] = $result['puntaje_maximo'];
            $data['rol_editor'] = ($result['rol'] == 'editor') ? true : false;

        } else {
            $sql = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$usuario' AND estado LIKE'activo'";

            $result = $this->database->execute($sql);
            $data['result'] = false;
            $data['error'] = ($result->num_rows == 1) ? "contraseña incorrecta" : "usuario inexistente o inactivo";

        }
        return $data;
    }

    public function obtenerDatosDePerfil($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id='$id'";

        $result = $this->database->execute($sql);

        if ($result->num_rows == 1) {

            $usuario = $result->fetch_assoc();

            if($usuario['rol']!='jugador'){
                $data['result'] = false;
                $data['not_found'] = "no se encontro al usuario";

            } else{
                $data['result'] = true;
                $data['foto'] = $usuario['imagen_url'];
                $data['email'] = $usuario['email'];
                $data['latitud'] = $usuario['latitud'];
                $data['longitud']= $usuario['longitud'];
                $data['maximo']= $usuario['puntaje_maximo'];
                $data['estado']=$usuario['estado'];

                $data['nombre'] = $usuario['nombre'];
                $data['sexo'] = ($usuario['sexo'] == 'F') ? 'Femenino' : 'Masculino';
                $data['username'] = $usuario['nombre_de_usuario'];
                $data['id']= $usuario['id'];

            }

        } else {
            $data['result'] = false;
            $data['not_found'] = "no se encontro al usuario";

        }

        return $data;
    }

    private function validarNombreUsuario($nombre_de_usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$nombre_de_usuario'";
        $result = $this->database->execute($sql);

        return $result->num_rows == 1;
    }

    private function validarContrasena($contrasena)
    {

        return strlen($contrasena) <= 8;
    }

    private function validarQueSoloTengaCaracteres($nombre)
    {
        return !(ctype_alpha($nombre));
    }

    private function obtenerHash()
    {
        $flag = false;

        while (!$flag) {

            $hash = rand(1, 1000);

            $sql = "SELECT * FROM usuarios WHERE hash='$hash'";
            $result = $this->database->execute($sql);

            if ($result->num_rows == 0) {
                $flag = true;
            }
        }

        return $hash;
    }

    private function obtenerFechaRegistro()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha_actual = new DateTime();

        return $fecha_actual->format('Y-m-d H:i:s');
    }

    private function validarUsuarioYPassword($usuario, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$usuario' AND estado = 'activo'";

        $result = $this->database->execute($sql);
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            if((password_verify($password, $row['contrasena']))){
                return $row;
            }

        }

        return null;
    }
}
