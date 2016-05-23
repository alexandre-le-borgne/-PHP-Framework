<?php

/**
 * Class View
 */
final class View
{
    /**
     * @param string $view
     */
    public static function extend($view)
    {
        Kernel::getInstance()->getViewManager()->getReferredViewPart()->setTemplate($view);
    }

    /**
     * @param string $view
     * @param array $data
     * @return string
     * @throws NotFoundException
     */
    public static function render($view, $data = array())
    {
        return Kernel::getInstance()->getViewManager()->render($view, $data);
    }

    /**
     * @param string $data
     * @param string $default
     * @return string
     */
    public static function output($data, $default = '')
    {
        $viewPartData = Kernel::getInstance()->getViewManager()->getReferredViewPart()->getData();
        if (isset($viewPartData[$data]))
            return $viewPartData[$data];
        return $default;
    }

    /**
     * @param string $string
     * @return string
     */
    public static function escape($string)
    {
        return Security::escape($string);
    }

    /**
     * @param string $route
     * @param array $data
     */
    public static function renderControllerAction($route, $data = array())
    {
        echo Kernel::getInstance()->generateResponse($route, $data, true)->getResponse();
    }

    /**
     * @param int $role
     * @return bool
     */
    public static function isGranted($role)
    {
        return Session::getInstance()->isGranted($role);
    }

    /**
     * @param string $asset
     * @return string
     */
    public static function getAsset($asset)
    {
        return Kernel::getInstance()->getUrlFromPath('web/' . $asset);
    }

    /**
     * @param string $route
     * @return string
     */
    public static function getUrlFromRoute($route)
    {
        return Kernel::getInstance()->getUrlFromPath($route);
    }

    /**
     * @return string
     */
    public static function getChildContent()
    {
        return Kernel::getInstance()->getViewManager()->getReferredViewPart()->getChildContent();
    }
}