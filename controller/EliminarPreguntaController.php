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

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
        }

        $this->presenter->show('agregarPregunta');
    }

    public function eliminar()
    {
        $id = $_GET['id'];
        $this->model->eliminarPreguntaConOpciones($id);
        header("location:/verPreguntas");
        exit();
    }
}
