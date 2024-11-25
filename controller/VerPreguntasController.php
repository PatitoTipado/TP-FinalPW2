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

        $this->validarEditor();

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
