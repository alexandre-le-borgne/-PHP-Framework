<?php
/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 14/12/15
 * Time: 14:17


 */

abstract class Controller {
    protected $models;
    private $data = array();

    public function set($data) {
        $this->data = array_merge($this->data, $data);
    }

    public function loadModel($model) {
        $modelspath = __DIR__.DIRECTORY_SEPARATOR.'../models/';
        require_once($modelspath.$model.'.php');
        $this->$model = new $model;
    }

    public function render($view, $data = array()) {
        $view = new View($view);
        $view->render($data);
    }

    public function redirect($url) {
        header('Location: '.$url);
    }

    public function redirectToRoute($route, $data) {

    }

    public function createNotFoundException($description) {
        throw new Exception($description);
    }
}