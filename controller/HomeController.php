<?php

class HomeController
{

    private $presenter;
    private $model;
    public function __construct( $presenter, $model)
    {
        $this->model=$model;
        $this->presenter = $presenter;

    }

    public function show()
    {
        $this->presenter->show('home',[]);
    }

}