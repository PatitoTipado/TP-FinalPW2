<?php

class HomeController
{

    private $presenter;
    private $model;
    private $partidaModel;

    public function __construct($presenter, $model,$modelPartida)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->partidaModel=$modelPartida;
    }

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
        }
        $data= $this->obtenerPartidas();

        $dataCompleto = array_merge($data, $_SESSION);

        $this->presenter->show('home', $dataCompleto);
        unset($_SESSION["error_partida"]);
        unset($data['partidas']);

    }

    private function obtenerPartidas()
    {
        $id_usuario=$_SESSION['id_usuario'];

        return $this->partidaModel->obtenerPartidas($id_usuario);
    }

}
