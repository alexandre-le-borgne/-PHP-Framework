<?php

class ExempleController extends Controller
{
    public function IndexAction()
    {
        return $this->render('exemple/home', array("title" => "Mon titre de test", 'content' => 'Mon contenu'));
    }
}