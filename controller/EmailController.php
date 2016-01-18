<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 22:37
 */
class EmailController extends Controller
{
    public function IndexAction($id = 0)
    {
        $this->loadModel('EmailModel');
        $email = $this->emailmodel->get($id);
        $header = (array) $email['header'];
        $data = array('title' => $header['subject'], 'content' => $email['body']);
        $this->render('layouts/email', $data);
    }
}