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

        $data= $this->obtenerDatosPerfil();

        $dataCompleto = array_merge($data, $_SESSION);

        $this->presenter->show('perfil', $dataCompleto);
        unset($_SESSION['not_found']);

    }

    private function obtenerDatosPerfil()
    {

        $id= $_SESSION['id_usuario'];

        $data=$this->model->obtenerDatosDePerfil($id);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontro el usuario";
            return $data;
        }
        return $data;

    }
}
