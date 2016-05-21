<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/05/2016
 * Time: 22:09
 */
class ViewManager
{
    private $referredViewPart;

    public function render($view, $data = array(), $childContent = null) {
        $path = Kernel::getInstance()->getPath('views/'.$view.'.php');

        if (file_exists($path))
        {
            $this->referredViewPart = new ViewPart($data, $childContent);

            extract($data);
            ob_start();
            require $path;
            $content = ob_get_clean();

            $template = $this->referredViewPart->getTemplate();

            if ($template)
            {
                return $this->render($template, $data, $content);
            }

            return $content;
        }
        else
        {
            throw new NotFoundException("VIEW NOT FOUND | " . $path . " |");
        }
    }

    /**
     * @return ViewPart
     */
    public function getReferredViewPart()
    {
        return $this->referredViewPart;
    }
}