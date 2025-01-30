<?php

class ChatController
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
        $id_usuario = $_SESSION['id_usuario'];
        $id_usuarioAChatear= $_GET['id']??'';

        $data['datos']= $this->model->obtenerTodaLaConversacion($id_usuario,$id_usuarioAChatear);
        $data= array_merge($_SESSION,$data);

        $this->presenter->show('chat',$data);
    }

    public function enviar()
    {
        $this->validarJugador();
        $id_usuario = $_SESSION['id_usuario'];
        $remitente=$_SESSION['user'];
        $data = json_decode(file_get_contents('php://input'), true);  // Decodifica el cuerpo JSON

        $id_usuarioEnviar= $data['id']??'';
        $contenido= $data['mensaje']??'';

        $this->model->enviarMensaje($id_usuario,$id_usuarioEnviar, $contenido,$remitente);
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