<?php
include_once("helper/MysqlDatabase.php");
include_once("helper/IncludeFilePresenter.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");
include_once ("helper/FileEmailSender.php");

include_once("model/UserModel.php");
include_once ("model/PartidaModel.php");

include_once("controller/LoginController.php");
include_once("controller/HomeController.php");
include_once("controller/RegistroController.php");
include_once("controller/PerfilController.php");
include_once("controller/PartidaController.php");
include_once ("controller/RankingController.php");

include_once('vendor/Mustache/src/Mustache/Autoloader.php');

class Configuration
{
    public function __construct() {}

    //CONTROLADOR

    public function getLoginController()
    {
        return new LoginController($this->getPresenter(), $this->getUserModel());
    }

    public function getRegistroController()
    {
        return new RegistroController($this->getPresenter(), $this->getUserModel());
    }

    public function getHomeController()
    {
        return new HomeController($this->getPresenter(), $this->getUserModel());
    }

    public function getPerfilController()
    {
        return new PerfilController($this->getPresenter(), $this->getUserModel());
    }

    public function getPartidaController()
    {
        return new PartidaController($this->getPresenter(), $this->getPartidaModel());
    }

    public function getRankingController()
    {
        //por ahora le paso el user model, dps no se quien sera  el encargado de obtener el mayor puntaje
        //aunque podemo hacer trampa y dejarselo a usuario ajsjajj
        return new RankingController($this->getPresenter(), $this->getUserModel());
    }

    //MODELOS

    public function getUserModel()
    {
        return new UserModel($this->getDatabase(),$this->getFileEmailSender());
    }

    public function getPartidaModel()
    {
        return new PartidaModel($this->getDatabase());
    }

    //HELPER

    public function getRouter()
    {
        return new Router($this, "getLoginController", "show");
    }

    private function getDatabase()
    {
        $config = parse_ini_file('configuration/config.ini');
        return new MysqlDatabase(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'],
            $config["database"]
        );
    }

    private function getPresenter()
    {
        return new MustachePresenter("./view");
    }

    private function getFileEmailSender()
    {
        return new FileEmailSender();
    }

}
