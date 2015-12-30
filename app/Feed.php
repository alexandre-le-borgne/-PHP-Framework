<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 30/12/2015
 * Time: 12:29
 */
class Feed // RSS Reader
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }


    public function getFeed() {
        try {
        $content = file_get_contents($this->url);
        $x = new SimpleXmlElement($content);
        var_dump($x);
        /*echo "<ul>";

        foreach($x->channel->item as $entry) {
            echo "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
        }
        echo "</ul>";
*/
        }
        catch(Exception $e) {
            throw new TraceableException("Le flux '$this->url' n'a pas pu être parsé en tant que flux RSS.");
        }
    }
}