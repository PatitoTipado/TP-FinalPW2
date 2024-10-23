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
        if (!isset($_SESSION['user']) && isset($_SESSION['id_pregunta']) && isset($_SESSION['opciones']) ){
            header("location:/");
            exit();
        }
        $this->presenter->show('partida', $_SESSION);
    }

    public function jugarNuevaPartida()
    {

        $id_jugador= $_SESSION['id_usuario'];

        $_SESSION['id_partida_actual']= $this->model->iniciarNuevaPartida($id_jugador);

        if(!$_SESSION['id_partida_actual']) {
            //imprimir este error en el home
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

        if($this->model->validarRespuesta($respuesta,$id_pregunta,$id_jugador,$id_partida)){
            //crear vista para continuar setear algo en sesion para saber si puede continuar, si no no podra continuar
            // simplemente llamar al metodo continuar del home de partidas para que siga
            // necesitamos validar que pueda continuar las partidas del home, algo de magia vamos a pasar por alli
            // si llegaste hasta aqui podrias imprimir las partidas y de alli agarrar cosas para identificar la partida
            // a partir de su id de usuario, es una primera iteracion asi que no hay pedo
            $this->vistaGanador();
        }else{
            $this->vistaPerdedor();
        }

    }

    public function continuar()
    {
        //luego lo refactorizamos para poner un campo hidden
        // y validamos que exista la partida asi podemos continuar cualquier partida
        $id_partida =$_SESSION['id_partida_actual'];
        $id_jugador= $_SESSION['id_usuario'];

        if($this->model->isPartidaValida($id_partida,$id_jugador)){

            $data = $this->model->obtenerDataPartida($id_partida);

            $_SESSION['id_pregunta'] = $data['id_pregunta'];
            $_SESSION['pregunta'] = $data['pregunta'];
            $_SESSION['opciones'] = $data['opciones'];

            header("location: /partida/show");
            exit();
        }


        $_SESSION['error_partida']="error al continuar una partida";

        header("location: /home");
        exit();
    }

    public function vistaPerdedor()
    {
        //le pasamos la opcion correcta por sesion y luego la unseteamos ahi noma
        //si podes hacelo, me apresuro para llegar al continuar hacelo con el id de pregunta un obtener rta correcta(id)
        $this->presenter->show('perdedor', $_SESSION);
        unset($_SESSION['id_pregunta']);
        unset($_SESSION['pregunta']);
        unset($_SESSION['opciones']);
        unset($_SESSION["id_partida_actual"]);

    }

    public function vistaGanador()
    {
        $this->presenter->show('ganador', $_SESSION);
    }

}
