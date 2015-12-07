<?php
include_once("models/Model.php");

class Controller {
    public $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function invoke()
    {
        include '../views/index.php';
    }
}