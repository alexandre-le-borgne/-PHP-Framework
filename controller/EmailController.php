<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 22:37
 */
class EmailController extends Controller
{
    public function IndexAction($id = null)
    {
        if (intval($id)) {

        } else {
            $this->loadModel('EmailModel');
            $emails = $this->emailmodel->getList();
            print_r($emails);
            $data = array('title' => 'Liste des emails', 'articles' => $emails);
            $this->render('layouts/articles', $data);
        }
    }
}