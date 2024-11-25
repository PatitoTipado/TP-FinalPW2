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

        $id = $_GET['id'] ?? "";

        if (empty($id)) {
            $id = $_SESSION['id_usuario'];
        }

        $data = $this->obtenerDatosPerfil($id);

        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'editor') {
            $this->presenter->show('editor', $data);
        } elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador') {
            $this->presenter->show('administrador', $data);
        } else {
            $this->presenter->show('perfil', $data);
        }

        unset($_SESSION['not_found']);
        unset($data['partidas']);
    }

    private function obtenerDatosPerfil($id)
    {

        $data = $this->model->obtenerDatosDePerfil($id);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontro el usuario";
            return $data;
        }
        return $data;
    }

}
