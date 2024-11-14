<?php

class ModificarPreguntaController
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

        $data['preguntas'] = $this->model->obtenerPreguntas();
        $data['opciones'] = "";

        $this->presenter->show('modificarPregunta', $data);
    }
}