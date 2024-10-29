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
        if (!isset($_SESSION['user']) ){
            header("location:/");
            exit();
        }

        if(!isset($_SESSION['id_partida_actual'])){
            header("location:/home");
            exit();

        }

        //deberiamos tener el timeut aca de que inicio que aparecio esta pregunta ? lol

        //si tiene seteado algo en id_pregunta y opciones tengo que revisar si salio de la partida
        //como tal avisarle que por salirse en medio de la una partida que perdera por tiempo volver
        //y luego validar en la partida que perdio por tiepo si lo hizo o mostrar la preguna
        $this->presenter->show('partida', $_SESSION);

    }

    public function jugarNuevaPartida()
    {

        $id_jugador= $_SESSION['id_usuario'];

        $_SESSION['id_partida_actual']= $this->model->iniciarNuevaPartida($id_jugador);

        if(!$_SESSION['id_partida_actual']) {

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
        $id_jugador=$_SESSION['id_usuario'];
        $id_partida=$_SESSION['id_partida_actual'];
        $_SESSION['respuesta_usuario']=$respuesta;

        if($this->model->validarRespuesta($respuesta,$id_pregunta,$id_jugador,$id_partida)){
            $this->vistaGanador();
        }else{
            $this->vistaPerdedor();
        }

    }

    public function reanudar()
    {
        $id_partida = $_GET['id_partida'];
        $id_jugador = $_SESSION['id_usuario'];

        if ($this->model->isPartidaValida($id_partida, $id_jugador)) {

            $data = $this->model->obtenerDataPartida($id_partida);

            $_SESSION['id_pregunta'] = $data['id_pregunta'];
            $_SESSION['pregunta'] = $data['pregunta'];
            $_SESSION['opciones'] = $data['opciones'];

            $_SESSION['id_partida_actual']=$id_partida;

            header("location: /partida/show");
            exit();
        }

        $_SESSION['error_partida']="error al continuar una partida";

        header("location: /home");
        exit();
    }

    public function vistaPerdedor()
    {
        $_SESSION['respuesta']=$this->model->obtenerRespuestaCorrecta($_SESSION['id_pregunta']);
        $this->presenter->show('perdedor', $_SESSION);

        unset($_SESSION['id_pregunta']);
        unset($_SESSION['pregunta']);
        unset($_SESSION['opciones']);
        unset($_SESSION["id_partida_actual"]);
        unset($_SESSION['respuesta']);

    }

    public function vistaGanador()
    {
        if(isset($_SESSION['respuesta_usuario']) &&
            $_SESSION['respuesta_usuario']!=$this->model->obtenerRespuestaCorrecta($_SESSION['id_pregunta'])){
            header("location:/partida/vistaPerdedor");
            exit();
        }
        $this->presenter->show('ganador', $_SESSION);
    }

}
