<?php
if(isset($loginUrl)) {
    echo '<a href="' . $this->escape($loginUrl) . '"><div class="fblogin"><img src="web/img/fb_icon_325x325.png"><p>Connectez-vous avec Facebook</p></div></a>';
}