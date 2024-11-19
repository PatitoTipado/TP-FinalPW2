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

        $this->presenter->show('listarUsuario',$_SESSION);
    }

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

    public function usuariosNuevos()
    {
        $this->validarAdministrador();
        $_SESSION['jugadores_nuevos'] = true;

        $fechaInicio= $_GET['fecha_inicio']?? '';
        $fechaFin= $_GET['fecha_fin'] ?? '';

        $dataCompleto=$_SESSION;

        if(!empty($fechaFin) && !empty($fechaInicio)){
            $data= $this->model->filtrarUsuariosPorRangoDeFecha($fechaInicio,$fechaFin);
            $data['cantidad_total']= $this->model->obtenerLaCantidadDeJugadoresTotales();
            $dataCompleto = array_merge($_SESSION, $data);
        }

        $this->presenter->show('listarUsuario', $dataCompleto);

        unset($_SESSION['jugadores_nuevos']);
    }

    public function filtroPais()
    {
        $this->validarAdministrador();
        $_SESSION['filtro_pais'] = true;

        $pais= $_GET['pais']?? '';
        $ciudad=$_GET['ciudad'] ??'';
        $dataCompleto=$_SESSION;

        if(!empty($pais) && !empty($ciudad)){
            $data= $this->model->filtrarPorPaisYCiudad($pais,$ciudad);
            $data['cantidad_total']= $this->model->obtenerLaCantidadDeJugadoresTotales();
            $dataCompleto = array_merge($_SESSION, $data);
        }

        $this->presenter->show('listarUsuario', $dataCompleto);

        unset($_SESSION['filtro_pais']);

    }

    public function filtroSexo()
    {
        $this->validarAdministrador();

        $_SESSION['filtro_sexo'] = true;

        // Obtener los jugadores agrupados por sexo
        $jugadoresPorSexo = $this->model->filtrarPorSexo();

        // Verifica si hay resultados antes de pasarlos a la vista
        if (!$jugadoresPorSexo) {
            die("No se encontraron jugadores.");
        }

        // Pasar los datos a la vista
        $data = ['jugadoresPorSexo' => $jugadoresPorSexo];

        // Combina datos con la sesión si es necesario
        $dataCompleto = array_merge($_SESSION, $data);

        // Pasar los datos a la vista
        $this->presenter->show('listarUsuario', $dataCompleto);

        unset($_SESSION['filtro_sexo']);
    }

    public function filtroEdad()
    {
        $this->validarAdministrador();

        $_SESSION['filtro_edad'] = true;

        // Obtener los jugadores agrupados por sexo
        $jugadoresPorEdad = $this->model->filtrarPorEdad();

        // Verifica si hay resultados antes de pasarlos a la vista
        if (!$jugadoresPorEdad) {
            die("No se encontraron jugadores.");
        }

        // Pasar los datos a la vista
        $data = ['jugadoresPorEdad' => $jugadoresPorEdad];

        // Combina datos con la sesión si es necesario
        $dataCompleto = array_merge($_SESSION, $data);

        // Pasar los datos a la vista
        $this->presenter->show('listarUsuario', $dataCompleto);

        unset($_SESSION['filtro_edad']);

    }

    public function listarTotalPartidas()
    {
        $this->validarAdministrador();

        $_SESSION['partidas_totales'] = true;

        $partidasPorEstado = $this->model->obtenerPartidasPorEstado();

        if (!$partidasPorEstado) {
            die("No se encontraron partidas.");
        }

        $data = ['partidasPorEstado' => $partidasPorEstado];

        $dataCompleto = array_merge($_SESSION, $data);

        $this->presenter->show('listarPartidas', $dataCompleto);

        unset($_SESSION['partidas_totales']);
    }

    public function listarPartidasPorFecha()
    {
        $this->validarAdministrador();

        $inicio=$_GET['fecha_inicio'];
        $fin= $_GET['fecha_fin'];

        $_SESSION['partidas_totales'] = true;

        $partidasPorEstado = $this->model->obtenerPartidasPorEstadoFiltradasPorFecha($inicio,$fin);

        if (!$partidasPorEstado) {
            die("No se encontraron partidas.");
        }

        $data = ['partidasPorEstado' => $partidasPorEstado];

        $dataCompleto = array_merge($_SESSION, $data);

        $this->presenter->show('listarPartidas', $dataCompleto);

        unset($_SESSION['partidas_totales']);
    }

    public function listarPreguntas()
    {
        $this->validarAdministrador();

        $_SESSION['preguntas'] = true;

        $preguntasPorEstado = $this->model->obtenerElTotalDePreguntasPorEstado();

        if (!$preguntasPorEstado) {
            die("No se encontraron partidas.");
        }

        $data = ['preguntasPorEstado' => $preguntasPorEstado];

        $dataCompleto = array_merge($_SESSION, $data);

        $this->presenter->show('listarPreguntas', $dataCompleto);

        unset($_SESSION['preguntas']);

    }

    public function preguntasPorNivel()
    {
        $this->validarAdministrador();

        $_SESSION['preguntas'] = true;

        $preguntasPorEstado = $this->model->obtenerPreguntasPorNivel();

        if (!$preguntasPorEstado) {
            die("No se encontraron partidas.");
        }

        $data = ['preguntasPorNivel' => $preguntasPorEstado];

        $dataCompleto = array_merge($_SESSION, $data);

        $this->presenter->show('listarPreguntas', $dataCompleto);

        unset($_SESSION['preguntas']);

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