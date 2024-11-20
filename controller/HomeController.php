<?php

class HomeController
{

    private $presenter;
    private $model;
    private $partidaModel;

    public function __construct($presenter, $model, $modelPartida)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->partidaModel = $modelPartida;
    }

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
            exit();
        }

        if(!isset($_SESSION['rol']) || $_SESSION['rol']!= 'jugador'){
            header("location:/");
            exit();
        }

        $data = $this->obtenerPartidas();
        $dataCompleto = array_merge($data, $_SESSION);

        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'editor') {
            $this->presenter->show('editor', $dataCompleto);
        } else {
            $this->presenter->show('home', $dataCompleto);
        }

        unset($_SESSION["error_partida"]);
        unset($_SESSION['not_found']);
        unset($data['partidas']);
        unset($_SESSION['id_pregunta']);
        unset($_SESSION['pregunta']);
        unset($_SESSION['opciones']);
        unset($_SESSION["id_partida_actual"]);
        unset($_SESSION['respuesta']);

    }

    private function obtenerPartidas()
    {
        $id_usuario = $_SESSION['id_usuario'];

        $data = $this->partidaModel->obtenerPartidas($id_usuario);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontraron partidas pasadas";
        }

        return $data;
    }
}
