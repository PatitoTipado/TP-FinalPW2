<?php

class UserModel
{
    private $database;
    private $emailSender;

    public function __construct($database,$emailSender)
    {
        $this->emailSender=$emailSender;
        $this->database = $database;
    }

    public function registrarUsuario($nombre_de_usuario, $nombre, $anio_de_nacimiento, $email, $contrasena,$repetir_contrasena, $sexo, $pais, $ciudad, $foto)
    {
        if ($this->validarCamposQueNoEstenVaciosYTengaLaMismaContraseña
        ($nombre_de_usuario, $nombre, $anio_de_nacimiento, $email, $contrasena, $repetir_contrasena, $sexo, $pais, $ciudad)) {
            $_SESSION['error_registro'] = "ningun parametro puede estar vacio o las contraseñas no ser iguales.";
            return false;
        }

        if ($this->validarNombreUsuario($nombre_de_usuario)) {
            $_SESSION['error_registro'] = "el nombre de usuario elegido ya esta registrado.";
            return false;
        }

        if ($this->validarContrasena($contrasena)) {
            $_SESSION['error_registro'] = "la contraseña no cumple con la longuitud requerida.";
            return false;
        }

        if($this->validarQueSoloTengaCaracteres($nombre)){
            $_SESSION['error_registro'] = "el nombre debe tener solo letras y sin espacios.";
            return false;
        }

        $carpetaImagenes = $_SERVER['DOCUMENT_ROOT'] . '/public/';

        if($this->esUnaImagenValida()){ //cambiar al controller
            $rutaImagen = $carpetaImagenes . $nombre_de_usuario . '.jpg';
            move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaImagen);
            $foto = 'public/' . $nombre_de_usuario . ".jpg";
        } else {
            $_SESSION['error_registro'] = "la imagen no se subio correctamente.";
            return false;
        }

        $fecha_registro= $this->obtenerFechaRegistro();

        $hash= $this->obtenerHash();

        $sql = "INSERT INTO usuarios 
        (nombre_de_usuario, nombre, anio_de_nacimiento, email, contrasena, sexo, pais, ciudad,fecha_registro,imagen_url,hash) 
        VALUES 
        ('$nombre_de_usuario', '$nombre', '$anio_de_nacimiento', '$email', '$contrasena', '$sexo', '$pais', '$ciudad', '$fecha_registro','$foto','$hash')";

        if ($this->database->execute($sql)) {
            $this->emailSender->sendEmail($nombre_de_usuario, 'validacion correo', "tu codigo hash es '$hash'" );
            return true;
        } else {
            $_SESSION['error_registro'] = "ocurrio un error en la base de datos.";
            return false;
        }
    }

    public function validarHash($hash)
    {
        $sql= "SELECT * FROM usuarios WHERE hash = '$hash'";
        $result = $this->database->execute($sql);

        if($result->num_rows==0){
            $_SESSION["error_hash"]="codigo hash incorrecto";
            return false;
        }

        $usuario = $result->fetch_assoc();

        if ($usuario['estado'] == 'inactivo') {

            $idUsuario = $usuario['id'];
            $updateQuery = "UPDATE usuarios SET estado = 'activo' WHERE id = $idUsuario";
            $success= $this->database->execute($updateQuery);
            if ($success) {
                $_SESSION['validacion_exitosa']="la validacion fue exitosa";
                return true;
            } else {
                return false;
            }

        } else {
            $_SESSION['error_hash'] = "El usuario ya está activo.";
            return false;
        }
    }

    public function validarLogin($usuario, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$usuario' AND contrasena LIKE '$password' AND estado LIKE'activo'";

        $result = $this->database->execute($sql);

        if ($result->num_rows == 1) {

            $usuario = $result->fetch_assoc();

            $_SESSION["user"] = $usuario["nombre_de_usuario"];
            //supongo que para las consultas lo usaremos mas adelante
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol'];

            return true;
        } else {
            $sql = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$usuario' AND estado LIKE'activo'";

            $result = $this->database->execute($sql);

            $_SESSION['error_login'] = ($result->num_rows == 1) ? "contraseña incorrecta" : "usuario inexistente o inactivo";

            return false;
        }
    }

    private function validarCamposQueNoEstenVaciosYTengaLaMismaContraseña
    ($nombre_de_usuario,$nombre,$anio_de_nacimiento,$email,$contrasena,$repetir_contrasena,$sexo,$pais,$ciudad)
    {
        if(empty(trim($nombre_de_usuario)) || empty(trim($nombre)) || empty(trim($anio_de_nacimiento)) ||
            empty(trim($email)) || empty(trim($contrasena)) || empty(trim($repetir_contrasena)) ||
            empty(trim($sexo)) || empty(trim($pais)) || empty(trim($ciudad)) || !(strcmp($contrasena,$repetir_contrasena)==0)){
            return true;
        }
        return false;
    }

    private function validarNombreUsuario($nombre_de_usuario){
        $sql = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$nombre_de_usuario'";
        $result = $this->database->execute($sql);

        return $result->num_rows == 1;
    }

    private function validarContrasena($contrasena){

        return strlen($contrasena)<=8;
    }

    private function validarQueSoloTengaCaracteres($nombre)
    {
        return !(ctype_alpha($nombre));
    }

    private function obtenerHash()
    {
        $flag=false;

        while(!$flag){

            $hash= rand(1,1000);

            $sql= "SELECT * FROM usuarios WHERE hash='$hash'";
            $result = $this->database->execute($sql);

            if($result->num_rows==0){
                $flag=true;
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

    private function esUnaImagenValida()
    {
        if(isset($_FILES["foto"]) &&
            $_FILES["foto"]["error"] == 0 &&
            $_FILES["foto"]["size"] > 0) {
            $extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
            if ($extension == "png" || $extension == 'jpg' || $extension == 'jpeg') {
                return true;
            }
        }else{
            return false;
        }
    }
}
