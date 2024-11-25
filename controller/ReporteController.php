<?php

class ReporteController
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
        $this->presenter->show('reporte',$_SESSION);
    }

    public function realizarReporte()
    {

        $id= $_SESSION['id_usuario'];
        $descripcion= $_POST['descripcion']?? "";
        $categoria = $_POST['categoria']??'';

        if($this->model->realizarReporte($id,$descripcion,$categoria)){
            $_SESSION['error_partida']= 'no se pudo realizar el reporte';
        }

        $_SESSION['error_partida']= '¡Gracias por tu reporte! Lo revisaremos pronto.';

        header('location:/home');
        exit();
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