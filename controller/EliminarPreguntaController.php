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
        $id = $_GET['id'];
        $this->model->eliminarPreguntaConOpciones($id);
        header("location:/verPreguntas");
        exit();
    }
}
