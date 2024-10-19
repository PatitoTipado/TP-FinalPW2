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

    //este metodo lo solemos usar cuando queremos que se cargue la vista con algun dato especial,
    //si solo tenemos la vista, con redireccionar estaremo bien (CREO) xd
    public function show()
    {
        $resultado = isset($_SESSION["resultado"]) ? $_SESSION["resultado"] : "";
        $vacio = isset($_SESSION["error_vacio"]) ? $_SESSION["resultado"] : "";
        $invalido = isset($_SESSION["invalido"]) ? $_SESSION["resultado"] : "";

        $this->presenter->show('registro', ['resultado' => $resultado, 'vacio' => $vacio, 'invalido' => $invalido]);
    }

    public function registrarUsuario()
    {
        //obtenemos los datos
        $nombre = $_POST['nombre'] ?? '';
        $anio_de_nacimiento = $_POST['anio'] ?? 0;
        $sexo = $_POST['sexo'] ?? '';
        $pais = $_POST['pais'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $repetir_contrasena = $_POST['repetir-contrasena'] ?? '';
        $nombre_de_usuario = $_POST['usuario'] ?? '';
        $foto = $_FILES['foto'] ?? '';

        //validaciones de cosas que no deben ir vacias
        if ($this->validarCampos($nombre_de_usuario, $nombre, $anio_de_nacimiento, $email, $contrasena, $repetir_contrasena, $sexo, $pais, $ciudad)) {
            $_SESSION["error_vacio"] = "ningun parametro puede estar vacio o las contraseñas no ser iguales.";
        }
        //agrega foto
        if ($this->model->registrarUsuario($nombre_de_usuario, $nombre, $anio_de_nacimiento, $email, $contrasena, $sexo, $pais, $ciudad, $foto)) {
            header("location:/login");
            unset($_SESSION["error_vacio"]);
            unset($_SESSION["invalido"]);
            unset($_SESSION["resultado"]);
        } else {
            $_SESSION["resultado"] = $this->model->registrarUsuario($nombre_de_usuario, $nombre, $anio_de_nacimiento, $email, $contrasena, $sexo, $pais, $ciudad, $foto);
            $_SESSION["invalido"] = "el registro es invalido";
        }

        //añadi interaccion con el usuario si por alguna razon la base de datos no agrega lo que debe agregar
        //por limitacion de la misma ej un correo o usuario repetido
    }

    private function validarCampos($nombre_de_usuario, $nombre, $anio_de_nacimiento, $email, $contrasena, $repetir_contrasena, $sexo, $pais, $ciudad)
    {
        if (
            empty(trim($nombre_de_usuario)) || empty(trim($nombre)) || empty(trim($anio_de_nacimiento)) ||
            empty(trim($email)) || empty(trim($contrasena)) || empty(trim($repetir_contrasena)) ||
            empty(trim($sexo)) || empty(trim($pais)) || empty(trim($ciudad)) || !(strcmp($contrasena, $repetir_contrasena) == 0)
        ) {
            return true;
        }
        return false;
    }
}
