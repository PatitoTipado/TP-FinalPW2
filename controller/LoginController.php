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
        //cada vez que cargo la pagina me saca el error feo
        unset($_SESSION["error_login"]);
    }

    public function validarLogin()
    {
        if (isset($_POST["username"]) && isset($_POST["password"])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if($this->model->validarLogin($username,$password)){
                header("location:/home");
                unset($_SESSION["error_login"]);
                exit();
            }else{
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
