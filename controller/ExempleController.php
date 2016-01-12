<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/01/2016
 * Time: 18:28
 */
class ExempleController extends Controller
{
    public function IndexAction()
    {
        $this->renderClass('exemple/Home', array("title" => "Mon titre"));
    }
}