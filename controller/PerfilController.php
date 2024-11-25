<?php

class PerfilController
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

        $id = $_GET['id'] ?? "";

        if (empty($id)) {
            $id = $_SESSION['id_usuario'];
        }

        $data = $this->obtenerDatosPerfil($id);

        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'editor') {
            $this->presenter->show('editor', $data);
        } elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador') {
            $this->presenter->show('administrador', $data);
        } else {
            $this->presenter->show('perfil', $data);
        }

        unset($_SESSION['not_found']);
        unset($data['partidas']);
    }

    private function obtenerDatosPerfil($id)
    {

        $data = $this->model->obtenerDatosDePerfil($id);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontro el usuario";
            return $data;
        }
        return $data;
    }

    public function sugerirPregunta()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
        }

        if(!isset($_SESSION['rol']) || $_SESSION['rol']!='jugador'){
            header("location:/");
        }

        $pregunta= $_POST["pregunta"]??'';
        $opcion3= $_POST["opcion3"]??'';
        $opcion1= $_POST["opcion1"]??'';
        $opcion2= $_POST["opcion2"]??'';
        $opcionCorrecta= $_POST["opcionCorrecta"]??'';
        $nivel= $_POST['nivel']??'';
        $id_categoria=$_POST['id_categoria']??'';


        if (!empty($pregunta) && !empty($opcion1) && !empty($opcion2) && !empty($opcion3)
            && !empty($opcionCorrecta) && !empty($id_categoria) && !empty($_SESSION['id_usuario'])) {

            if ($this->model->sugerirPregunta($pregunta, $nivel, $opcion1, $opcion2, $opcion3, $opcionCorrecta,$id_categoria,$_SESSION['id_usuario'])) {
                $_SESSION["resultado"] = "la pregunta pudo ser sugerida correctamente sera vista pronto para su aprobacion";
                header("Location:/perfil");
                exit();
            } else {
                $_SESSION["error"] = "error al sugerir pregunta pregunta existente o los campos estan vacios";
                header("Location:/perfil/sugerirPregunta");
                exit();
            }
        } else {
            $this->presenter->show('sugerir');
        }
    }

}
