<?php

class EditorController
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

        $this->presenter->show('editor');
    }

    public function agregar()
    {
        $this->validarEditor();

        $pregunta= isset($_POST["pregunta"]) ?? '';
        $opcion1= isset($_POST["opcion1"]) ??'';
        $opcion2= isset($_POST["opcion2"]) ??'';
        $opcion3= isset($_POST["opcion3"]) ??'';
        $opcionCorrecta= isset($_POST["opcionCorrecta"]) ??'';
        $nivel= isset($_POST["nivel"]) ??'';


        if(empty($pregunta) || empty($opcionCorrecta) || empty($opcion1) || empty($opcion2) || empty($opcion3) || empty($nivel)){
            $this->presenter->show('agregarPregunta');
        }

        if (isset($_POST["pregunta"]) && isset($_POST["opcion1"]) && isset($_POST["opcion2"]) && isset($_POST["opcion3"]) && isset($_POST["opcionCorrecta"]) && isset($_POST['nivel'])) {
            $pregunta = $_POST['pregunta'];
            $opcion1 = $_POST['opcion1'];
            $opcion2 = $_POST['opcion2'];
            $opcion3 = $_POST['opcion3'];
            $opcionCorrecta = $_POST['opcionCorrecta'];
            $nivel = $_POST['nivel'];

            $result = $this->model->agregarPreguntaConOpciones($pregunta, $nivel, $opcion1, $opcion2, $opcion3, $opcionCorrecta);

            if (is_string($result)) {
                $_SESSION["mensaje"] = "hubo un error al agregar o ya existe o no completo todos los campos";
            } else {
                $_SESSION["mensaje"] = "la pregunta se agrego correctamente";
            }
            header("Location: /editor/agregar");
            exit();
        }
    }

    public function mostrarPreguntas()
    {
        $this->validarEditor();
        $data['preguntas'] = $this->model->obtenerPreguntas();
        $this->presenter->show('verPreguntas', $data);
    }

    public function verPregunta()
    {
        $id = $_GET['id']?? '';

        $this->validarEditor();

        if(empty($id)){
            header("Location: /editor");
            exit();
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
        $nivel = $_POST['nivel'];

        $this->model->modificarPreguntaConOpciones($id, $pregunta, $opcion1, $opcion2, $opcion3, $opcionCorrecta, $nivel);

        header("location:/editor/mostrarPreguntas");
        exit();
    }

    public function eliminar()
    {
        $this->validarEditor();

        $id = $_GET['id'];
        $this->model->eliminarPreguntaConOpciones($id);
        header("location:/editor/mostrarPreguntas");
        exit();
    }

    public function verSugeridas()
    {
        $this->validarEditor();
        $data['sugeridas'] = $this->model->obtenerPreguntasSugeridas();
        $this->presenter->show('verSugeridas', $data);
    }

    public function aprobarSugerida() {
        $this->validarEditor();

        $id = $_GET['id']??'';

        $this->model->aprobarPreguntaSugerida($id);
        header("location:/editor/mostrarPreguntas");
        exit();
    }

    public function rechazarSugerida() {
        $this->validarEditor();

        $id = $_GET['id']??'';

        $this->model->rechazarPreguntaSugerida($id);
        header("location:/editor/verSugeridas");
        exit();
    }

    public function verReportes()
    {
        $this->validarEditor();
        $data['reportes'] = $this->model->obtenerPreguntasReportadas();
        $this->presenter->show('verReportes', $data);
    }

    public function aprobarReporte() {
        $id = $_GET['id']??'';

        $this->model->aprobarPreguntaReportada($id);
        header("location:/verPreguntas");
        exit();
    }

    public function eliminarReporte() {
        $id = $_GET['id']??'';

        $this->model->eliminarPreguntaReportada($id);
        header("location:/verSugeridas");
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
