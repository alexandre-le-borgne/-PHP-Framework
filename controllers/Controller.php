<?php
require_once("models/Model.php");

class Controller {
    public $model;

    public function __construct()
    {
   //     $this->model = new Model();
    }

    public function invoke()
    {
        require "views/persists/head.php";
        require "views/persists/header.php";
        require "views/persists/nav.php";
        require "views/index.php";
        require "views/persists/footer.php";
        require "views/persists/end.php";
    }
}