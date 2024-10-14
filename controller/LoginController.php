<?php

class LoginController
{
    private $presenter;
    private $model;
    public function __construct( $presenter,$model)
    {
        $this->model=$model;
        $this->presenter = $presenter;
    }

    public function show()
    {
        $this->presenter->show('login',[]);
    }

    public function validarLogin()
    {
        if (isset($_POST["username"])&& isset($_POST["password"])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            //pegarle al modelo para validar que el usuario sea correcto
            if($this->model->validarLogin($username,$password)){
                $_SESSION['user']= $username;
                header("location:/home");
            }else{
                header("location:/login");
            }
            //si lo es redigir al home desde controler
            //si no recargar la pagina e imprimir contraseña o username incorrecto
        }

    }

    public function cerrarSesion()
    {
        session_unset();
        session_destroy();
        header("Location: /Login");

        exit();
    }



}