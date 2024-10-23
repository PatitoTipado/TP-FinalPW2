<?php

class PerfilController
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
        if (!isset($_SESSION['user'])) {
            header("location:/");
        }
        $this->presenter->show('perfil', ['user' => $_SESSION['user'], 'foto' => $_SESSION['foto'], 'email' => $_SESSION['email'], 'pais' => $_SESSION['pais'], 'ciudad' => $_SESSION['ciudad'], 'nombre' => $_SESSION['nombre'], 'sexo' => $_SESSION['sexo']]);
    }
}
