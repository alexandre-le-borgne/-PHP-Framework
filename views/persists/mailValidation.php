<?php
/**
 * Validation du mail (Model ?)
 */

View::getView('persists/head');

if (isset($message))
    echo $message;
else
    View::getView('index');

View::getView('persists/end');