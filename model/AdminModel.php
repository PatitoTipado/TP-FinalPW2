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
            $sql = "SELECT COUNT(*) AS cantidad FROM usuarios WHERE fecha_registro BETWEEN '$inicio' AND '$fin' AND rol = 'jugador'";
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
        $query = "SELECT sexo AS sexo, COUNT(*) AS cantidad FROM usuarios WHERE rol = 'jugador' GROUP BY sexo";

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
        // Si ambos campos están vacíos, devolver todas las partidas agrupadas por estado
        if (empty($inicio) && empty($fin)) {
            return $this->obtenerPartidasPorEstado();
        }

        // Construir las condiciones del WHERE dinámicamente
        $conditions = [];

        if (!empty($inicio)) {
            $conditions[] = "fecha_de_partida >= '$inicio'";
        }

        if (!empty($fin)) {
            $conditions[] = "fecha_de_finalizacion <= '$fin'";
        }

        // Unir las condiciones con "AND"
        $whereClause = implode(' AND ', $conditions);

        // Crear la consulta completa
        $query = "SELECT estado, COUNT(*) AS cantidad FROM partidas";
        if (!empty($whereClause)) {
            $query .= " WHERE $whereClause";
        }
        $query .= " GROUP BY estado";

        // Ejecutar la consulta
        $result = mysqli_query($this->database->getConn(), $query);

        if (!$result) {
            throw new Exception("Error en la consulta SQL: " . mysqli_error($this->database->getConn()));
        }

        // Transformar los resultados en un array
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }



}