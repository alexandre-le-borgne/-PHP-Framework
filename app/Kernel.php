<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:36
 */
class Kernel {
    private static $instance;

    private function __construct() {
        Kernel::$instance = $this;
    }

    public static function getInstance(){
        if(!Kernel::$instance)
            Kernel::$instance = new Kernel();
        return Kernel::$instance;
    }

    public function response() {
        if (isset($_GET['controller']) && isset($_GET['action'])) {
            $controller = $_GET['controller'];
            $action     = $_GET['action'];
        } else {
            $controller = 'IndexController';
            $action     = 'index';
        }
        $controller = new $controller();
        return $controller->{$action.'Action'}();
    }
}