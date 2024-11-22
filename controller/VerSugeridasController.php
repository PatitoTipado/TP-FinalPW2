<?php

class VerSugeridasController
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
        $data['sugeridas'] = $this->model->obtenerPreguntasSugeridas();
        $this->presenter->show('verSugeridas', $data);
    }

    public function aprobar() {
        $id = $_GET['id'];

        $this->model->aprobarPreguntaSugerida($id);
        header("location:/verSugeridas");
        exit();
    }

    public function rechazar() {
        $id = $_GET['id'];

        $this->model->rechazarPreguntaSugerida($id);
        header("location:/verSugeridas");
        exit();
    }
}
