<?php

class AdminModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerJugadoresTotales()
    {
        // La consulta SQL
        $query = "SELECT estado, COUNT(*) AS cantidad FROM usuarios WHERE rol = 'jugador' GROUP BY estado";

        // Ejecutar la consulta
        $result = mysqli_query($this->database->getConn(), $query);

        // Comprobar si la consulta fue exitosa
        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($this->database->getConn()));
        }

        // Inicializar el arreglo donde se almacenarán los resultados
        $data = [];

        // Obtener los resultados como un arreglo asociativo
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row; // Añadir cada fila a $data
        }

        return $data; // Retornar los resultados como un arreglo de filas
    }

    public function filtrarUsuariosPorRangoDeFecha($inicio, $fin)
    {
        if ($inicio <= $fin) {
            $sql = "SELECT COUNT(*) AS cantidad FROM usuarios WHERE fecha_registro BETWEEN '$inicio' AND '$fin' AND rol = 'jugador' GROUP BY estado";
            $data['result'] = false;

            $result = $this->database->query($sql);

            if ($result) {
                $data['result']=true;
                $data['cantidad'] = $result[0]['cantidad'] ?? 0;
            } else {
                $data['error'] = "Error al ejecutar la consulta.";
            }

            return $data;
        }

        $data['error'] = "La fecha de inicio no puede ser mayor que la fecha de fin.";

        return $data;
    }

    public function obtenerLaCantidadDeJugadoresTotales()
    {
        $sql = "SELECT COUNT(*) AS cantidad FROM usuarios WHERE rol = 'jugador'";
        $data['result'] = false;

        $result = $this->database->query($sql);

        if ($result) {
            return $result[0]['cantidad'] ?? 0;
        }

        return $data;
    }

    public function filtrarPorPaisYCiudad($pais, $ciudad)
    {
        $sql = "SELECT COUNT(*) AS cantidad FROM usuarios WHERE pais = '$pais' AND ciudad= '$ciudad' AND rol = 'jugador'";
        $data['result'] = false;

        $result = $this->database->query($sql);
        $data['cantidad'] = "no se encontraron resultados";

        if ($result) {
            $data['result']=true;
            $data['cantidad'] = $result[0]['cantidad'];
        }

        return $data;
    }

    public function filtrarPorSexo()
    {
        //pongo estado por que es mas facil escribir ne paantalla
        $query = "SELECT sexo AS estado, COUNT(*) AS cantidad FROM usuarios WHERE rol = 'jugador' GROUP BY sexo";

        $result = mysqli_query($this->database->getConn(), $query);

        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($this->database->getConn()));
        }

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row; // Añadir cada fila a $data
        }

        return $data;

    }

    public function filtrarPorEdad()
    {
        $fechaActual= $this->database->obtenerFechaActual();

        $data['menores']= $this->usuariosMenoresDe18($fechaActual);

        $data['adultos']=$this->usuariosEntre18y60($fechaActual);

        $data['jubilados']= $this->usuariosMayoresDe60($fechaActual);

        return $data;
    }

    public function usuariosMenoresDe18($fechaActual)
    {
        $sql = "SELECT COUNT(*) AS cantidad 
        FROM usuarios 
        WHERE rol = 'jugador' 
        AND YEAR(CURDATE()) - anio_de_nacimiento < 18";

        $result = $this->database->query($sql);

        return $result[0]['cantidad'] ?? 0;
    }

    public function usuariosEntre18y60($fechaActual)
    {
        $sql = "SELECT COUNT(*) AS cantidad 
        FROM usuarios 
        WHERE rol = 'jugador' 
        AND YEAR(CURDATE()) - anio_de_nacimiento BETWEEN 18 AND 59";

        $result = $this->database->query($sql);

        return $result[0]['cantidad'] ?? 0;
    }

    public function usuariosMayoresDe60($fechaActual)
    {
        $sql = "SELECT COUNT(*) AS cantidad 
        FROM usuarios 
        WHERE rol = 'jugador' 
        AND YEAR(CURDATE()) - anio_de_nacimiento >= 60";

        $result = $this->database->query($sql);

        return $result[0]['cantidad'] ?? 0;
    }

    public function obtenerPartidasPorEstado()
    {
        $query = "SELECT estado, COUNT(*) AS cantidad FROM partidas GROUP BY estado";

        $result = mysqli_query($this->database->getConn(), $query);

        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($this->database->getConn()));
        }

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;

    }

    public function obtenerPartidasPorEstadoFiltradasPorFecha($inicio, $fin)
    {
        if (empty($inicio) && empty($fin)) {
            return $this->obtenerPartidasPorEstado();
        }

        $conditions = [];

        if (!empty($inicio)) {
            $conditions[] = "fecha_de_partida >= '$inicio'";
        }

        if (!empty($fin)) {
            $conditions[] = "fecha_de_finalizacion <= '$fin'";
        }

        $whereClause = implode(' AND ', $conditions);

        $query = "SELECT estado, COUNT(*) AS cantidad FROM partidas";
        if (!empty($whereClause)) {
            $query .= " WHERE $whereClause";
        }
        $query .= " GROUP BY estado";

        $result = mysqli_query($this->database->getConn(), $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    public function obtenerElTotalDePreguntasPorEstado()
    {
        $query = "SELECT estado AS estado, COUNT(*) AS cantidad FROM preguntas GROUP BY estado";

        $result = mysqli_query($this->database->getConn(), $query);

        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($this->database->getConn()));
        }

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;

    }

    public function obtenerPreguntasPorNivel()
    {
        $query = "SELECT nivel AS estado, COUNT(*) AS cantidad FROM preguntas GROUP BY estado";

        $result = mysqli_query($this->database->getConn(), $query);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    public function obtenerPorcentajeDeRespuesta()
    {
        $cantidadAparicionesTotal= $this->obtenerDeTodasLasPreguntasCuantasVecesAparecio();
        $cantidadVecesCorrectas=$this->obtenerDeTodasLasPreguntasCuantasVecesSeRespondio();

        $data ['correctas']= ($cantidadVecesCorrectas /$cantidadAparicionesTotal) *100;
        $data ['incorrectas']=100 - $data['correctas'];

        return $data;
    }

    private function obtenerDeTodasLasPreguntasCuantasVecesAparecio()
    {

        $query = "SELECT SUM(cantidad_apariciones) AS total FROM preguntas
                  WHERE cantidad_apariciones IS NOT NULL AND cantidad_apariciones != ''";

        $result = $this->database->execute($query);

        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($this->database->getConn()));
        }

        $row = $result->fetch_assoc();

        return $row['total'];
    }

    private function obtenerDeTodasLasPreguntasCuantasVecesSeRespondio()
    {

        $query = "SELECT SUM(cantidad_veces_respondidas) AS total FROM preguntas
                  WHERE cantidad_veces_respondidas IS NOT NULL AND cantidad_veces_respondidas != ''";

        $result = $this->database->execute($query);

        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($this->database->getConn()));
        }

        $row = $result->fetch_assoc();

        return $row['total'];
    }



}