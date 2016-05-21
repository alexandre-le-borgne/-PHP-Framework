<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/05/2016
 * Time: 22:35
 */
class ViewPart
{
    /**
     * @var ViewPart $template
     */
    private $template;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $childContent;

    /**
     * ViewPart constructor.
     * @param array $data
     * @param string $childContent
     */
    public function __construct($data = array(), $childContent = '')
    {
        $this->data = $data;
        $this->childContent = $childContent;
        $this->template = null;
    }

    /**
     * @return ViewPart
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param ViewPart $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getChildContent()
    {
        return $this->childContent;
    }

}