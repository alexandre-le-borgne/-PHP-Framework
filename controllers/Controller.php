<?php
//include_once("../models/Model.php");

class Controller {
    public $model;

    public function __construct()
    {
   //     $this->model = new Model();
    }

    public function invoke()
    {
        include "../views/persists/head.php";
        include "../views/persists/header.php";
        include "../views/persists/nav.php";
        include "../views/index.php";
        include "../views/persists/footer.php";
        include "../views/persists/end.php";
    }
}