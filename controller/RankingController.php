<?php

class RankingController
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
            exit();
        }
        $this->presenter->show('ranking', ['user' => $_SESSION['user']]);
    }
}