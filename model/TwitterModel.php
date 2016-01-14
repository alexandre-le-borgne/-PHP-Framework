<?php
/**
 * Created by PhpStorm.
 * User: j13000355
 * Date: 04/01/16
 * Time: 17:19
 */

class TwitterRSSStream {

    private function parse($text) {
        $text = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $text);
        $text = preg_replace('#@([a-z0-9_]+)#i', '@<a href="http://twitter.com/$1">$1</a>', $text);
        $text = preg_replace('# \#([a-z0-9_-]+)#i', ' #<a href="http://search.twitter.com/search?q=%23$1">$1</a>', $text);
        return $text;
    }

    public function TwitterStream($user){
        $count = 5;
        $date_format = 'd M Y, H:i:s';

        $url = 'https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name='.$user.'&count='.$count;
        $oXML = simplexml_load_file( $url );
        echo '<ul>';
        foreach( $oXML->status as $oStatus ) {
            $datetime = date_create($oStatus->created_at);
            $date = date_format($datetime, $date_format)."\n";
            echo '<li>'.parse(utf8_decode($oStatus->text));
            echo ' (<a href="http://twitter.com/'.$user.'/status/'.$oStatus->id_str.'">'.$date.'</a>)</li>';
        }
        echo '</ul>';
    }
}