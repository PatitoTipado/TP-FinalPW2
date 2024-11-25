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
        $this->validarEditor();

        $this->presenter->show('agregarPregunta');
    }

    public function agregar()
    {
        $this->validarEditor();
        if (isset($_POST["pregunta"]) && isset($_POST["opcion1"]) && isset($_POST["opcion2"]) && isset($_POST["opcion3"]) && isset($_POST["opcionCorrecta"]) && isset($_POST['nivel'])) {
            $pregunta = $_POST['pregunta'];
            $opcion1 = $_POST['opcion1'];
            $opcion2 = $_POST['opcion2'];
            $opcion3 = $_POST['opcion3'];
            $opcionCorrecta = $_POST['opcionCorrecta'];
            $nivel = $_POST['nivel'];

            $result = $this->model->agregarPreguntaConOpciones($pregunta, $nivel, $opcion1, $opcion2, $opcion3, $opcionCorrecta);

            if (is_string($result)) {
                $_SESSION["error_al_agregar"] = "La pregunta ya existe";
                header("Location: /agregarPregunta");
            } else {
                header("Location: /verPreguntas");
                exit();
            }
        }
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
