<?php
if(isset($authUrl)) {
    echo '<a href="' . $this->escape($loginUrl) . '">Log in with Facebook!</a>';
}