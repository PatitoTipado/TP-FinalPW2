<?php

class PartidaController
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
        $this->presenter->show('partida', $_SESSION);
    }

    public function jugarNuevaPartida()
    {

        $id_jugador= $_SESSION['id_usuario'];

        $_SESSION['id_partida_actual']= $this->model->iniciarNuevaPartida($id_jugador);

        if($_SESSION['id_partida_actual']==false) {
            $_SESSION['error_partida']="error al crear una partida";
            header("location: /home");
            exit();
        }

        $id_partida = $_SESSION['id_partida_actual'];

        $data = $this->model->obtenerDataPartida($id_partida);

        $_SESSION['id_pregunta'] = $data['id_pregunta'];
        $_SESSION['pregunta'] = $data['pregunta'];
        $_SESSION['opciones'] = $data['opciones'];

        header("location: /partida/show");
        exit();
    }

    public function validarRespuesta()
    {

        $respuesta=$_POST['respuesta'];
        $id_pregunta=$_SESSION['id_pregunta'];

        if($this->model->validarRespuesta($respuesta,$id_pregunta)){
            echo "sos crakc";
        }else{
            echo "no sos crack";
        }

    }

}
