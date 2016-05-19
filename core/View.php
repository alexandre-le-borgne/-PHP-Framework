<?php

class View
{
    private static $instance;
    private $data = array();

    private function __construct()
    {
    }

    public function output($var, $default = '')
    {
        if (isset($this->data[$var]))
            return $this->data[$var];
        return $default;
    }

    public function escape($string)
    {
        return Security::escape($string);
    }

    public function render($view, $data = array())
    {
        if (!empty($data))
        {
            if (!empty($this->data))
            {
                $this->data = array_merge($data, $this->data);
            }
            else
            {
                $this->data = $data;
            }
        }
        $viewspath = __DIR__ . DIRECTORY_SEPARATOR;
        $path = $viewspath . $view . '.php';

        if (file_exists($path))
        {
            $data['view'] = new ViewPart();
            extract($data);
            ob_start();

            require $path;

            $content_for_layout = ob_get_clean();
            if ($data['view']->super())
            {
                $this->data['_content'] = $content_for_layout;
                $this->render($data['view']->super(), $this->data);
            }
            else
            {
                echo $content_for_layout;
            }
        }
        else
        {
            throw new NotFoundException("VIEW NOT FOUND | " . $path . " |");
        }
    }

    public function renderControllerAction($route, $data = array())
    {
        Kernel::getInstance()->generateResponse($route, $data, true);
    }

    public function isGranted($role)
    {
        return Session::getInstance()->isGranted($role);
    }

    public static function getView($view, $data = array())
    {
        if (self::$instance == null)
            self::$instance = new View();
        self::$instance->render($view, $data);
    }

    public static function getAsset($asset)
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . '/aaron/web/' . $asset;
    }

    public static function getUrlFromRoute($route)
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] . '/aaron/' . $route;
    }
}