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

    public function verPregunta()
    {
        $id = $_GET['id'];

        if (!isset($_SESSION['user'])) {
            header("location:/");
        }
        $data['pregunta'] = $this->model->obtenerPregunta($id);
        $this->presenter->show('pregunta', $data);
    }

    public function modificar()
    {
        $id = $_GET['id'];

        $pregunta = isset($_POST['pregunta']) ? $_POST['pregunta'] : "";
        $opcion1 = isset($_POST['opcion1']) ? $_POST['opcion1'] : "";
        $opcion2 = isset($_POST['opcion2']) ? $_POST['opcion2'] : "";
        $opcion3 = isset($_POST['opcion3']) ? $_POST['opcion3'] : "";
        $opcionCorrecta = isset($_POST['opcionCorrecta']) ? $_POST['opcionCorrecta'] : "";

        $this->model->modificarPreguntaConOpciones($id, $pregunta, $opcion1, $opcion2, $opcion3, $opcionCorrecta);
<
        header("location:/verPreguntas");
        exit();
    }
}
