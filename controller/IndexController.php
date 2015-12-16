<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:37
 */
class IndexController extends Controller
{
    public function __construct() {
        parent::__construct(new IndexModel());
    }

    public function IndexAction()
    {
        $this->render('persists/home');
    }

    public function RegisterAction()
    {
        $username = '';
        if(isset($_POST['username']))
            $username = $_POST['username'];
        $data = array('username' => $username);
        $this->render('forms/registerForm', $data);
    }

    public function LoginAction()
    {
        $this->render('persists/home');
    }
}