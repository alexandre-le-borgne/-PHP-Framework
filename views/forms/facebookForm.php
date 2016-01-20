<?php
if(isset($loginUrl)) {
    echo '<a href="' . $this->escape($loginUrl) . '">Log in with Facebook!</a>';
}