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

        $id = $_GET['id'] ?? "";

        if (empty($id)) {
            $id = $_SESSION['id_usuario'];
        }

        $data = $this->obtenerDatosPerfil($id);
        $data2 = $this->obtenerPartidas($id);

        $dataCompleto = array_merge($data, $data2, $_SESSION);

        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'editor') {
            $this->presenter->show('editor', $dataCompleto);
        } elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador') {
            $this->presenter->show('administrador', $dataCompleto);
        } else {
            $this->presenter->show('perfil', $dataCompleto);
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

    private function obtenerPartidas($id)
    {

        $data = $this->partidaModel->obtenerPartidas($id);

        if (!$data['result']) {
            $_SESSION['not_found_partidas'] = "no se encontraron partidas pasadas";
        }

        return $data;
    }
}
