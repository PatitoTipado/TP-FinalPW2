<?php

class AdminController
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
        $this->validarAdministrador();

        $this->presenter->show('admin',$_SESSION);
    }

    public function listarUsuarios()
    {
        $this->validarAdministrador();

        $this->presenter->show('listarUsuario',$_SESSION);
    }

//    public function jugadoresTotales()
//    {
//
//        $this->validarAdministrador();
//        $_SESSION['jugadores_totales']=true;
//
//        $data = $this->model->obtenerJugadoresTotales();
//
//        $dataCompleto= array_merge($_SESSION,$data);
//
//        $this->presenter->show('listarUsuario',$dataCompleto);
//        unset($_SESSION['jugadores_totales']);
//    }

    public function jugadoresTotales()
    {
        $this->validarAdministrador();
        $_SESSION['jugadores_totales'] = true;

        // Obtener los jugadores agrupados por estado
        $jugadoresPorEstado = $this->model->obtenerJugadoresTotales();

        // Verifica si hay resultados antes de pasarlos a la vista
        if (!$jugadoresPorEstado) {
            die("No se encontraron jugadores.");
        }

        // Pasar los datos a la vista
        $data = ['jugadoresPorEstado' => $jugadoresPorEstado];

        // Combina datos con la sesión si es necesario
        $dataCompleto = array_merge($_SESSION, $data);

        // Pasar los datos a la vista
        $this->presenter->show('listarUsuario', $dataCompleto);

        unset($_SESSION['jugadores_totales']);
    }



    public function validarAdministrador()
    {
        if (!isset($_SESSION['user'])) {
            header("location:/");
            exit();
        }

        if(!isset($_SESSION['rol']) || $_SESSION['rol']!= 'administrador'){
            header("location:/");
            exit();
        }
    }
}