<?php

class VerPreguntasController
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
        $this->presenter->show('verPreguntas', $data);
    }

    public function verPregunta()
    {
        $id = $_GET['id'];

        if (!isset($_SESSION['user'])) {
            header("location:/");
        }
        $data['pregunta'] = $this->model->obtenerPregunta($id);
        $this->presenter->show('pregunta', $data);
    }
}
