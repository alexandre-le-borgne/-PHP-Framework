<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/05/2016
 * Time: 22:09
 */
class View
{
     public static function extend($view) {
        Kernel::getInstance()->getViewManager()->getReferredViewPart()->setTemplate($view);
    }

    public static function render($view, $data = array()) {
        return Kernel::getInstance()->getViewManager()->render($view, $data);
        // Lancer une nouvelle série de vue depuis le manager
    }

    public static function output($data, $default = '') {

        $viewPartData = Kernel::getInstance()->getViewManager()->getReferredViewPart()->getData();
        if (isset($viewPartData[$data]))
            return $viewPartData[$data];
        return $default;
    }

    public static function escape($string) {
        return Security::escape($string);
    }

    public static function renderControllerAction($route, $data=array()) {
        echo Kernel::getInstance()->generateResponse($route, $data, true)->getResponse();
    }

    public static function isGranted($role) {
        return Session::getInstance()->isGranted($role);
    }

    public static function getAsset($asset) {
        return Kernel::getInstance()->getUrlFromPath('web/'.$asset);
    }

    public static function getUrlFromRoute($route) {
        return Kernel::getInstance()->getUrlFromPath($route);
    }

    public static function getChildContent() {
        return Kernel::getInstance()->getViewManager()->getReferredViewPart()->getChildContent();
    }
}