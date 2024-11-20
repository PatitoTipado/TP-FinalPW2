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

    public function agregar()
    {
        if (isset($_POST["pregunta"]) && isset($_POST["opcion1"]) && isset($_POST["opcion2"]) && isset($_POST["opcion3"]) && isset($_POST["opcionCorrecta"])) {
            $pregunta = $_POST['pregunta'];
            $opcion1 = $_POST['opcion1'];
            $opcion2 = $_POST['opcion2'];
            $opcion3 = $_POST['opcion3'];
            $opcionCorrecta = $_POST['opcionCorrecta'];

            $result = $this->model->agregarPreguntaConOpciones($pregunta, $opcion1, $opcion2, $opcion3, $opcionCorrecta);

            if (is_string($result)) {
                $_SESSION["error_al_agregar"] = "La pregunta ya existe";
                header("Location: /agregarPregunta");
            } else {
                header("Location: /verPreguntas");
                exit();
            }
        }
    }
}
