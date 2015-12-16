<?php
class Route {

    public $view;

    public $controller;



    public function __construct($model, $view, $controller) {

        $this->view = $view;
        $this->controller = $controller;

    }

    public function getView() {

        return $this->view;

    }

    public function getController() {

        return $this->controller;

    }
}