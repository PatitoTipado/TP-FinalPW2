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
        $this->validarEditor();
        $data['sugeridas'] = $this->model->obtenerPreguntasSugeridas();
        $this->presenter->show('verSugeridas', $data);
    }

    public function aprobar() {
        $this->validarEditor();

        $id = $_GET['id'];

        $this->model->aprobarPreguntaSugerida($id);
        header("location:/verPreguntas");
        exit();
    }

    public function rechazar() {
        $this->validarEditor();

        $id = $_GET['id'];

        $this->model->rechazarPreguntaSugerida($id);
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
