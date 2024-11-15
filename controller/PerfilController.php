<?php

class PerfilController
{

    private $presenter;
    private $model;
    private $partidaModel;

    public function __construct($presenter, $model, $partidaModel)
    {
        $this->model = $model;
        $this->partidaModel = $partidaModel;
        $this->presenter = $presenter;
    }

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
        }

        $data = $this->obtenerDatosPerfil();
        $data2 = $this->obtenerPartidas();

        $dataCompleto = array_merge($data, $data2, $_SESSION);

        $this->presenter->show('perfil', $dataCompleto);
        unset($_SESSION['not_found']);
        unset($data['partidas']);
    }

    private function obtenerDatosPerfil()
    {

        $id = $_SESSION['id_usuario'];

        $data = $this->model->obtenerDatosDePerfil($id);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontro el usuario";
            return $data;
        }
        return $data;
    }

    private function obtenerPartidas()
    {
        $id_usuario = $_SESSION['id_usuario'];

        $data = $this->partidaModel->obtenerPartidas($id_usuario);

        if (!$data['result']) {
            $_SESSION['not_found_partidas'] = "no se encontraron partidas pasadas";
        }

        return $data;
    }
}
