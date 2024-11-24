<?php

class AdminController
{

    private $presenter;
    private $model;
    private $pdfGenerator;

    public function __construct($presenter, $model,$pdfGenerator)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->pdfGenerator=$pdfGenerator;
    }

    public function show()
    {
        $this->validarAdministrador();

        $this->presenter->show('listarUsuario',$_SESSION);
    }

    //JUGADORES

    public function jugadoresTotales()
    {
        $this->validarAdministrador();

        $data['titulo']= 'Reporte jugadores totales';
        $data['tipo']= 'estado de jugador';
        $data['filtro_aplicado']="obtener todos los jugadores por estado";
        $data['row'] = $this->model->obtenerJugadoresTotales();
        $data['generar']= true;

        $dataCompleto = array_merge($_SESSION, $data);

        $this->presenter->show('listarUsuario', $dataCompleto);

    }

    //se que lo mejor es modularizarlo e imprimir pdf por usuario pregunta y partida
    //pero estoy apretado y creo que me sale mas barato

    public function generarPdf()
    {
        $this->validarAdministrador();

        //atributos comunes
        $data['titulo']= $_POST['titulo']?? '';
        $data['tipo']= $_POST['tipo']??'';
        $data['filtro_aplicado']= $_POST['filtro']??'';

        //atributos validadores
        $inicio= $_POST['inicio']??'';
        $fin= $_POST['fin'];
        $pais= $_POST['pais'];
        $ciudad=$_POST['ciudad'];
        $isPregunta=$_POST['isPregunta']??false;

        //vizualizar en el generador cada parte
        $data['filtro_edad']= $_POST['filtro_edad'] ??false;
        $data['porcentaje']=$_POST['porcentaje']??false;
        $base64Image = $_POST['chart_image'] ?? '';

        if (!empty($base64Image)) {
            // Decodificar la imagen Base64 y guardarla como archivo
            $carpetaImagenes = $_SERVER['DOCUMENT_ROOT'] . '/public/';
            $nombreArchivo = 'grafico.PNG';

            // Separar el encabezado del contenido Base64 (si existe un encabezado)
            $imageData = explode(',', $base64Image);
            $base64Content = isset($imageData[1]) ? $imageData[1] : $imageData[0];

            // Decodificar y guardar el contenido de la imagen
            $rutaArchivo = $carpetaImagenes . $nombreArchivo;
            file_put_contents($rutaArchivo, base64_decode($base64Content));

            // Guardar la ruta en el array de datos para usarla después
            $data['grafico'] = $rutaArchivo;
        }else{
            die("me mori llego vacio");
        }
        //para imprimir preguntas
        if($isPregunta){
            $data['row']= $this->obtenerDatosPorTituloDePregunta($data['titulo']);
            $this->pdfGenerator->generateAndRenderPdf('./view/reportePdfView.mustache', $data, 'total.pdf', 0);
            return;

        }

        //para partidas
        if(empty($inicio) && empty($fin) && $data['titulo'] == 'obtener todas las partidas filtradas por fecha'){
            $data ['row'] = $this->model->obtenerPartidasPorEstado();
            $data['estado']='filtro desde:  ' . $inicio . ' hasta: ' . $fin;
            $this->pdfGenerator->generateAndRenderPdf('./view/reportePdfView.mustache', $data, 'total.pdf', 0);
            return;
        }

        //para jugadores
        if($data['filtro_edad']){
            $data['menores']=$_POST['menores']??'';
            $data['adultos']= $_POST['adultos']??'';
            $data['jubilados']=$_POST['jubilados']??'';
        }

        if(!empty($inicio) && !empty($fin)){
            $data['row'] = $this->obtenerDatosPorTituloFiltradoPorFecha($data['titulo'],$inicio,$fin);
            //para que no salgan en partidas cuando obtengo por titulo
            if($data['titulo']!='obtener todas las partidas filtradas por fecha'){
                $data['cantidad_total'] = $this->model->obtenerLaCantidadDeJugadoresTotales();
            }

            $data['estado']='filtro desde:  ' . $inicio . ' hasta: ' . $fin;
            $this->pdfGenerator->generateAndRenderPdf('./view/reportePdfView.mustache', $data, 'total.pdf', 0);
            return;
        }

        if(!empty($pais) && !empty($ciudad)){
            $data['row'] = $this->obtenerDatosPorTituloFiltradoPorPaisYCiudad($data['titulo'],$pais,$ciudad);
            $data['cantidad_total'] = $this->model->obtenerLaCantidadDeJugadoresTotales();
            $data['estado']='El pais es:  ' . $pais . ' y su ciudad: ' . $ciudad;
            $this->pdfGenerator->generateAndRenderPdf('./view/reportePdfView.mustache', $data, 'total.pdf', 0);
            return;
        }

        $data['cantidad_total'] = $this->model->obtenerLaCantidadDeJugadoresTotales();
        $data['row'] = $this->obtenerDatosPorTitulo($data['titulo']);

        $this->pdfGenerator->generateAndRenderPdf('./view/reportePdfView.mustache', $data, 'total.pdf', 0);
    }

    public function usuariosNuevos()
    {
        $this->validarAdministrador();
        $data['fecha']=true;

        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';

        if (!empty($fechaFin) && !empty($fechaInicio)) {
            $data['row'] = $this->model->filtrarUsuariosPorRangoDeFecha($fechaInicio, $fechaFin);
            $data['cantidad_total'] = $this->model->obtenerLaCantidadDeJugadoresTotales();
            $data['filtro_aplicado'] = "Obtener jugadores nuevos por rango de fechas";
            $data['titulo'] = 'Reporte de jugadores nuevos';
            $data['tipo'] = 'Rango de fecha';
            $data['generar']= true;
            $data['estado']='filtro desde:  ' . $fechaInicio . ' hasta: ' . $fechaFin;
            $data['sobrante']= $data['cantidad_total']-$data['row']['cantidad'];
        }

        $data['inicio']=$fechaInicio;
        $data['fin']= $fechaFin;

        $this->presenter->show('listarUsuario', $data);

    }

    public function filtroPais()
    {
        $this->validarAdministrador();
        $data['mostrar_pais'] = true;

        $pais= $_GET['pais']?? '';
        $ciudad=$_GET['ciudad'] ??'';

        if(!empty($pais) && !empty($ciudad)){

            $data['row']= $this->model->filtrarPorPaisYCiudad($pais,$ciudad);
            $data['cantidad_total']= $this->model->obtenerLaCantidadDeJugadoresTotales();
            $data['filtro_aplicado'] = "obtener jugadores por pais y ciudad";
            $data['titulo'] = 'Reporte de jugadores por pais y ciudad';
            $data['tipo'] = 'pais y ciudad';
            $data['generar']= true;
            $data['estado']='pais:  ' . $pais . ' ciudad: ' . $ciudad;
            $data['sobrante']= $data['cantidad_total']-$data['row']['cantidad'];

        }

        $data['pais']=$pais;
        $data['ciudad']= $ciudad;

        $this->presenter->show('listarUsuario', $data);
    }

    public function filtroSexo()
    {
        $this->validarAdministrador();

        $data['titulo']= 'Obtener todos los jugadores filtrados por sexo';
        $data['tipo']= 'Filtro sexo';
        $data['filtro_aplicado']="Obtener todos los jugadores filtrados por sexo";
        $data['generar']= true;

        $data['row'] = $this->model->filtrarPorSexo();

        $this->presenter->show('listarUsuario', $data);

    }

    public function filtroEdad()
    {
        $this->validarAdministrador();

        $data['titulo']= 'Obtener todos los jugadores filtrados por edad';
        $data['tipo']= 'Filtro edad';
        $data['filtro_edad']="Obtener todos los jugadores filtrados por edad";
        $data['generar']= true;

        $data ['row'] = $this->model->filtrarPorEdad();

        $this->presenter->show('listarUsuario', $data);

    }

    //PARTIDAS

    public function listarTotalPartidas()
    {
        $this->validarAdministrador();

        $inicio=$_GET['fecha_inicio']?? '';
        $fin= $_GET['fecha_fin']?? '';
        $data['generar']= true;
        $data['titulo']= 'obtener todas las partidas filtradas por fecha';
        $data['tipo']= 'filtro partidas por fecha';
        $data['filtro_aplicado']="obtener todas las partidas las partidas";
        $data['fecha']=true;

        if(empty($inicio) || empty($fin)){

            $data ['row'] = $this->model->obtenerPartidasPorEstado();
            $this->presenter->show('listarPartidas', $data);
            exit();
        }

        $data ['row']=$this->model->obtenerPartidasPorEstadoFiltradasPorFecha($inicio,$fin);
        $data['inicio']=$inicio;
        $data['fin']=$fin;
        $this->presenter->show('listarPartidas', $data);
        exit();
    }

    //PREGUNTAS

    public function listarPreguntas()
    {
        $this->validarAdministrador();
        $data['generar']= true;
        $data['isPregunta']=true;

        $data['titulo']= 'obtener todas las preguntas';
        $data['tipo']= 'Preguntas por estado';
        $data['filtro_aplicado']="obtener todas las preguntas";

        $data ['row']= $this->model->obtenerElTotalDePreguntasPorEstado();

        $this->presenter->show('listarPreguntas', $data);

    }

    public function preguntasPorNivel()
    {
        $this->validarAdministrador();
        $data['generar']= true;
        $data['isPregunta']=true;

        $data['titulo']= 'Obtener preguntas por nivel';
        $data['tipo']= 'Preguntas por nivel';
        $data['filtro_aplicado']="Obtener preguntas por nivel";

        $data['row'] = $this->model->obtenerPreguntasPorNivel();

        $this->presenter->show('listarPreguntas', $data);
    }

    public function porcentajeDePreguntas()
    {
        $this->validarAdministrador();
        $data['generar']= true;
        $data['isPregunta']=true;
        $data['titulo']= 'Obtener porcentaje de preguntas';
        $data['tipo']= 'Tipos de porcentaje';
        $data['porcentaje']="porcentajes de preguntas";

        $data['row']= $this->model->obtenerPorcentajeDeRespuesta();

        $this->presenter->show('listarPreguntas', $data);
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

    private function obtenerDatosPorTitulo($titulo)
    {
        if($titulo == 'Reporte jugadores totales'){
            return $this->model->obtenerJugadoresTotales();
        }
        if($titulo =='Obtener todos los jugadores filtrados por sexo'){
            return $this->model->filtrarPorSexo();
        }
        if($titulo== 'Obtener todos los jugadores filtrados por edad'){
            return $this->model->filtrarPorEdad();
        }

        return null;
    }

    private function obtenerDatosPorTituloFiltradoPorFecha($titulo, $inicio, $fin)
    {
        if($titulo== 'Reporte de jugadores nuevos'){
            return $this->model->filtrarUsuariosPorRangoDeFecha($inicio, $fin);
        }

        if($titulo=='obtener todas las partidas filtradas por fecha'){

            return $this->model->obtenerPartidasPorEstadoFiltradasPorFecha($inicio,$fin);
        }

        return null;
    }

    private function obtenerDatosPorTituloFiltradoPorPaisYCiudad($titulo, $pais, $ciudad)
    {
        if($titulo== 'Reporte de jugadores por pais y ciudad'){
            return $this->model->filtrarPorPaisYCiudad($pais, $ciudad);
        }

        return null;

    }

    private function obtenerDatosPorTituloDePregunta($titulo)
    {
        if($titulo == 'Obtener porcentaje de preguntas'){
            return $this->model->obtenerPorcentajeDeRespuesta();
        }

        if($titulo== 'Obtener preguntas por nivel'){
            return $this->model->obtenerPreguntasPorNivel();
        }

        if($titulo=='obtener todas las preguntas'){
            return $this->model->obtenerElTotalDePreguntasPorEstado();
        }

        return null;
    }
}