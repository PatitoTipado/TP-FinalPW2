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
        $this->validarEditor();
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
    public function validarEditor()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
            exit();
        }

        if(!isset($_SESSION['rol']) || $_SESSION['rol']!= 'editor'){
            header("location:/");
            exit();
        }

    }

}
