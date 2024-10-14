<?php
class MysqlDatabase
{
    private $conn;
    public function __construct($host, $port, $username, $password, $database)
    {
        $this->conn = mysqli_connect($host, $username, $password, $database, $port);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            die("Error en la consulta SQL: " . mysqli_error($this->conn));
        }
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function execute($sql)
    {
        return  mysqli_query($this->conn, $sql);
    }

    public function getConn()
    {
        return $this->conn;
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }
}
