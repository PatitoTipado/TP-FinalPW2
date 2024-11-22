<?php

class VerReportesController
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
        if (!isset($_SESSION['user'])) {
            header("location:/");
        }
        $data['reportes'] = $this->model->obtenerReportes();
        $this->presenter->show('verReportes', $data);
    }
}
