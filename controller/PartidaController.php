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
        $this->validarJugador();

        if(!isset($_SESSION['id_partida_actual'])){
            header("location:/home");
            exit();

        }

        $this->presenter->show('partida', $_SESSION);

    }

    public function jugarNuevaPartida()
    {
        $this->validarJugador();

        $id_jugador= $_SESSION['id_usuario'];

        $id_partida =$this->model->iniciarNuevaPartida($id_jugador);

        if(!$id_partida) {
            $_SESSION['error_partida']="error al crear una partida";
            header("location: /home");
            exit();
        }

        $data = $this->model->obtenerDataPartida($id_partida);

        $_SESSION['tiempo']= $data['tiempo'];
        $_SESSION['id_pregunta'] = $data['id_pregunta'];
        $_SESSION['pregunta'] = $data['pregunta'];
        $_SESSION['opciones'] = $data['opciones'];
        $_SESSION['id_partida_actual']=$data['id_partida'];
        $_SESSION['color']= $data['color'];
        $_SESSION['categoria_nombre']=$data['categoria_nombre'];

        header("location: /partida/show");
        exit();
    }

    public function validarRespuesta()
    {
        $this->validarJugador();

        $respuesta=$_POST['respuesta'];
        $id_jugador=$_SESSION['id_usuario'];
        $_SESSION['respuesta_usuario']=$respuesta;

        $id_pregunta=$this->model->obtenerUltimaPreguntaDelUsuario($id_jugador);
        $id_partida=$this->model->obtenerUltimaPartidaDelUsuario($id_jugador);

        $result= $this->model->validarRespuesta($respuesta,$id_pregunta,$id_jugador,$id_partida);

        switch ($result) {
            case "ganador":
                $this->vistaGanador();
            break;
            case "perdedor":
                $this->vistaPerdedor();
                break;
            case "error":
                $this->vistaError();
                break;
        }

    }

    public function reanudar()
    {
        $this->validarJugador();

        $id_partida = $_GET['id_partida']?? '';
        $id_jugador = $_SESSION['id_usuario'];

        if($id_partida==''){
            $id_partida=$this->model->obtenerUltimaPartidaDelUsuario($id_jugador);
        }

        if ($this->model->isPartidaValida($id_partida, $id_jugador)) {

            $data = $this->model->obtenerDataPartida($id_partida);

            $_SESSION['tiempo']=$data['tiempo'];
            $_SESSION['id_pregunta'] = $data['id_pregunta'];
            $_SESSION['pregunta'] = $data['pregunta'];
            $_SESSION['opciones'] = $data['opciones'];
            $_SESSION['color']= $data['color'];
            $_SESSION['categoria_nombre']=$data['categoria_nombre'];
            $_SESSION['nivel']=$data['nivel'];

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
        $this->validarJugador();

        if(!isset($_SESSION['respuesta_usuario'])){
            header("location:/");
            exit();
        }

        $id_pregunta=$this->model->obtenerUltimaPreguntaDelUsuario($_SESSION['id_usuario']);

        $_SESSION['respuesta']=$this->model->obtenerRespuestaCorrecta($id_pregunta);
        $this->presenter->show('perdedor', $_SESSION);

        unset($_SESSION['id_pregunta']);
        unset($_SESSION['pregunta']);
        unset($_SESSION['opciones']);
        unset($_SESSION["id_partida_actual"]);
        unset($_SESSION['respuesta']);

    }

    public function vistaGanador()
    {

        $this->validarJugador();

        if(!isset($_SESSION['respuesta_usuario'])){
            header("location:/");
            exit();
        }

        $id_pregunta=$this->model->obtenerUltimaPreguntaDelUsuario($_SESSION['id_usuario']);

        if(isset($_SESSION['respuesta_usuario']) &&
            $_SESSION['respuesta_usuario']!=$this->model->obtenerRespuestaCorrecta($id_pregunta)){
            header("location:/partida/vistaPerdedor");
            exit();
        }
        $this->presenter->show('ganador', $_SESSION);
    }

    public function vistaError()
    {
        $this->validarJugador();

        $this->presenter->show('error', $_SESSION);
        unset($_SESSION['id_pregunta']);
        unset($_SESSION['pregunta']);
        unset($_SESSION['opciones']);
        unset($_SESSION["id_partida_actual"]);
        unset($_SESSION['respuesta']);

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
