<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 30/12/2015
 * Time: 13:02
 */
class PostModel
{
    private $date;
    private $timestamp;
    private $link;
    private $title;
    private $text;
    private $summary;

    public function __construct($date, $timestamp, $link, $title, $text, $summary)
    {
        $this->date = $date;
        $this->timestamp = $timestamp;
        $this->link = $link;
        $this->title = $title;
        $this->text = $text;
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }
}