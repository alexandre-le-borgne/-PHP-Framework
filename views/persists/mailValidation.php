<?php
/**
 * Validation du mail (Model ?)
 */

AbstractView::getView('persists/head');

if (isset($message))
    echo $message;
else
    AbstractView::getView('index');

AbstractView::getView('persists/end');