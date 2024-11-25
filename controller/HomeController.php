<?php

class HomeController
{

    private $presenter;
    private $model;
    private $partidaModel;

    public function __construct($presenter, $model, $modelPartida)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->partidaModel = $modelPartida;
    }

    public function show()
    {
        $this->validarJugador();

        if(isset($_POST['estado']) && $_POST['estado']== 'finalizada'){
            $data= $this->obtenerPartidasFinalizadas();
            $data['finalizada']=true;
        }else{
            $data = $this->obtenerPartidasEnCurso();
            $data['en_curso']=true;
        }

        $this->presenter->show('home', $data);

        unset($_SESSION["error_partida"]);
        unset($_SESSION['not_found']);
        unset($data['partidas']);
        unset($_SESSION['id_pregunta']);
        unset($_SESSION['pregunta']);
        unset($_SESSION['opciones']);
        unset($_SESSION["id_partida_actual"]);
        unset($_SESSION['respuesta']);

    }

    private function obtenerPartidasEnCurso()
    {
        $id_usuario = $_SESSION['id_usuario'];

        $data = $this->partidaModel->obtenerPartidasEnCurso($id_usuario);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontraron partidas pasadas";
        }

        return $data;
    }

    private function obtenerPartidasFinalizadas()
    {
        $id_usuario = $_SESSION['id_usuario'];

        $data = $this->partidaModel->obtenerPartidasFinalizadas($id_usuario);

        if (!$data['result']) {
            $_SESSION['not_found'] = "no se encontraron partidas pasadas";
        }

        return $data;

    }

    public function validarJugador()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
            exit();
        }

        if(!isset($_SESSION['rol']) || $_SESSION['rol']!= 'jugador'){
            header("location:/");
            exit();
        }

    }

}
