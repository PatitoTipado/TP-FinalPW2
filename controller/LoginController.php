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
        if (!isset($_SESSION['user']) && !isset($_SESSION['rol'])) {
            $this->presenter->show('login',$_SESSION);
            unset($_SESSION["error_login"]);
            return;
        }
        if($_SESSION['rol']=='administrador'){
            header("location:/admin");
            exit();
        }

        if($_SESSION['rol']=='jugador'){
            header("location:/home");
            exit();
        }
    }

    public function validarLogin()
    {
        if (isset($_POST["username"]) && isset($_POST["password"])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $data=$this->model->validarLogin($username,$password);
            
            if($data['result']){
                $_SESSION['id_usuario'] = $data['id_usuario'];
                $_SESSION['rol'] =$data['rol'];
                $_SESSION['user']=$data['user'];
                $_SESSION['puntaje_maximo']=$data['puntaje_maximo'];
                unset($_SESSION["error_login"]);
            }else{
                $_SESSION['error_login']=$data['error'];
            }
            //que se encargue el login de la vista asi no tengo que redireccionar aca
            header("location:/login");
            exit();

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
