<?php

class AgregarPreguntaController
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

    public function agregar() {
        $this->model->agregarPregunta();
    }
}