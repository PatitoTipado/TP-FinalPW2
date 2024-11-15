<?php

class RegistroController
{

    private $model;
    private $presenter;

    public function __construct($presenter, $model)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function show() {
        if (isset($_SESSION['user'])) {
            header("location:/home");
            exit();
        }
        $this->presenter->show('registro' , $_SESSION);
        unset($_SESSION["error_registro"]);
    }

    public function registrarUsuario()
    {
        $nombre = $_POST['nombre'] ?? '';
        $anio_de_nacimiento = $_POST['anio'] ?? 0;
        $sexo = $_POST['sexo'] ?? '';
        $latitud = $_POST['latitud'] ?? '';
        $longitud = $_POST['longitud'] ?? '';
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $repetir_contrasena = $_POST['repetir-contrasena'] ?? '';
        $nombre_de_usuario = $_POST['usuario'] ?? '';

        if(!($repetir_contrasena===$contrasena)){
            $_SESSION['error_registro']="las contraseñas no coinciden o estan vacias";
            header("location: /registro");
            exit();
        }

        $carpetaImagenes = $_SERVER['DOCUMENT_ROOT'] . '/public/';

        if ($this->esUnaImagenValida()) {
            $rutaImagen = $carpetaImagenes . $nombre_de_usuario . '.jpg';
            move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaImagen);
            $foto = 'public/' . $nombre_de_usuario . ".jpg";
        } else {
            $_SESSION['error_registro'] = "la imagen no se subio correctamente o el formato no es el correcto (png-jpg-jpeg).";
            header("location: /registro");
            exit();
        }

        $registro=$this->model->registrarUsuario($nombre_de_usuario,$nombre,$anio_de_nacimiento,$email,$contrasena,$foto,$sexo,$latitud,$longitud);

        if($registro=="exitoso"){
            header("location:/registro/validarCorreo");
            unset($_SESSION["error_registro"]);
            exit();
        }else{
            $_SESSION['error_registro']=$registro;
            header("location: /registro");
            exit();
        }

    }

    public function validarCorreo()
    {
        if (isset($_SESSION['user'])) {
            header("location:/home");
            exit();
        }
        $this->presenter->show('validarCorreo' , $_SESSION);
        unset($_SESSION["result_hash"]);
    }

    public function validarHash()
    {
        $hash= $_POST['codigo'];

        $validarHash=$this->model->validarHash($hash);

        if(!$validarHash){
            header("location: /registro/validacionExitosa");
            exit();
        }else{
            $_SESSION['result_hash']=$validarHash;
            header("location: /registro/validarCorreo");
            exit();
        }
    }

    public function validacionExitosa()
    {
        if(!$_SESSION['result_hash']){
            $this->presenter->show('validacionExitosa' , $_SESSION);
            unset($_SESSION["result_hash"]);
        }else{
            header("location: /validacionCorreo");
            exit();
        }
    }

    private function esUnaImagenValida()
    {
        if (
            isset($_FILES["foto"]) &&
            $_FILES["foto"]["error"] == 0 &&
            $_FILES["foto"]["size"] > 0
        ) {
            $extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
            if ($extension == "png" || $extension == 'jpg' || $extension == 'jpeg') {
                return true;
            }
        } else {
            return false;
        }
    }


}
