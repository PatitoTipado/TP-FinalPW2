<?php

class VerReportesController
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
        $data['reportes'] = $this->model->obtenerPreguntasReportadas();
        $this->presenter->show('verReportes', $data);
    }

    public function aprobar() {
        $id = $_GET['id'];

        $this->model->aprobarPreguntaReportada($id);
        header("location:/verPreguntas");
        exit();
    }

    public function eliminar() {
        $id = $_GET['id'];

        $this->model->eliminarPreguntaReportada($id);
        header("location:/verSugeridas");
        exit();
    }
}
