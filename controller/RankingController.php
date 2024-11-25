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
        $this->validarJugador();

        $data['users'] = $this->rankingModel->getNameAndScoreByPositionOfUsers();
        $this->presenter->show('ranking', $data);
    }

    //preguntar si borrar
    private function obtenerUsuario($id)
    {
        $this->validarJugador();

        $data = $this->rankingModel->obtenerUsuario($id);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontraron usuarios";
        }

        return $data;
    }

    //preguntar si borrar
    public function verUsuario()
    {
        $id = $_GET['id'];

        $this->validarJugador();

        $data['user'] = $this->rankingModel->obtenerUsuario($id);
        $this->presenter->show('usuario', $data);
    }

    public function validarJugador()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
            exit();
        }

        if(!isset($_SESSION['rol']) || $_SESSION['rol']!= 'jugador'){
            header("location:/");
            exit();
        }

    }

}
