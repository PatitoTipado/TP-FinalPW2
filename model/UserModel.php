<?php

class UserModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrarUsuario
    ($nombre_de_usuario, $nombre, $anio_de_nacimiento, $email, $contrasena,$repetir_contrasena, $sexo, $pais, $ciudad)
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

        if(isset($_FILES["foto"]) &&
            $_FILES["foto"]["error"] == 0 &&
            $_FILES["foto"]["size"] > 0) {
            $extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
            if ($extension == "PNG" || $extension == 'jpg' || $extension == 'jpeg') {
                $rutaImagen = $carpetaImagenes . $nombre_de_usuario . '.jpg';
                move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaImagen);
                $foto = 'public/' . $_FILES["foto"]["name"] . ".jpg";
            } else {
                $_SESSION['error_registro'] = "la imagen no se subio correctamente.";
                return false;
            }
        }

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha_actual = new DateTime();
        $fecha_registro = $fecha_actual->format('Y-m-d H:i:s');

        $sql = "INSERT INTO usuarios 
        (nombre_de_usuario, nombre, anio_de_nacimiento, email, contrasena, sexo, pais, ciudad,fecha_registro,imagen_url) 
        VALUES 
        ('$nombre_de_usuario', '$nombre', '$anio_de_nacimiento', '$email', '$contrasena', '$sexo', '$pais', '$ciudad', '$fecha_registro','$foto')";

        if ($this->database->execute($sql)) {
            return true;
        } else {
            $_SESSION['error_registro'] = "ocurrio un error en la base de datos.";
            return false;
        }

    }

    public function validarLogin($usuario, $password)
    {
        //TODO: VALIDAR  QUE ESTE VERIFICADO
        $sql = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$usuario' AND contrasena LIKE '$password'";

        $result = $this->database->execute($sql);

        if ($result->num_rows == 1) {

            $usuario = $result->fetch_assoc();

            $_SESSION["user"] = $usuario["nombre_de_usuario"];
            //supongo que para las consultas lo usaremos mas adelante
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol'];

            return true;
        } else {
            //TODO: VALIDAR QUE ESTE VERIFICADO
            $sql = "SELECT * FROM usuarios WHERE nombre_de_usuario = '$usuario'";

            $result = $this->database->execute($sql);

            $_SESSION['error_login'] = ($result->num_rows == 1) ? "contraseña incorrecta" : "usuario inexistente";

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

}