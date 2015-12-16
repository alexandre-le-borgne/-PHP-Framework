<?php
/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 14/12/15
 * Time: 14:17


 */

abstract class Controller {
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function render($view) {
        $view = new View($view);
        $view->render();
    }
}