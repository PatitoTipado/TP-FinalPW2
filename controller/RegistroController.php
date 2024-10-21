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
        $this->presenter->show('registro' , $_SESSION);
        unset($_SESSION["error_registro"]);
    }

    public function registrarUsuario()
    {
        $nombre = $_POST['nombre'] ?? '';
        $anio_de_nacimiento = $_POST['anio'] ?? 0;
        $sexo = $_POST['sexo'] ?? '';
        $pais = $_POST['pais'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $repetir_contrasena = $_POST['repetir-contrasena'] ?? '';
        $nombre_de_usuario = $_POST['usuario'] ?? '';

        if($this->model->registrarUsuario($nombre_de_usuario,$nombre,$anio_de_nacimiento,$email,$contrasena,$repetir_contrasena,$sexo,$pais,$ciudad)){
            header("location:/registro/validarCorreo");
            unset($_SESSION["error_registro"]);
        }else{
            header("location: /registro");
        }

    }

    public function validarCorreo()
    {
        $this->presenter->show('validarCorreo' , $_SESSION);
        unset($_SESSION["error_hash"]);
    }

    public function validarHash()
    {
        $hash= $_POST['codigo'];
        if($this->model->validarHash($hash)){
            header("location: /registro/validacionExitosa");
        }else{
            header("location: /registro/validarCorreo");
        }
    }

    public function validacionExitosa()
    {
        if($_SESSION['validacion_exitosa']){
            $this->presenter->show('validacionExitosa' , $_SESSION);
            unset($_SESSION["validacion_exitosa"]);
        }else{
            header("location: /validacionCorreo");
        }
    }

}
