<?php

class RegistroController
{

    private $model;
    private $presenter;

    public function __construct($presenter,$model)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    //este metodo lo solemos usar cuando queremos que se cargue la vista con algun dato especial,
    //si solo tenemos la vista, con redireccionar estaremo bien (CREO) xd
    public function show() {
        $this->presenter->show('registro' , []);
    }

    public function registrarUsuario()
    {
        if (isset($_POST["username"])&& isset($_POST["password"])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            header("location:/canciones");
            //pegarle al modelo para validar que el usuario sea correcto
            //si lo es redigir al home desde controler
            //si no recargar la pagina e imprimir contraseña o username incorrecto
        }

    }
}