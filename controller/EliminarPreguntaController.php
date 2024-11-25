<?php

class EliminarPreguntaController
{
    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function eliminar()
    {
        $this->validarEditor();

        $id = $_GET['id'];
        $this->model->eliminarPreguntaConOpciones($id);
        header("location:/verPreguntas");
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
