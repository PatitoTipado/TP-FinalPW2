<?php

class LoginController
{
    private $presenter;
    private $model;
    public function __construct($presenter, $model)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function show()
    {
        if (isset($_SESSION['user'])) {
            header("location:/home");
            exit();
        }
        $this->presenter->show('login',$_SESSION);
        unset($_SESSION["error_login"]);
    }

    public function validarLogin()
    {
        if (isset($_POST["username"]) && isset($_POST["password"])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $data=$this->model->validarLogin($username,$password);
            
            if($data['result']){
                header("location:/home");
                $_SESSION['id_usuario'] = $data['id_usuario'];
                $_SESSION['rol'] =$data['rol'];
                $_SESSION['user']=$data['user'];
                unset($_SESSION["error_login"]);
                exit();
            }else{
                $_SESSION['error_login']=$data['error'];
                header("location:/login");
                exit();
            }
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
