<?php

class RankingController
{

    private $presenter;
    private $model;
    private $rankingModel;

    public function __construct($presenter, $model, $rankingModel)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->rankingModel = $rankingModel;
    }

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
        }
        $data['users'] = $this->rankingModel->getNameAndScoreByPositionOfUsers();
        $this->presenter->show('ranking', $data);
    }

    private function obtenerUsuario($id)
    {

        $data = $this->rankingModel->obtenerUsuario($id);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontraron usuarios";
        }

        return $data;
    }

    public function verUsuario()
    {
        $id = $_GET['id'];

        if (!isset($_SESSION['user'])) {
            header("location:/");
        }
        $data['user'] = $this->rankingModel->obtenerUsuario($id);
        $this->presenter->show('usuario', $data);
    }
}
